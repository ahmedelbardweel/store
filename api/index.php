<?php

// Auto-setup for Vercel: ensure SQLite DB exists in /tmp (writable dir)
$dbPath = '/tmp/database.sqlite';
$isNewDb = !file_exists($dbPath) || filesize($dbPath) === 0;
if (!file_exists($dbPath)) {
    touch($dbPath);
}

// Set environment variables for Vercel runtime
putenv("DB_DATABASE={$dbPath}");
$_ENV['DB_DATABASE'] = $dbPath;
$_SERVER['DB_DATABASE'] = $dbPath;

// Forward Vercel requests to the normal Laravel index.php
require __DIR__ . '/../public/index.php';
