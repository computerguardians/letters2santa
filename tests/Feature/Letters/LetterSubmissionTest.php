<?php

namespace Tests\Feature\Letters;

use App\Enums\PaymentStatus;
use App\Models\Letter;
use App\Models\Payment;
use App\Models\User;
use App\Services\StripeGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LetterSubmissionTest extends TestCase
{
    use RefreshDatabase;

    private function validData(array $overrides = []): array
    {
        return array_merge([
            'child_first_name' => 'Emma',
            'child_age' => 7,
            'town' => 'Tarneit',
            'country_code' => 'AU',
            'body' => 'Dear Santa, I would love a telescope.',
            'guardian_confirmed' => '1',
        ], $overrides);
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/letters/new')->assertRedirect('/login');
    }

    public function test_unverified_users_must_verify_first(): void
    {
        $this->actingAs(User::factory()->unverified()->create())->get('/letters/new')->assertRedirect('/verify-email');
    }

    public function test_parent_can_create_a_text_letter(): void
    {
        $user = User::factory()->timezone('Australia/Perth')->create();

        $this->actingAs($user)->post('/letters', $this->validData(['wall_consent' => '1']))
            ->assertRedirect('/checkout');

        $letter = Letter::first();
        $this->assertEquals('Emma', $letter->child_first_name);
        $this->assertEquals('Australia/Perth', $letter->timezone);
        $this->assertEquals($user->email, $letter->parent_email);
        $this->assertEquals(PaymentStatus::Pending, $letter->payment_status);
        $this->assertTrue($letter->wall_consent);
        $this->assertFalse($letter->instagram_consent);
        $this->assertNotNull($letter->consent_at);
        $this->assertStringStartsWith('L2S-', $letter->reference);
    }

    public function test_consent_defaults_to_off(): void
    {
        $this->actingAs(User::factory()->create())->post('/letters', $this->validData());
        $letter = Letter::first();
        $this->assertFalse($letter->wall_consent);
        $this->assertFalse($letter->instagram_consent);
    }

    public function test_letter_needs_text_or_an_image(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/letters', $this->validData(['body' => '']))
            ->assertSessionHasErrors('body');
        $this->assertEquals(0, Letter::count());
    }

    public function test_validation_rules(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post('/letters', $this->validData(['child_first_name' => str_repeat('a', 31)]))->assertSessionHasErrors('child_first_name');
        $this->actingAs($user)->post('/letters', $this->validData(['child_first_name' => 'Emma123']))->assertSessionHasErrors('child_first_name');
        $this->actingAs($user)->post('/letters', $this->validData(['child_age' => 15]))->assertSessionHasErrors('child_age');
        $this->actingAs($user)->post('/letters', $this->validData(['country_code' => 'XX']))->assertSessionHasErrors('country_code');
        $this->actingAs($user)->post('/letters', $this->validData(['body' => str_repeat('x', 2001)]))->assertSessionHasErrors('body');
        $this->actingAs($user)->post('/letters', $this->validData(['guardian_confirmed' => null]))->assertSessionHasErrors('guardian_confirmed');
        $this->assertEquals(0, Letter::count());
    }

    public function test_image_upload_is_resized_stored_privately_and_stripped_of_exif(): void
    {
        Storage::fake('local');
        $user = User::factory()->create();

        // Build a 2000x1500 JPEG with EXIF metadata via Imagick.
        $im = new \Imagick();
        $im->newImage(2000, 1500, new \ImagickPixel('red'));
        $im->setImageFormat('jpeg');
        $im->setImageProperty('exif:Make', 'TestCamera');
        $im->setImageProperty('comment', 'secret location');
        $tmp = tempnam(sys_get_temp_dir(), 'l2s').'.jpg';
        $im->writeImage($tmp);
        $file = new UploadedFile($tmp, 'drawing.jpg', 'image/jpeg', null, true);

        $this->actingAs($user)->post('/letters', $this->validData(['body' => '', 'images' => [$file]]))
            ->assertRedirect('/checkout');

        $letter = Letter::with('images')->first();
        $this->assertCount(1, $letter->images);
        $image = $letter->images->first();
        Storage::disk('local')->assertExists($image->path);
        Storage::disk('local')->assertExists($image->thumb_path);

        $stored = new \Imagick();
        $stored->readImageBlob(Storage::disk('local')->get($image->path));
        $this->assertLessThanOrEqual(1080, $stored->getImageWidth());
        $this->assertEmpty($stored->getImageProperty('exif:Make') ?: '');
        $this->assertEmpty($stored->getImageProperty('comment') ?: '');

        $thumb = new \Imagick();
        $thumb->readImageBlob(Storage::disk('local')->get($image->thumb_path));
        $this->assertLessThanOrEqual(320, $thumb->getImageWidth());
    }

    public function test_more_than_two_images_are_rejected(): void
    {
        Storage::fake('local');
        $files = [UploadedFile::fake()->image('a.jpg'), UploadedFile::fake()->image('b.jpg'), UploadedFile::fake()->image('c.jpg')];
        $this->actingAs(User::factory()->create())
            ->post('/letters', $this->validData(['images' => $files]))
            ->assertSessionHasErrors('images');
    }

    public function test_checkout_creates_one_payment_for_several_letters(): void
    {
        $user = User::factory()->create();
        Letter::factory()->count(3)->for($user)->create();

        $this->mock(StripeGateway::class, function ($mock) {
            $session = new \Stripe\Checkout\Session('cs_test_abc');
            $session->url = 'https://checkout.stripe.com/pay/cs_test_abc';
            $mock->shouldReceive('createLetterCheckout')->once()->andReturn($session);
        });

        $this->actingAs($user)->post('/checkout')->assertRedirect('https://checkout.stripe.com/pay/cs_test_abc');

        $payment = Payment::first();
        $this->assertEquals(3, $payment->letter_count);
        $this->assertEquals(300, $payment->amount_cents);
        $this->assertEquals('cs_test_abc', $payment->stripe_session_id);
        $this->assertEquals(3, Letter::where('payment_id', $payment->id)->count());
    }

    public function test_parent_can_withdraw_consent_and_delete_letter(): void
    {
        Storage::fake('local');
        $user = User::factory()->create();
        $letter = Letter::factory()->for($user)->paid()->approved()->wallConsent()->create();
        $this->assertTrue($letter->isPubliclyVisible());

        $this->actingAs($user)->post("/letters/{$letter->id}/withdraw-consent")->assertRedirect();
        $this->assertFalse($letter->fresh()->isPubliclyVisible());

        $this->actingAs($user)->delete("/letters/{$letter->id}")->assertRedirect('/letters');
        $this->assertSoftDeleted('letters', ['id' => $letter->id]);
    }

    public function test_parent_cannot_touch_another_parents_letter(): void
    {
        $letter = Letter::factory()->create();
        $this->actingAs(User::factory()->create())->delete("/letters/{$letter->id}")->assertForbidden();
    }
}
