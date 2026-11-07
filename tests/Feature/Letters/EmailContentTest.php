<?php

namespace Tests\Feature\Letters;

use App\Jobs\SendElfEmail;
use App\Jobs\SendSantaEmail;
use App\Mail\ElfArrivalMail;
use App\Mail\SantaLetterMail;
use App\Models\Letter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_elf_email_is_personalised_and_marks_letter(): void
    {
        Mail::fake();
        $letter = Letter::factory()->paid()->create(['child_first_name' => 'Noah', 'town' => 'Geelong', 'parent_name' => 'Sam']);

        (new SendElfEmail($letter))->handle();

        Mail::assertSent(ElfArrivalMail::class, function (ElfArrivalMail $mail) use ($letter) {
            $html = $mail->render();

            return $mail->hasTo($letter->parent_email)
                && str_contains($html, 'Noah')
                && str_contains($html, 'Geelong')
                && str_contains($html, 'Hi Sam');
        });
        $this->assertNotNull($letter->fresh()->elf_sent_at);
    }

    public function test_santa_email_uses_a_variant_and_attaches_pdf(): void
    {
        Mail::fake();
        $letter = Letter::factory()->paid()->create(['child_first_name' => 'Ava', 'town' => 'Hobart', 'child_age' => 6, 'santa_scheduled_for' => now()->subMinute()]);

        (new SendSantaEmail($letter))->handle();

        $letter->refresh();
        $this->assertNotNull($letter->santa_sent_at);
        $this->assertGreaterThanOrEqual(1, $letter->santa_variant);
        $this->assertLessThanOrEqual(config('letters.santa_variants'), $letter->santa_variant);

        Mail::assertSent(SantaLetterMail::class, function (SantaLetterMail $mail) {
            $html = $mail->render();
            $attachments = $mail->attachments();

            return str_contains($html, 'Ava') && str_contains($html, 'Hobart') && str_contains($html, 'Being 6')
                && count($attachments) === 1;
        });
    }

    public function test_every_santa_variant_renders(): void
    {
        for ($v = 1; $v <= (int) config('letters.santa_variants'); $v++) {
            $letter = \App\Support\SampleLetter::make(['santa_variant' => $v]);
            $html = (new SantaLetterMail($letter))->render();
            $this->assertStringContainsString('Emma', $html, "variant $v");
            $this->assertStringContainsString('Tarneit', $html, "variant $v");
        }
    }

    public function test_santa_email_is_not_sent_twice(): void
    {
        Mail::fake();
        $letter = Letter::factory()->paid()->create(['santa_sent_at' => now()]);
        (new SendSantaEmail($letter))->handle();
        Mail::assertNothingSent();
    }

    public function test_rejected_letters_still_get_emails(): void
    {
        Mail::fake();
        $letter = Letter::factory()->paid()->rejected()->create();
        (new SendElfEmail($letter))->handle();
        (new SendSantaEmail($letter))->handle();
        Mail::assertSent(ElfArrivalMail::class);
        Mail::assertSent(SantaLetterMail::class);
    }
}
