<?php


/**
 * Global helper functions.
 *
 * These are loaded via composer.json's "files" autoload.
 * Run `composer dump-autoload` after adding or modifying this file.
 */

use Illuminate\Support\Facades\Storage;


// ─────────────────────────────────────────────────────────────
// STORAGE / MEDIA
// ─────────────────────────────────────────────────────────────


if (! function_exists('storage_url')) {
    /**
     * Resolve a storage path into a full public URL.
     *
     * Handles three cases gracefully:
     *   1. null / empty     → returns null
     *   2. Already a full URL (legacy data) → returns as-is
     *   3. A path           → builds the URL from the given disk
     *
     * This protects against double-prefixing when old records
     * store full URLs while new ones store paths.
     *
     * @param  string|null  $path   Path or URL stored in the DB.
     * @param  string       $disk   Filesystem disk (default: 's3').
     * @return string|null
     */

    function  storage_url(?string $path, string $disk = 's3'): ?string
    {
        if (empty($path)) {
            return null;
        }

        // Already a full URL — don't prepend anything

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return Storage::disk($disk)->url($path);
    }
}

if (! function_exists('storage_path_from_url')) {
    /**
     * Extract the storage path from a full URL.
     *
     * Useful when you need to delete or replace a file whose DB value
     * is a full URL (legacy data).
     *
     * Returns null if the input isn't a valid URL.
     */

    function storage_path_from_url(?string $url, string $disk = 's3'): ?string
    {
        if (empty($url)) {
            return null;
        }

        if (! str_starts_with($url, 'http://') && ! str_starts_with($url, 'https://')) {
            return ltrim($url, '/');
        }

        $parsed = parse_url($url);
        $path = ltrim($parsed['path'] ?? '', '/');

        // Strip the bucket prefix if the disk uses one
        $bucket = config("filesystems.disks.{$disk}.bucket");
        if ($bucket && str_starts_with($path, "{$bucket}/")) {
            $path = substr($path, strlen($bucket) + 1);
        }

        return $path ?: null;
    }
}


if (! function_exists('delete_storage_file')) {
    /**
     * Safely delete a file from a storage disk, whether the DB value
     * is a full URL or a path.
     *
     * @return bool  True if the file was deleted, false otherwise.
     */

    function delete_storage_file(?string $pathOrUrl, string $disk = 's3'): bool
    {
        if (empty($pathOrUrl)) {
            return false;
        }

        $path = storage_path_from_url($pathOrUrl, $disk);

        if ($path && Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->delete($path);
        }

        return false;
    }
}
