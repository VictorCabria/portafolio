<?php

namespace Tests\Feature;

use App\Models\Experience;
use App\Models\Profile;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SyncContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_sync_content_from_seeder(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)->post('/admin/sincronizar')
            ->assertRedirect(route('admin.dashboard'));

        $this->assertSame(4, Experience::count());
        $this->assertTrue(Project::where('title', 'TODOS – App de telemedicina')->exists());
        $this->assertStringEndsWith('/images/perfil.jpg', Profile::current()->avatarUrl());
        $this->assertModelExists($admin);
    }

    public function test_guests_cannot_sync_content(): void
    {
        $this->post('/admin/sincronizar')->assertRedirect('/login');

        $this->assertSame(0, Project::count());
    }
}
