<?php

namespace Globalis\WP\Cubi\ImageWebp;

class ImageWebp
{
    public static function generateWebPMainFile($file)
    {
        $gdImage = self::generateGdImage($file['file'], $file['type']);

        if (!$gdImage) {
            return $file;
        }

        $fileName = explode('/', $file['file']);
        $fileName = $fileName[array_key_last($fileName)];
        $uploadDir = wp_upload_dir();
        $filePath = $uploadDir['path'] . '/' . $fileName . '.webp';

        self::writeWebpImage($gdImage, $filePath);

        return $file;
    }

    public static function generateWebPSubFile($metadata)
    {
        foreach ($metadata['sizes'] as $file) {
            $uploadDir = wp_upload_dir();

            $gdImage = self::generateGdImage($uploadDir['path'] . '/' . $file['file'], $file['mime-type']);

            if (!$gdImage) {
                return $metadata;
            }

            $filePath = $uploadDir['path'] . '/' . $file['file'] . '.webp';

            self::writeWebpImage($gdImage, $filePath);
        }

        return $metadata;
    }

    public static function deleteWebPSubFile($file)
    {
        $fileWebP = $file . '.webp';

        if (file_exists($fileWebP)) {
            unlink($fileWebP);
        }

        return $file;
    }

    /**
     * Generate GdImage from file
     *
     * @param string $filePath
     * @param string $mimeType
     * @return \GdImage|null Return the GdImage on success, false otherwise
     */
    public static function generateGdImage(string $filePath, string $mimeType = ''): ?\GdImage
    {
        if ($mimeType === '') {
            $mimeType = mime_content_type($filePath);
        }

        // Convert uploaded image to webp
        switch ($mimeType) {
            case 'image/png':
                $gdImage = imagecreatefrompng($filePath);
                break;

            case 'image/jpg':
            case 'image/jpeg':
                $gdImage = imagecreatefromjpeg($filePath);
                break;

            default:
                return false;
                break;
        }

        imagepalettetotruecolor($gdImage);

        return $gdImage;
    }

    /**
     * Convert and write the webp image to the upload directory
     *
     * @param \GdImage $gdImage
     * @param string $filePath
     * @return void
     */
    public static function writeWebpImage(\GdImage $gdImage, string $filePath, bool $force = false): bool
    {
        if (file_exists($filePath) && !$force) {
            return false;
        }

        $image = fopen($filePath, 'w+');

        if (!$image) {
            return false;
        }

        imagewebp($gdImage, $image);

        return true;
    }
}
