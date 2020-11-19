<?php

namespace App\Packages\Files\Support;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class Image
{
    /**
     * @static
     * @param  UploadedFile  $file
     * @param $destination string Path for destination image to be placed
     * @param $targetWidth int Width of the new image (in pixels)
     * @param $targetHeight int Height of the new image (in pixels)
     * @param $strict bool
     */
    public static function create(
        UploadedFile $file,
        string $destination,
        int $targetWidth,
        int $targetHeight,
        $strict = false
    ): void {
        $dir = dirname($destination);
        if (!is_dir($dir) && !mkdir($dir, 0770, true) && !is_dir($dir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $dir));
        }
        $fileContents = file_get_contents($file->getPath());
        $image = imagecreatefromstring($fileContents);

        $thumbnail = self::resizeImage($image, $targetWidth, $targetHeight, $strict);

        imagejpeg($thumbnail, $destination, 100);
        imagedestroy($thumbnail);
        imagedestroy($image);
    }

    public static function resizeImage($original, int $targetWidth, int $targetHeight, $strict = false)
    {
        $originalWidth = imagesx($original);
        $originalHeight = imagesy($original);

        $widthRatio = $targetWidth / $originalWidth;
        $heightRatio = $targetHeight / $originalHeight;

        if (($widthRatio > 1 || $heightRatio > 1) && !$strict) {
            // don't scale up an image if either targets are greater than the original sizes and we aren't using a strict parameter
            $dstHeight = $originalHeight;
            $dstWidth = $originalWidth;
            $srcHeight = $originalHeight;
            $srcWidth = $originalWidth;
            $srcX = 0;
            $srcY = 0;
        } elseif ($widthRatio > $heightRatio) {
            // width is the constraining factor
            if ($strict) {
                $dstHeight = $targetHeight;
                $dstWidth = $targetWidth;
                $srcHeight = $originalHeight;
                $srcWidth = $heightRatio * $targetWidth;
                $srcX = floor(($originalWidth - $srcWidth) / 2);
                $srcY = 0;
            } else {
                $dstHeight = ($originalHeight * $targetWidth) / $originalWidth;
                $dstWidth = $targetWidth;
                $srcHeight = $originalHeight;
                $srcWidth = $originalWidth;
                $srcX = 0;
                $srcY = 0;
            }
        } elseif ($strict) {
            $dstHeight = $targetHeight;
            $dstWidth = $targetWidth;
            $srcHeight = $widthRatio * $targetHeight;
            $srcWidth = $originalWidth;
            $srcY = floor(($originalHeight - $srcHeight) / 2);
            $srcX = 0;
        } else {
            $dstHeight = $targetHeight;
            $dstWidth = ($originalWidth * $targetHeight) / $originalHeight;
            $srcHeight = $originalHeight;
            $srcWidth = $originalWidth;
            $srcX = 0;
            $srcY = 0;
        }

        $new = imagecreatetruecolor($dstWidth, $dstHeight);

        if ($new === false) {
            return false;
        }

        imagecopyresampled($new, $original, 0, 0, $srcX, $srcY, $dstWidth, $dstHeight, $srcWidth, $srcHeight);

        return $new;
    }

}
