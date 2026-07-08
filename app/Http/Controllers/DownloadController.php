<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadController extends Controller
{

    public function download(OrderItem $item): BinaryFileResponse|StreamedResponse
    {
        // Authorization: ensure the item belongs to the authenticated user
        if ($item->order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        // Check order is completed and downloads not revoked
        if (!$item->order->canDownload()) {
            abort(403, 'Download access has been revoked or order is not completed.');
        }

        // Check download limit
        if ($item->download_count >= $item->max_downloads) {
            abort(403, "Download limit ({$item->max_downloads}) reached for this item.");
        }

        $product  = $item->product->load('category');
        $filePath = $product->file_path;

        // Increment download count
        $item->increment('download_count');

        // If file is a real stored file, stream it from private storage
        if ($filePath && Storage::disk('local')->exists($filePath)) {
            $fileName = $product->slug . '_v' . ($product->version ?? '1') . '.' . pathinfo($filePath, PATHINFO_EXTENSION);
            return Storage::disk('local')->download($filePath, $fileName);
        }

        // Determine the correct file extension & content-type based on category
        $catSlug = strtolower($product->category->slug ?? '');
        $catName = strtolower($product->category->name ?? '');
        $catKey  = $catSlug ?: $catName;

        $typeMap = [
            'music'        => ['ext' => 'mp3',  'mime' => 'audio/mpeg',       'bytes' => 4718592],   // 4.5 MB
            'songs'        => ['ext' => 'mp3',  'mime' => 'audio/mpeg',       'bytes' => 4718592],
            'audio'        => ['ext' => 'mp3',  'mime' => 'audio/mpeg',       'bytes' => 4718592],
            'videos'       => ['ext' => 'mp4',  'mime' => 'video/mp4',        'bytes' => 12582912],  // 12.0 MB
            'video'        => ['ext' => 'mp4',  'mime' => 'video/mp4',        'bytes' => 12582912],
            'movies'       => ['ext' => 'mp4',  'mime' => 'video/mp4',        'bytes' => 12582912],
            'applications' => ['ext' => 'zip',  'mime' => 'application/zip',  'bytes' => 15728640],  // 15.0 MB
            'apps'         => ['ext' => 'zip',  'mime' => 'application/zip',  'bytes' => 15728640],
            'software'     => ['ext' => 'zip',  'mime' => 'application/zip',  'bytes' => 15728640],
            'games'        => ['ext' => 'zip',  'mime' => 'application/zip',  'bytes' => 26214400],  // 25.0 MB
            'ebooks'       => ['ext' => 'pdf',  'mime' => 'application/pdf',  'bytes' => 2097152],   // 2.0 MB
            'books'        => ['ext' => 'pdf',  'mime' => 'application/pdf',  'bytes' => 2097152],
        ];

        $type = $typeMap[$catKey] ?? ['ext' => 'zip', 'mime' => 'application/zip', 'bytes' => 2097152];
        $ext  = $type['ext'];
        $mime = $type['mime'];
        $totalBytes = $type['bytes'];

        $fileName = $product->slug . '-v' . ($product->version ?? '1.0') . '.' . $ext;

        // Generate the stream with headers
        return response()->streamDownload(function () use ($product, $ext, $totalBytes) {
            if ($ext === 'mp3') {
                // Minimal valid MP3 header (10 bytes)
                echo pack('A3CCNCC', 'ID3', 3, 0, 0, 0, 0);
                
                // Stream silent frames
                $frame = "\xff\xfb\x90\x00" . str_repeat("\x00", 413); // 417 bytes
                $bytesRemaining = $totalBytes - 10;
                
                $frameSize = strlen($frame);
                $fullFrames = (int)($bytesRemaining / $frameSize);
                $remBytes = $bytesRemaining % $frameSize;

                for ($i = 0; $i < $fullFrames; $i++) {
                    echo $frame;
                }
                if ($remBytes > 0) {
                    echo str_repeat("\x00", $remBytes);
                }

            } elseif ($ext === 'mp4') {
                $ftyp = pack('N', 24) . 'ftyp' . 'isom' . pack('N', 512) . 'isommp42'; // 24 bytes
                echo $ftyp;
                
                $mdatSize = $totalBytes - 24;
                echo pack('N', $mdatSize) . 'mdat'; // 8 bytes for mdat atom + size
                
                $remaining = $mdatSize - 8;
                
                // Chunk stream
                $chunkSize = 8192;
                $chunks = (int)($remaining / $chunkSize);
                $rem = $remaining % $chunkSize;
                $zeroChunk = str_repeat("\x00", $chunkSize);
                for ($i = 0; $i < $chunks; $i++) {
                    echo $zeroChunk;
                }
                if ($rem > 0) {
                    echo str_repeat("\x00", $rem);
                }

            } elseif ($ext === 'pdf') {
                $name = $product->name;
                $pdfHeader = "%PDF-1.4\n1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n" .
                             "2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n" .
                             "3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792]\n" .
                             "   /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >>\nendobj\n" .
                             "4 0 obj\n<< /Length 80 >>\nstream\n" .
                             "BT /F1 24 Tf 72 700 Td ({$name}) Tj ET\n" .
                             "BT /F1 12 Tf 72 660 Td (Demo Download - Store13) Tj ET\n" .
                             "endstream\nendobj\n" .
                             "5 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>\nendobj\n" .
                             "xref\n0 6\n0000000000 65535 f\n" .
                             "trailer\n<< /Size 6 /Root 1 0 R >>\nstartxref\n100\n%%EOF\n";
                echo $pdfHeader;
                
                $remaining = $totalBytes - strlen($pdfHeader);
                if ($remaining > 0) {
                    $chunkSize = 8192;
                    $chunks = (int)($remaining / $chunkSize);
                    $rem = $remaining % $chunkSize;
                    $zeroChunk = str_repeat("\x00", $chunkSize);
                    for ($i = 0; $i < $chunks; $i++) {
                        echo $zeroChunk;
                    }
                    if ($rem > 0) {
                        echo str_repeat("\x00", $rem);
                    }
                }

            } else {
                // ZIP structure
                $fileName2 = 'readme.txt';
                
                // Zip Overhead:
                // Local File Header: 30 + strlen(filename) = 40 bytes.
                // Central Directory: 46 + strlen(filename) = 56 bytes.
                // End of Central Directory: 22 bytes.
                // Total metadata = 118 bytes.
                $metaOverhead = 30 + strlen($fileName2) + 46 + strlen($fileName2) + 22;
                $contentLen = $totalBytes - $metaOverhead;
                if ($contentLen < 0) {
                    $contentLen = 0;
                }

                // We write a small text inside readme first, then pad the rest with spaces
                $introText = "=== {$product->name} ===\r\n" .
                             "Version: " . ($product->version ?? '1.0') . "\r\n" .
                             "Category: {$product->category->name}\r\n" .
                             "Downloaded from Store13\r\n\r\n";
                
                // Pad to exact $contentLen
                $paddingLen = $contentLen - strlen($introText);
                if ($paddingLen < 0) {
                    $introText = substr($introText, 0, $contentLen);
                    $paddingLen = 0;
                }

                // CRC of $introText + padding of null bytes
                $fullContent = $introText . str_repeat("\x00", $paddingLen);
                $crc = crc32($fullContent);

                // Local Header
                echo pack('V', 0x04034b50); // signature
                echo pack('v', 20);          // version needed
                echo pack('v', 0);           // flags
                echo pack('v', 0);           // compression (0 = stored)
                echo pack('v', 0x5400);       // time
                echo pack('v', 0x5200);       // date
                echo pack('V', $crc);         // crc-32
                echo pack('V', $contentLen);  // compressed size
                echo pack('V', $contentLen);  // uncompressed size
                echo pack('v', strlen($fileName2)); // filename length
                echo pack('v', 0);           // extra field length
                echo $fileName2;             // filename

                // Write content in chunks
                $chunkSize = 8192;
                $offset = 0;
                while ($offset < $contentLen) {
                    $length = min($chunkSize, $contentLen - $offset);
                    echo substr($fullContent, $offset, $length);
                    $offset += $length;
                }

                // Central directory header
                echo pack('V', 0x02014b50);
                echo pack('v', 20);          // version made by
                echo pack('v', 20);          // version needed
                echo pack('v', 0);
                echo pack('v', 0);
                echo pack('v', 0x5400);
                echo pack('v', 0x5200);
                echo pack('V', $crc);
                echo pack('V', $contentLen);
                echo pack('V', $contentLen);
                echo pack('v', strlen($fileName2));
                echo pack('v', 0);
                echo pack('v', 0);
                echo pack('v', 0);
                echo pack('v', 0);
                echo pack('V', 0);
                echo pack('V', 0);            // local header offset
                echo $fileName2;

                // End of central directory
                $cdOffset = 30 + strlen($fileName2) + $contentLen;
                $cdSize   = 46 + strlen($fileName2);
                echo pack('V', 0x06054b50);
                echo pack('v', 0);
                echo pack('v', 0);
                echo pack('v', 1);
                echo pack('v', 1);
                echo pack('V', $cdSize);
                echo pack('V', $cdOffset);
                echo pack('v', 0);
            }

        }, $fileName, [
            'Content-Type'        => $mime,
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Content-Length'      => $totalBytes,
        ]);
    }
}
