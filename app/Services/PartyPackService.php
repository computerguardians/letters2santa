<?php

namespace App\Services;

use App\Models\Business;
use App\Models\Event;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

/**
 * QR codes, the printable A4 poster and host logos. Everything is generated on demand from the
 * event's public URL, so nothing has to be regenerated if the domain changes.
 */
class PartyPackService
{
    public function __construct(private ImageManager $images) {}

    public function qrSvg(Event $event, int $size = 600): string
    {
        return (string) QrCode::format('svg')->size($size)->margin(1)->errorCorrection('M')
            ->color(22, 91, 51)->generate($event->publicUrl());
    }

    public function qrPng(Event $event, int $size = 900): string
    {
        return (string) QrCode::format('png')->size($size)->margin(1)->errorCorrection('M')
            ->color(22, 91, 51)->generate($event->publicUrl());
    }

    /** A4 portrait poster as PDF bytes. */
    public function posterPdf(Event $event): string
    {
        $logo = $this->logoDataUri($event);

        return Pdf::loadView('pdf.party-poster', [
            'event' => $event,
            'business' => $event->business,
            'qr' => 'data:image/png;base64,'.base64_encode($this->qrPng($event, 1000)),
            'url' => $event->publicUrl(),
            'shortUrl' => preg_replace('#^https?://#', '', $event->publicUrl()),
            'logo' => $logo,
        ])->setPaper('a4', 'portrait')->output();
    }

    public function posterFilename(Event $event): string
    {
        return 'letters2santa-poster-'.Str::slug($event->name).'.pdf';
    }

    /**
     * Store a host logo (event or business) as a metadata-free PNG on the private uploads disk.
     * Returns the stored path.
     */
    public function storeLogo(UploadedFile $file, string $dir): string
    {
        $disk = config('letters.uploads_disk');
        $png = $this->images->read($file->getRealPath())->scaleDown(width: 800, height: 400)->toPng();
        $path = $dir.'/'.Str::random(20).'.png';
        Storage::disk($disk)->put($path, (string) $png);

        return $path;
    }

    public function deleteLogo(?string $path): void
    {
        if ($path) {
            Storage::disk(config('letters.uploads_disk'))->delete($path);
        }
    }

    public function logoUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        return Storage::disk(config('letters.uploads_disk'))->temporaryUrl($path, now()->addMinutes((int) config('letters.signed_url_minutes')));
    }

    /** Event logo, falling back to the business logo, as a data URI for the PDF. */
    public function logoDataUri(Event $event): ?string
    {
        $path = $event->logo_path ?: $event->business?->logo_path;
        if (! $path) {
            return null;
        }
        $disk = Storage::disk(config('letters.uploads_disk'));
        if (! $disk->exists($path)) {
            return null;
        }

        return 'data:image/png;base64,'.base64_encode((string) $disk->get($path));
    }
}
