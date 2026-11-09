<?php

namespace Tests\Feature\Party;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPartyResourcesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_browse_events_and_hosts(): void
    {
        $admin = User::factory()->admin()->create();
        $event = Event::factory()->withCredits(40)->create(['name' => 'Council Carols']);

        $this->actingAs($admin)->get('/admin/events')->assertOk()->assertSee('Council Carols');
        $this->actingAs($admin)->get("/admin/events/{$event->id}")->assertOk()->assertSee($event->publicUrl());
        $this->actingAs($admin)->get('/admin/businesses')->assertOk()->assertSee($event->business->name);
        $this->actingAs($admin)->get("/admin/businesses/{$event->business_id}")->assertOk()->assertSee($event->business->contact_name);
    }

    public function test_hosts_cannot_open_the_admin_panel(): void
    {
        $event = Event::factory()->create();
        $this->actingAs($event->business->user)->get('/admin/events')->assertForbidden();
    }
}
