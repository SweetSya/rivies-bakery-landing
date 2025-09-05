<?php

namespace App;

use Exception;
use Illuminate\Http\UploadedFile;

trait ValidatesImageUpload
{
    /**
     * Validate if the uploaded file is a secure image
     */
    public function validateSecureImage(UploadedFile $file): array
    {
        $errors = [];

        // 1. Check file size (max 2MB by default)
        $maxSize = config('app.max_image_upload_size', 2048); // KB
        if ($file->getSize() > ($maxSize * 1024)) {
            $errors[] = "Ukuran file terlalu besar. Maksimal {$maxSize}KB.";
        }

        // 2. Check MIME type
        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            $errors[] = 'Format file tidak didukung. Hanya JPEG, PNG, GIF, dan WebP yang diizinkan.';
        }

        // 3. Check file extension
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $allowedExtensions)) {
            $errors[] = 'Ekstensi file tidak valid.';
        }

        // 4. Validate actual file content (not just headers)
        if (!$this->isValidImageContent($file)) {
            $errors[] = 'File bukan gambar yang valid atau mengandung konten berbahaya.';
        }

        // 5. Check image dimensions
        $imageInfo = getimagesize($file->getPathname());
        if ($imageInfo !== false) {
            $width = $imageInfo[0];
            $height = $imageInfo[1];

            $minWidth = config('app.min_image_width', 50);
            $minHeight = config('app.min_image_height', 50);
            $maxWidth = config('app.max_image_width', 3000);
            $maxHeight = config('app.max_image_height', 3000);

            if ($width < $minWidth || $height < $minHeight) {
                $errors[] = "Dimensi gambar terlalu kecil. Minimal {$minWidth}x{$minHeight} pixel.";
            }

            if ($width > $maxWidth || $height > $maxHeight) {
                $errors[] = "Dimensi gambar terlalu besar. Maksimal {$maxWidth}x{$maxHeight} pixel.";
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'file_info' => $imageInfo ? [
                'width' => $imageInfo[0],
                'height' => $imageInfo[1],
                'mime_type' => $imageInfo['mime'],
                'size' => $file->getSize(),
                'original_name' => $file->getClientOriginalName(),
                'extension' => $extension
            ] : null
        ];
    }

    /**
     * Check if file content is actually a valid image
     */
    private function isValidImageContent(UploadedFile $file): bool
    {
        try {
            // 1. Check MIME type using finfo
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file->getPathname());
            finfo_close($finfo);

            $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($mimeType, $allowedMimes)) {
                return false;
            }

            // 2. Try to get image information
            $imageInfo = getimagesize($file->getPathname());
            if ($imageInfo === false) {
                return false;
            }

            // 3. Check for malicious content
            if ($this->containsMaliciousContent($file)) {
                return false;
            }

            // 4. Try to create image resource (final validation)
            return $this->canCreateImageResource($file, $mimeType);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Check for malicious content in the file
     */
    private function containsMaliciousContent(UploadedFile $file): bool
    {
        $content = file_get_contents($file->getPathname());

        // Check for common malicious patterns
        $maliciousPatterns = [
            '<?php',
            '<?=',
            '<script',
            'javascript:',
            'onload=',
            'onerror=',
            'eval(',
            'system(',
            'exec(',
            'shell_exec(',
            'passthru(',
            'base64_decode(',
        ];

        foreach ($maliciousPatterns as $pattern) {
            if (stripos($content, $pattern) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Try to create image resource to validate it's a real image
     */
    private function canCreateImageResource(UploadedFile $file, string $mimeType): bool
    {
        try {
            $image = false;

            switch ($mimeType) {
                case 'image/jpeg':
                    $image = @imagecreatefromjpeg($file->getPathname());
                    break;
                case 'image/png':
                    $image = @imagecreatefrompng($file->getPathname());
                    break;
                case 'image/gif':
                    $image = @imagecreatefromgif($file->getPathname());
                    break;
                case 'image/webp':
                    $image = @imagecreatefromwebp($file->getPathname());
                    break;
            }

            if ($image !== false) {
                imagedestroy($image);
                return true;
            }

            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Get image validation rules for Laravel validation
     */
    public function getImageValidationRules(array $options = []): array
    {
        $maxSize = $options['max_size'] ?? config('app.max_image_upload_size', 2048);
        $required = $options['required'] ?? false;

        $rules = [
            $required ? 'required' : 'nullable',
            'file',
            'image',
            'mimes:jpeg,jpg,png,gif,webp',
            "max:{$maxSize}",
        ];

        if (isset($options['dimensions'])) {
            $rules[] = 'dimensions:' . $options['dimensions'];
        }

        return $rules;
    }
}
