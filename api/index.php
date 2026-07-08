<?php

// Vercel: create writable directories in /tmp
$dirs = [
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/logs',
    '/tmp/storage/app/public',
    '/tmp/cache',
];
foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Copy bootstrap cache files to /tmp/cache so Laravel can use & update them
$srcCache = __DIR__ . '/../bootstrap/cache';
foreach (['packages.php', 'services.php'] as $file) {
    $src = $srcCache . '/' . $file;
    $dst = '/tmp/cache/' . $file;
    if (file_exists($src) && !file_exists($dst)) {
        copy($src, $dst);
    }
}

// SQLite database in /tmp
$dbPath = '/tmp/database.sqlite';
if (!file_exists($dbPath)) {
    touch($dbPath);
}

// Set all required env vars for Vercel runtime
$envVars = [
    'DB_DATABASE'         => $dbPath,
    'APP_SERVICES_CACHE'  => '/tmp/cache/services.php',
    'APP_PACKAGES_CACHE'  => '/tmp/cache/packages.php',
    'APP_CONFIG_CACHE'    => '/tmp/cache/config.php',
    'APP_ROUTES_CACHE'    => '/tmp/cache/routes-v7.php',
    'APP_EVENTS_CACHE'    => '/tmp/cache/events.php',
    'VIEW_COMPILED_PATH'  => '/tmp/storage/framework/views',
];
foreach ($envVars as $key => $value) {
    putenv("{$key}={$value}");
    $_ENV[$key]    = $value;
    $_SERVER[$key] = $value;
}

// Auto-run migrations & seed on cold start
$migrationFlag = '/tmp/.db_migrated';
if (!file_exists($migrationFlag) || filesize($dbPath) < 100) {
    $artisan = __DIR__ . '/../artisan';
    $php = PHP_BINARY ?: 'php';
    exec("{$php} {$artisan} migrate --force --seed 2>&1", $output, $code);
    if ($code === 0) {
        file_put_contents($migrationFlag, date('Y-m-d H:i:s'));
    }
}

// Forward Vercel requests to the normal Laravel index.php
require __DIR__ . '/../public/index.php';
