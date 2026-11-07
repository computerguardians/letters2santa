<?php

namespace Tests\Feature\Letters;

use App\Enums\ModerationStatus;
use App\Jobs\ModerateLetter;
use App\Models\Letter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModerationJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_clean_text_letter_is_auto_approved(): void
    {
        $letter = Letter::factory()->paid()->create(['body' => 'Dear Santa, please bring snow.']);
        (new ModerateLetter($letter))->handle(app(\App\Services\TextModerationService::class), app(\App\Services\RekognitionService::class));

        $this->assertEquals(ModerationStatus::Approved, $letter->fresh()->moderation_status);
    }

    public function test_flagged_text_stays_pending_with_flags_recorded(): void
    {
        $letter = Letter::factory()->paid()->create(['body' => 'Dear Santa, call me on 0412 345 678.']);
        (new ModerateLetter($letter))->handle(app(\App\Services\TextModerationService::class), app(\App\Services\RekognitionService::class));

        $letter->refresh();
        $this->assertEquals(ModerationStatus::Pending, $letter->moderation_status);
        $this->assertContains('phone', $letter->moderation_flags['auto']);
    }

    public function test_images_without_rekognition_wait_for_a_human(): void
    {
        $letter = Letter::factory()->paid()->create(['body' => null]);
        $letter->images()->create(['disk' => 'local', 'path' => 'x.jpg', 'thumb_path' => 'x_t.jpg']);

        (new ModerateLetter($letter))->handle(app(\App\Services\TextModerationService::class), app(\App\Services\RekognitionService::class));

        $letter->refresh();
        $this->assertEquals(ModerationStatus::Pending, $letter->moderation_status);
        $this->assertContains('image_unreviewed', $letter->moderation_flags['auto']);
    }

    public function test_human_decision_is_not_overruled(): void
    {
        $letter = Letter::factory()->paid()->create(['moderation_flags' => ['reviewed_by' => 'admin@example.com']]);
        (new ModerateLetter($letter))->handle(app(\App\Services\TextModerationService::class), app(\App\Services\RekognitionService::class));

        $this->assertEquals(ModerationStatus::Pending, $letter->fresh()->moderation_status);
    }
}
