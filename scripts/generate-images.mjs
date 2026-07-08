import fs from 'fs';
import path from 'path';

const dir = 'public/images/products';
fs.mkdirSync(dir, { recursive: true });

const products = [
  { slug: 'cyberpunk-2077', label: 'Cyberpunk 2077', color: '#f53003', w: 400, h: 300 },
  { slug: 'witcher-3-wild-hunt', label: 'Witcher 3', color: '#f8b803', w: 400, h: 300 },
  { slug: 'minecraft', label: 'Minecraft', color: '#059669', w: 400, h: 300 },
  { slug: 'valorant', label: 'Valorant', color: '#4F46E5', w: 400, h: 300 },
  { slug: 'blinding-lights-the-weeknd', label: 'Blinding Lights', color: '#f53003', w: 400, h: 400 },
  { slug: 'shape-of-you-ed-sheeran', label: 'Shape of You', color: '#f8b803', w: 400, h: 400 },
  { slug: 'chill-lofi-beats-pack', label: 'Lofi Beats', color: '#059669', w: 400, h: 400 },
  { slug: 'complete-web-dev-course-2024', label: 'Web Dev Course', color: '#4F46E5', w: 400, h: 300 },
  { slug: 'nature-documentary-pack', label: 'Nature Docs', color: '#059669', w: 400, h: 300 },
  { slug: 'free-stock-footage-pack-v1', label: 'Stock Footage', color: '#f53003', w: 400, h: 300 },
  { slug: 'adobe-photoshop-2024', label: 'Photoshop 2024', color: '#4F46E5', w: 400, h: 300 },
  { slug: 'vlc-media-player', label: 'VLC Player', color: '#f8b803', w: 400, h: 300 },
  { slug: 'microsoft-office-2024', label: 'Office 2024', color: '#059669', w: 400, h: 300 },
];

function productSvg(label, color, w, h) {
  const fontSize = Math.min(18, Math.floor(w / (label.length * 0.55)));
  const safeLabel = label.replace(/&/g, '&amp;').replace(/</g, '&lt;');
  return `<svg xmlns="http://www.w3.org/2000/svg" width="${w}" height="${h}" viewBox="0 0 ${w} ${h}" role="img" aria-label="${safeLabel}">
  <rect width="100%" height="100%" fill="#1b1b18"/>
  <rect x="16" y="16" width="${w - 32}" height="${h - 32}" rx="12" fill="${color}" opacity="0.12"/>
  <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="${color}" font-family="system-ui,sans-serif" font-size="${fontSize}" font-weight="700">${safeLabel}</text>
</svg>`;
}

for (const product of products) {
  fs.writeFileSync(
    path.join(dir, `${product.slug}.svg`),
    productSvg(product.label, product.color, product.w, product.h),
  );
}

fs.writeFileSync(path.join(dir, 'default.svg'), productSvg('Store13', '#f53003', 400, 300));

const hero = `<svg xmlns="http://www.w3.org/2000/svg" width="640" height="360" viewBox="0 0 640 360" role="img" aria-label="Store13 digital marketplace">
  <rect width="100%" height="100%" fill="#1b1b18"/>
  <text x="320" y="170" text-anchor="middle" fill="#27272a" font-family="system-ui,sans-serif" font-size="120" font-weight="900">13</text>
  <rect x="80" y="200" width="140" height="100" rx="12" fill="#27272a" stroke="#3f3f46" transform="rotate(-6 150 250)"/>
  <rect x="250" y="185" width="140" height="100" rx="12" fill="#3f3f46" stroke="#f53003" stroke-width="2"/>
  <rect x="420" y="200" width="140" height="100" rx="12" fill="#27272a" stroke="#3f3f46" transform="rotate(6 490 250)"/>
  <circle cx="150" cy="235" r="16" fill="#f53003" opacity="0.8"/>
  <circle cx="320" cy="220" r="16" fill="#f8b803" opacity="0.9"/>
  <circle cx="490" cy="235" r="16" fill="#059669" opacity="0.8"/>
  <text x="320" y="330" text-anchor="middle" fill="#a1a1aa" font-family="system-ui,sans-serif" font-size="14" font-weight="600">Games · Music · Videos · Apps</text>
</svg>`;

fs.writeFileSync('public/images/hero.svg', hero);
console.log(`Created ${products.length + 2} SVG images`);
