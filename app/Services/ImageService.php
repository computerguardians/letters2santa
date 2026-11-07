<?php

namespace App\Services;

use App\Models\Letter;
use App\Models\LetterImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

/**
 * Turns an upload (jpg/png/webp/heic) into a private, metadata-free 1080px JPEG plus a thumbnail.
 * The original file is never stored.
 */
class ImageService
{
    public function __construct(private ImageManager $manager) {}

    public function store(UploadedFile $file, Letter $letter): LetterImage
    {
        $disk = config('letters.uploads_disk');
        $displayPx = (int) config('letters.letter.display_px');
        $thumbPx = (int) config('letters.letter.thumb_px');

        // read() auto-orients from EXIF; HEIC is decoded by Imagick. Encoding with strip: true drops EXIF/GPS/XMP.
        $image = $this->manager->read($file->getRealPath());
        $display = $image->scaleDown(width: $displayPx, height: $displayPx)->toJpeg(quality: 85, strip: true);

        $thumbImage = $this->manager->read((string) $display);
        $thumb = $thumbImage->scaleDown(width: $thumbPx, height: $thumbPx)->toJpeg(quality: 80, strip: true);

        $dir = 'letters/'.$letter->uuid;
        $name = Str::random(20);
        $path = "{$dir}/{$name}.jpg";
        $thumbPath = "{$dir}/{$name}_thumb.jpg";

        Storage::disk($disk)->put($path, (string) $display);
        Storage::disk($disk)->put($thumbPath, (string) $thumb);

        return $letter->images()->create([
            'disk' => $disk,
            'path' => $path,
            'thumb_path' => $thumbPath,
            'width' => $image->width(),
            'height' => $image->height(),
            'bytes' => strlen((string) $display),
        ]);
    }

    /** Validate that a file really is a decodable image (mime sniffing alone is not enough). */
    public function isDecodable(UploadedFile $file): bool
    {
        try {
            $this->manager->read($file->getRealPath());

            return true;
        } catch (\Throwable) {
            return false;
        }
    }
}
