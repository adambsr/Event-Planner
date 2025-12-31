<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\AAB_Event;
use App\Models\AAB_Category;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

/**
 * Integration Tests for Event Feature
 * 
 * Test Level: Integration
 * Test Type: Functional
 * Technique: Use Case Testing, Equivalence Partitioning, State Transition
 * 
 * Related Test Cases: TC-EVT-001 to TC-EVT-045
 */
class EventTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $user;
    protected AAB_Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create permissions
        Permission::findOrCreate('view events', 'web');
        Permission::findOrCreate('edit events', 'web');
        Permission::findOrCreate('delete events', 'web');
        
        // Create roles
        $adminRole = Role::findOrCreate('admin', 'web');
        $adminRole->givePermissionTo(['view events', 'edit events', 'delete events']);
        
        Role::findOrCreate('user', 'web');
        
        // Create users
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
        
        $this->user = User::factory()->create();
        $this->user->assignRole('user');
        
        // Create category
        $this->category = AAB_Category::factory()->create(['name' => 'Technology']);
    }

    /**
     * TC-EVT-001: View Public Events - Only Active Events Displayed
     * Technique: State Transition (status filtering)
     * 
     * @test
     */
    public function test_public_events_shows_only_active_events(): void
    {
        // Arrange
        $activeEvent = AAB_Event::factory()->create([
            'title' => 'Active Event',
            'status' => 'active',
            'category_id' => $this->category->id,
            'created_by' => $this->admin->id,
        ]);
        
        $archivedEvent = AAB_Event::factory()->create([
            'title' => 'Archived Event',
            'status' => 'archived',
            'category_id' => $this->category->id,
            'created_by' => $this->admin->id,
        ]);

        // Act
        $response = $this->get('/home');

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Active Event');
        $response->assertDontSee('Archived Event');
    }

    /**
     * TC-EVT-002: Archived Events Hidden from Public View
     * Technique: State Transition
     * 
     * @test
     */
    public function test_archived_events_not_visible_publicly(): void
    {
        // Arrange
        AAB_Event::factory()->create([
            'title' => 'Hidden Event',
            'status' => 'archived',
            'category_id' => $this->category->id,
            'created_by' => $this->admin->id,
        ]);

        // Act
        $response = $this->get('/home');

        // Assert
        $response->assertDontSee('Hidden Event');
    }

    /**
     * TC-EVT-010: Search by Title
     * Technique: Equivalence Partitioning
     * 
     * @test
     */
    public function test_search_events_by_title(): void
    {
        // Arrange
        AAB_Event::factory()->create([
            'title' => 'Laravel Workshop',
            'status' => 'active',
            'category_id' => $this->category->id,
            'created_by' => $this->admin->id,
        ]);
        
        AAB_Event::factory()->create([
            'title' => 'Python Conference',
            'status' => 'active',
            'category_id' => $this->category->id,
            'created_by' => $this->admin->id,
        ]);

        // Act
        $response = $this->get('/home?search=Laravel');

        // Assert
        $response->assertSee('Laravel Workshop');
        $response->assertDontSee('Python Conference');
    }

    /**
     * TC-EVT-012: Search No Results
     * Technique: Equivalence Partitioning (Empty result set)
     * 
     * @test
     */
    public function test_search_returns_no_results(): void
    {
        // Arrange
        AAB_Event::factory()->create([
            'title' => 'Tech Event',
            'status' => 'active',
            'category_id' => $this->category->id,
            'created_by' => $this->admin->id,
        ]);

        // Act
        $response = $this->get('/home?search=xyznonexistent123');

        // Assert
        $response->assertStatus(200);
        $response->assertDontSee('Tech Event');
    }

    /**
     * TC-EVT-020: Filter by Category
     * Technique: Equivalence Partitioning
     * 
     * @test
     */
    public function test_filter_events_by_category(): void
    {
        // Arrange
        $sportsCategory = AAB_Category::factory()->create(['name' => 'Sports']);
        
        AAB_Event::factory()->create([
            'title' => 'Tech Talk',
            'status' => 'active',
            'category_id' => $this->category->id,
            'created_by' => $this->admin->id,
        ]);
        
        AAB_Event::factory()->create([
            'title' => 'Football Match',
            'status' => 'active',
            'category_id' => $sportsCategory->id,
            'created_by' => $this->admin->id,
        ]);

        // Act
        $response = $this->get('/home?category_id=' . $sportsCategory->id);

        // Assert
        $response->assertSee('Football Match');
        $response->assertDontSee('Tech Talk');
    }

    /**
     * TC-EVT-030: View Event Details
     * Technique: Use Case Testing
     * 
     * @test
     */
    public function test_view_event_details(): void
    {
        // Arrange
        $event = AAB_Event::factory()->create([
            'title' => 'Detailed Event',
            'description' => 'This is a detailed description',
            'place' => 'Conference Center',
            'price' => 50.00,
            'status' => 'active',
            'category_id' => $this->category->id,
            'created_by' => $this->admin->id,
        ]);

        // Act
        $response = $this->get('/events/' . $event->id);

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Detailed Event');
        $response->assertSee('This is a detailed description');
        $response->assertSee('Conference Center');
    }

    /**
     * TC-EVT-031: View Non-existent Event
     * Technique: Equivalence Partitioning (Invalid ID)
     * 
     * @test
     */
    public function test_view_nonexistent_event_returns_404(): void
    {
        // Act
        $response = $this->get('/events/99999');

        // Assert
        $response->assertStatus(404);
    }

    /**
     * TC-EVT-040: Create Event - Valid (Admin)
     * Technique: Use Case Testing
     * 
     * @test
     */
    public function test_admin_can_create_event(): void
    {
        // Arrange
        $this->actingAs($this->admin);

        // Act
        $response = $this->post('/admin/events', [
            'title' => 'New Conference',
            'description' => 'A great conference',
            'start_date' => '2026-06-01 09:00:00',
            'end_date' => '2026-06-01 18:00:00',
            'place' => 'Convention Center',
            'price' => 100,
            'category_id' => $this->category->id,
            'capacity' => 200,
        ]);

        // Assert
        $this->assertDatabaseHas('aab_events', [
            'title' => 'New Conference',
            'created_by' => $this->admin->id,
        ]);
    }

    /**
     * TC-EVT-041: Create Event - Missing Title
     * Technique: Equivalence Partitioning (Invalid - required field missing)
     * 
     * @test
     */
    public function test_create_event_fails_without_title(): void
    {
        // Arrange
        $this->actingAs($this->admin);

        // Act
        $response = $this->post('/admin/events', [
            'title' => '',
            'description' => 'A great conference',
            'start_date' => '2026-06-01 09:00:00',
            'end_date' => '2026-06-01 18:00:00',
            'place' => 'Convention Center',
            'price' => 100,
            'category_id' => $this->category->id,
            'capacity' => 200,
        ]);

        // Assert
        $response->assertSessionHasErrors('title');
    }

    /**
     * TC-EVT-044: Archive Event (Soft Delete)
     * Technique: State Transition
     * 
     * @test
     */
    public function test_admin_can_archive_event(): void
    {
        // Arrange
        $this->actingAs($this->admin);
        
        $event = AAB_Event::factory()->create([
            'status' => 'active',
            'category_id' => $this->category->id,
            'created_by' => $this->admin->id,
        ]);

        // Act
        $response = $this->delete('/admin/events/' . $event->id);

        // Assert
        $response->assertRedirect(route('events.list'));
        $this->assertDatabaseHas('aab_events', [
            'id' => $event->id,
            'status' => 'archived',
        ]);
    }

    /**
     * TC-EVT-045: Non-Admin Cannot Create Event
     * Technique: Decision Table (Authorization)
     * 
     * @test
     */
    public function test_regular_user_cannot_access_create_event(): void
    {
        // Arrange
        $this->actingAs($this->user);

        // Act
        $response = $this->get('/admin/events/create');

        // Assert
        $response->assertStatus(403);
    }

    /**
     * TC-EVT-043: Update Event
     * Technique: Use Case Testing
     * 
     * @test
     */
    public function test_admin_can_update_event(): void
    {
        // Arrange
        $this->actingAs($this->admin);
        
        $event = AAB_Event::factory()->create([
            'title' => 'Original Title',
            'category_id' => $this->category->id,
            'created_by' => $this->admin->id,
        ]);

        // Act
        $response = $this->put('/admin/events/' . $event->id, [
            'title' => 'Updated Title',
            'description' => $event->description,
            'start_date' => $event->start_date->format('Y-m-d H:i:s'),
            'end_date' => $event->end_date->format('Y-m-d H:i:s'),
            'place' => $event->place,
            'price' => $event->price,
            'category_id' => $event->category_id,
            'capacity' => $event->capacity,
        ]);

        // Assert
        $this->assertDatabaseHas('aab_events', [
            'id' => $event->id,
            'title' => 'Updated Title',
        ]);
    }

    /**
     * TC-SEC-001: Access Admin Events Without Auth
     * Technique: Decision Table (Security)
     * 
     * @test
     */
    public function test_unauthenticated_cannot_access_admin_events(): void
    {
        // Act
        $response = $this->get('/admin/events');

        // Assert
        $response->assertRedirect(route('login'));
    }

    /**
     * TC-EVT-FREE: Free event has is_free set correctly
     * Technique: Equivalence Partitioning
     * 
     * @test
     */
    public function test_free_event_created_with_zero_price(): void
    {
        // Arrange
        $this->actingAs($this->admin);

        // Act
        $response = $this->post('/admin/events', [
            'title' => 'Free Workshop',
            'description' => 'A free workshop',
            'start_date' => '2026-06-01 09:00:00',
            'end_date' => '2026-06-01 18:00:00',
            'place' => 'Online',
            'is_free' => true,
            'price' => 0,
            'category_id' => $this->category->id,
            'capacity' => 100,
        ]);

        // Assert
        $this->assertDatabaseHas('aab_events', [
            'title' => 'Free Workshop',
            'is_free' => true,
            'price' => 0,
        ]);
    }

    /**
     * TC-EVT-ARCH-001: Admin can view archived event details
     * Technique: Authorization Testing
     * 
     * @test
     */
    public function test_admin_can_view_archived_event(): void
    {
        // Arrange
        $archivedEvent = AAB_Event::factory()->create([
            'title' => 'Archived Admin Event',
            'status' => 'archived',
            'category_id' => $this->category->id,
            'created_by' => $this->admin->id,
        ]);

        $this->actingAs($this->admin);

        // Act
        $response = $this->get('/events/' . $archivedEvent->id);

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Archived Admin Event');
        $response->assertSee('Archived Event'); // Badge text
    }

    /**
     * TC-EVT-ARCH-002: Admin events list shows status column
     * Technique: Functional Testing
     * 
     * @test
     */
    public function test_admin_events_list_shows_status(): void
    {
        // Arrange
        AAB_Event::factory()->create([
            'title' => 'Active Test Event',
            'status' => 'active',
            'category_id' => $this->category->id,
            'created_by' => $this->admin->id,
        ]);

        AAB_Event::factory()->create([
            'title' => 'Archived Test Event',
            'status' => 'archived',
            'category_id' => $this->category->id,
            'created_by' => $this->admin->id,
        ]);

        $this->actingAs($this->admin);

        // Act
        $response = $this->get('/admin/events');

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Active Test Event');
        $response->assertSee('Archived Test Event');
        $response->assertSee('status-badge active'); // CSS class for active badge
        $response->assertSee('status-badge archived'); // CSS class for archived badge
    }

    /**
     * TC-EVT-ARCH-003: Admin can filter by status
     * Technique: Functional Testing
     * 
     * @test
     */
    public function test_admin_can_filter_events_by_status(): void
    {
        // Arrange
        AAB_Event::factory()->create([
            'title' => 'Active Filter Event',
            'status' => 'active',
            'category_id' => $this->category->id,
            'created_by' => $this->admin->id,
        ]);

        AAB_Event::factory()->create([
            'title' => 'Archived Filter Event',
            'status' => 'archived',
            'category_id' => $this->category->id,
            'created_by' => $this->admin->id,
        ]);

        $this->actingAs($this->admin);

        // Act - Filter by archived only
        $response = $this->get('/admin/events?status=archived');

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Archived Filter Event');
        $response->assertDontSee('Active Filter Event');
    }
}
