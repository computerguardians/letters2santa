<?php

namespace App\Jobs;

use App\Enums\ModerationStatus;
use App\Models\Letter;
use App\Services\RekognitionService;
use App\Services\TextModerationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ModerateLetter implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(public Letter $letter) {}

    public function handle(TextModerationService $text, RekognitionService $rekognition): void
    {
        $letter = $this->letter->fresh(['images']);
        if (! $letter || $letter->moderation_status !== ModerationStatus::Pending || $letter->is_sample) {
            return;
        }
        // Don't overrule a human who already looked at it.
        if (! empty($letter->moderation_flags['reviewed_by'])) {
            return;
        }

        $flags = $text->check($letter->body, $letter->child_first_name);
        $hasFace = false;

        foreach ($letter->images as $image) {
            if ($image->moderated) {
                $hasFace = $hasFace || $image->has_face;
                if ($image->flagged) {
                    $flags[] = 'image';
                }
                continue;
            }

            if (! $rekognition->enabled()) {
                $flags[] = 'image_unreviewed';
                continue;
            }

            $result = $rekognition->analyze((string) \Illuminate\Support\Facades\Storage::disk($image->disk)->get($image->path));
            $image->update([
                'moderation_labels' => $result['labels'],
                'has_face' => $result['has_face'],
                'flagged' => $result['flagged'],
                'moderated' => empty($result['error']),
            ]);
            $hasFace = $hasFace || $result['has_face'];
            if ($result['flagged']) {
                $flags[] = 'image';
            }
        }

        $flags = array_values(array_unique($flags));

        $letter->forceFill([
            'has_face' => $hasFace,
            'moderation_flags' => ['auto' => $flags, 'checked_at' => now()->toIso8601String()],
            // Faces alone don't block the letter: the image is simply never shown publicly.
            'moderation_status' => $flags === [] ? ModerationStatus::Approved : ModerationStatus::Pending,
        ])->save();
    }
}
