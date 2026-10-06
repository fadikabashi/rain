<?php

namespace App\Services\Helpers;

use App\Constants\FileUpload;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Helper class for handling photo uploads and deletions.
 */
class PhotoHandler
{
    /**
     * Store uploaded photo and return full path.
     *
     * @param UploadedFile|null $photo
     * @return string|null Full path to stored photo, or null if no photo provided
     */
    public static function store(?UploadedFile $photo): ?string
    {
        if (!$photo || !$photo->isValid()) {
            return null;
        }

        $path = $photo->store(FileUpload::USER_UPLOAD_PATH, FileUpload::DEFAULT_DISK);
        return FileUpload::getFullPath($path);
    }

    /**
     * Delete photo from storage.
     *
     * @param string|null $photoPath Full path to photo (e.g., 'files/users/photo.jpg')
     * @return bool True if deleted, false if no photo path provided
     */
    public static function delete(?string $photoPath): bool
    {
        if (!$photoPath) {
            return false;
        }

        // Remove 'files/' prefix to get storage path
        $storagePath = str_replace(FileUpload::FILE_PREFIX, '', $photoPath);
        
        return Storage::disk(FileUpload::DEFAULT_DISK)->delete($storagePath);
    }

    /**
     * Replace old photo with new one.
     * Deletes old photo if exists, then stores new one.
     *
     * @param string|null $oldPhotoPath Current photo path
     * @param UploadedFile|null $newPhoto New photo to upload
     * @return string|null Full path to new photo, or null if no new photo
     */
    public static function replace(?string $oldPhotoPath, ?UploadedFile $newPhoto): ?string
    {
        // Delete old photo if exists
        if ($oldPhotoPath) {
            self::delete($oldPhotoPath);
        }

        // Store new photo
        return self::store($newPhoto);
    }
}
