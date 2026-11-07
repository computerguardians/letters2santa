<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class LetterImage extends Model
{
    protected $fillable = [
        'letter_id', 'disk', 'path', 'thumb_path', 'width', 'height', 'bytes',
        'moderation_labels', 'has_face', 'flagged', 'moderated',
    ];

    protected function casts(): array
    {
        return [
            'moderation_labels' => 'array',
            'has_face' => 'boolean',
            'flagged' => 'boolean',
            'moderated' => 'boolean',
        ];
    }

    public function letter(): BelongsTo
    {
        return $this->belongsTo(Letter::class);
    }

    /** Short-lived signed URL for private storage. */
    public function url(?int $minutes = null): string
    {
        return Storage::disk($this->disk)->temporaryUrl(
            $this->path,
            now()->addMinutes($minutes ?? config('letters.signed_url_minutes'))
        );
    }

    public function thumbUrl(?int $minutes = null): string
    {
        return Storage::disk($this->disk)->temporaryUrl(
            $this->thumb_path,
            now()->addMinutes($minutes ?? config('letters.signed_url_minutes'))
        );
    }

    public function thumbBytes(): ?string
    {
        return Storage::disk($this->disk)->get($this->thumb_path);
    }

    public function deleteFiles(): void
    {
        Storage::disk($this->disk)->delete(array_filter([$this->path, $this->thumb_path]));
    }
}
