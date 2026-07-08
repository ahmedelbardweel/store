<?php

// Vercel: create writable directories in /tmp (only writable location)
$dirs = [
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/logs',
    '/tmp/storage/app/public',
];
foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// SQLite database in /tmp
$dbPath = '/tmp/database.sqlite';
if (!file_exists($dbPath)) {
    touch($dbPath);
}

// Set environment variables for Vercel runtime
putenv("DB_DATABASE={$dbPath}");
$_ENV['DB_DATABASE'] = $dbPath;
$_SERVER['DB_DATABASE'] = $dbPath;

putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');
$_ENV['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';
$_SERVER['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';

// Auto-run migrations on cold start (flag stored in /tmp)
$migrationFlag = '/tmp/.db_migrated';
if (!file_exists($migrationFlag) || filesize($dbPath) < 100) {
    $artisan = __DIR__ . '/../artisan';
    $php = PHP_BINARY ?: 'php';
    exec("{$php} {$artisan} migrate --force 2>&1", $output, $code);
    if ($code === 0) {
        file_put_contents($migrationFlag, date('Y-m-d H:i:s'));
    }
}

// Forward Vercel requests to the normal Laravel index.php
require __DIR__ . '/../public/index.php';
