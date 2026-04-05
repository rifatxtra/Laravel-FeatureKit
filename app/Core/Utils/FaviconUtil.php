<?php

namespace App\Core\Utils;

use Illuminate\Http\UploadedFile;

class FaviconUtil
{
    /**
     * Convert an uploaded image to a standard favicon format.
     * Resizes to 32x32 and saves as PNG (which is modern standard).
     *
     * @param UploadedFile $file
     * @param string $destinationPath
     * @return bool
     */
    public static function convert(UploadedFile $file, string $destinationPath): bool
    {
        $extension = strtolower($file->getClientOriginalExtension());
        
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                $image = imagecreatefromjpeg($file->getRealPath());
                break;
            case 'png':
                $image = imagecreatefrompng($file->getRealPath());
                break;
            case 'webp':
                $image = imagecreatefromwebp($file->getRealPath());
                break;
            default:
                return false;
        }

        if (!$image) {
            return false;
        }

        // Create a 32x32 blank canvas
        $favicon = imagecreatetruecolor(32, 32);
        
        // Preserve transparency for PNG
        imagealphablending($favicon, false);
        imagesavealpha($favicon, true);
        $transparent = imagecolorallocatealpha($favicon, 255, 255, 255, 127);
        imagefilledrectangle($favicon, 0, 0, 32, 32, $transparent);

        // Resize the original image to 32x32
        $width = imagesx($image);
        $height = imagesy($image);
        imagecopyresampled($favicon, $image, 0, 0, 0, 0, 32, 32, $width, $height);

        // Start buffering to capture PNG data
        ob_start();
        imagepng($favicon);
        $pngData = ob_get_clean();

        // Standard ICO Header (6 bytes)
        // Reserved (0), Type (1 = ICO), Count (1 image)
        $header = pack('v3', 0, 1, 1);

        // ICO Directory Entry (16 bytes)
        // Width (32), Height (32), Colors (0), Reserved (0), Planes (1), BitCount (32), Size (PNG size), Offset (22)
        $entry = pack('C4v2V2', 32, 32, 0, 0, 1, 32, strlen($pngData), 22);

        // Combined ICON file content
        $icoContent = $header . $entry . $pngData;

        // Save to the destination
        $result = file_put_contents($destinationPath, $icoContent) !== false;

        // Cleanup
        imagedestroy($image);
        imagedestroy($favicon);

        return $result;
    }
}
