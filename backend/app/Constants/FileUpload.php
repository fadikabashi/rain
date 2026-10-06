<?php

namespace App\Constants;

/**
 * File upload constants.
 */
class FileUpload
{
    /** Default disk for file uploads */
    public const DEFAULT_DISK = 'public_file';

    /** Default storage path for user uploads */
    public const USER_UPLOAD_PATH = 'users';

    /** Default file prefix */
    public const FILE_PREFIX = 'files/';

    /** Maximum file size in KB */
    public const MAX_FILE_SIZE_KB = 2048; // 2MB

    /** Allowed image MIME types */
    public const ALLOWED_IMAGE_TYPES = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
    ];

    /**
     * Get full file path.
     *
     * @param string $path
     * @return string
     */
    public static function getFullPath(string $path): string
    {
        return self::FILE_PREFIX . $path;
    }
}
