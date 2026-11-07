<?php

namespace Tests\Feature;

use App\Models\Letter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_sent_to_the_admin_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_parents_cannot_open_the_admin_panel(): void
    {
        $this->actingAs(User::factory()->create())->get('/admin')->assertForbidden();
        $this->actingAs(User::factory()->create())->get('/admin-tools/email-preview/elf')->assertForbidden();
    }

    public function test_admins_can_open_dashboard_letters_payments_and_previews(): void
    {
        $admin = User::factory()->admin()->create();
        Letter::factory()->paid()->count(2)->create();

        $this->actingAs($admin)->get('/admin')->assertOk();
        $this->actingAs($admin)->get('/admin/letters')->assertOk()->assertSee('Letters');
        $this->actingAs($admin)->get('/admin/letters/'.Letter::first()->id)->assertOk();
        $this->actingAs($admin)->get('/admin/payments')->assertOk();
        $this->actingAs($admin)->get('/admin/users')->assertOk();
        $this->actingAs($admin)->get('/admin/email-previews')->assertOk();
        $this->actingAs($admin)->get('/admin-tools/email-preview/elf')->assertOk()->assertSee('Elf Jingles');
        $this->actingAs($admin)->get('/admin-tools/email-preview/santa-4')->assertOk()->assertSee('Emma');
    }
}
