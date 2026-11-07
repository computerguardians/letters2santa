<?php

namespace App\Services;

use Aws\Rekognition\RekognitionClient;
use Illuminate\Support\Facades\Log;

/**
 * Amazon Rekognition image moderation. Disabled by default (REKOGNITION_ENABLED=false): when off,
 * images are left unreviewed and the letter waits in the manual moderation queue.
 */
class RekognitionService
{
    private ?RekognitionClient $client = null;

    public function enabled(): bool
    {
        return (bool) config('letters.moderation.rekognition_enabled')
            && filled(config('filesystems.disks.s3.key'));
    }

    /**
     * @return array{labels: array, has_face: bool, flagged: bool, error?: string}
     */
    public function analyze(string $imageBytes): array
    {
        $result = ['labels' => [], 'has_face' => false, 'flagged' => false];

        try {
            $moderation = $this->client()->detectModerationLabels([
                'Image' => ['Bytes' => $imageBytes],
                'MinConfidence' => (float) config('letters.moderation.min_confidence'),
            ]);

            $blocked = config('letters.moderation.blocked_parent_labels', []);
            foreach ($moderation['ModerationLabels'] ?? [] as $label) {
                $result['labels'][] = [
                    'name' => $label['Name'],
                    'parent' => $label['ParentName'] ?? '',
                    'confidence' => round($label['Confidence'], 1),
                ];
                if (in_array($label['Name'], $blocked, true) || in_array($label['ParentName'] ?? '', $blocked, true)) {
                    $result['flagged'] = true;
                }
            }

            $faces = $this->client()->detectFaces([
                'Image' => ['Bytes' => $imageBytes],
                'Attributes' => ['DEFAULT'],
            ]);
            $result['has_face'] = count($faces['FaceDetails'] ?? []) > 0;
        } catch (\Throwable $e) {
            Log::warning('Rekognition failed: '.$e->getMessage());
            $result['error'] = $e->getMessage();
            $result['flagged'] = true; // fail closed: a human looks at it
        }

        return $result;
    }

    private function client(): RekognitionClient
    {
        return $this->client ??= new RekognitionClient([
            'version' => 'latest',
            'region' => config('filesystems.disks.s3.region') ?: config('services.ses.region'),
            'credentials' => [
                'key' => config('filesystems.disks.s3.key'),
                'secret' => config('filesystems.disks.s3.secret'),
            ],
        ]);
    }
}
