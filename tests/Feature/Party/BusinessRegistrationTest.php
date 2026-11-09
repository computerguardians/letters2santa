<?php

namespace Tests\Feature\Party;

use App\Enums\UserRole;
use App\Models\Business;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BusinessRegistrationTest extends TestCase
{
    use RefreshDatabase;

    private function data(array $overrides = []): array
    {
        return array_merge([
            'business_name' => 'Smith & Co',
            'abn' => '12 345 678 901',
            'contact_name' => 'Sam Smith',
            'phone' => '0400 000 000',
            'country_code' => 'AU',
            'email' => 'sam@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'timezone' => 'Australia/Melbourne',
            'terms' => '1',
        ], $overrides);
    }

    public function test_registration_page_loads(): void
    {
        $this->get('/host/register')->assertOk()->assertSee('Set up your party pack');
    }

    public function test_business_can_register_and_is_sent_to_dashboard(): void
    {
        $this->post('/host/register', $this->data())->assertRedirect('/host');

        $user = User::where('email', 'sam@example.com')->first();
        $this->assertEquals(UserRole::Business, $user->role);
        $this->assertEquals('Australia/Melbourne', $user->timezone);
        $this->assertAuthenticatedAs($user);

        $business = $user->business;
        $this->assertEquals('Smith & Co', $business->name);
        $this->assertEquals('12345678901', $business->abn);
        $this->assertEquals('Sam Smith', $business->contact_name);
    }

    public function test_registration_validation(): void
    {
        $this->post('/host/register', $this->data(['business_name' => '']))->assertSessionHasErrors('business_name');
        $this->post('/host/register', $this->data(['abn' => 'abc']))->assertSessionHasErrors('abn');
        $this->post('/host/register', $this->data(['terms' => null]))->assertSessionHasErrors('terms');
        $this->post('/host/register', $this->data(['password_confirmation' => 'different']))->assertSessionHasErrors('password');
        $this->assertEquals(0, Business::count());
    }

    public function test_existing_parent_can_add_a_business_profile(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/host/register', [
            'business_name' => 'Tarneit Primary', 'contact_name' => 'Pat', 'country_code' => 'AU', 'terms' => '1',
        ])->assertRedirect('/host');

        $this->assertEquals(UserRole::Business, $user->fresh()->role);
        $this->assertEquals('Tarneit Primary', $user->fresh()->business->name);
    }

    public function test_host_pages_require_a_business_profile(): void
    {
        $this->actingAs(User::factory()->create())->get('/host')->assertRedirect('/host/register');
    }

    public function test_host_pages_require_login(): void
    {
        $this->get('/host')->assertRedirect('/login');
    }

    public function test_unverified_hosts_must_verify_email_first(): void
    {
        $business = Business::factory()->for(User::factory()->business()->unverified())->create();
        $this->actingAs($business->user)->get('/host')->assertRedirect('/verify-email');
    }
}
