<?php

namespace Tests\Feature\Letters;

use App\Models\Letter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WallVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_settled_approved_consented_letters_appear_on_the_wall(): void
    {
        $visible = Letter::factory()->paid()->approved()->wallConsent()->create();
        $visibleCredited = Letter::factory()->credited()->approved()->wallConsent()->create();
        Letter::factory()->approved()->wallConsent()->create();                 // unpaid
        Letter::factory()->paid()->wallConsent()->create();                     // pending moderation
        Letter::factory()->paid()->rejected()->wallConsent()->create();         // rejected
        Letter::factory()->paid()->approved()->create();                        // no consent
        Letter::factory()->paid()->approved()->wallConsent()->create(['consent_withdrawn_at' => now()]);
        Letter::factory()->sample()->create();                                  // sample, never counted

        $ids = Letter::publicOnWall()->pluck('id')->sort()->values()->all();
        $this->assertEquals(collect([$visible->id, $visibleCredited->id])->sort()->values()->all(), $ids);
    }

    public function test_all_settled_letters_count_toward_the_total_but_samples_never_do(): void
    {
        Letter::factory()->paid()->count(3)->create();            // no consent, pending moderation: still count
        Letter::factory()->credited()->rejected()->create();      // rejected: still counts
        Letter::factory()->count(2)->create();                    // unpaid: no
        Letter::factory()->sample()->count(5)->create();          // samples: no

        $this->assertEquals(4, Letter::settled()->count());
    }
}
