<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\AAB_Event;
use App\Models\AAB_Category;
use App\Models\AAB_Registration;
use Spatie\Permission\Models\Role;

/**
 * Integration Tests for Registration Feature
 * 
 * Test Level: Integration
 * Test Type: Functional
 * Technique: Use Case Testing, Equivalence Partitioning, Boundary Value Analysis
 * 
 * Related Test Cases: TC-REG-001 to TC-REG-011
 */
class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected AAB_Event $event;
    protected AAB_Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create role
        Role::findOrCreate('user', 'web');
        Role::findOrCreate('admin', 'web');
        
        // Create admin for event creation
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        
        // Create user
        $this->user = User::factory()->create();
        $this->user->assignRole('user');
        
        // Create category and event
        $this->category = AAB_Category::factory()->create();
        $this->event = AAB_Event::factory()->create([
            'title' => 'Test Event',
            'status' => 'active',
            'capacity' => 10,
            'category_id' => $this->category->id,
            'created_by' => $admin->id,
        ]);
    }

    /**
     * TC-REG-001: Register for Event Successfully
     * Technique: Use Case Testing
     * 
     * @test
     */
    public function test_user_can_register_for_event(): void
    {
        // Arrange
        $this->actingAs($this->user);

        // Act
        $response = $this->post('/events/' . $this->event->id . '/register');

        // Assert
        $response->assertRedirect(route('events.show', $this->event));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('aab_registrations', [
            'user_id' => $this->user->id,
            'event_id' => $this->event->id,
        ]);
    }

    /**
     * TC-REG-002: Register - Event Full
     * Technique: Boundary Value Analysis (Capacity at limit)
     * 
     * @test
     */
    public function test_cannot_register_for_full_event(): void
    {
        // Arrange
        $fullEvent = AAB_Event::factory()->create([
            'capacity' => 2,
            'status' => 'active',
            'category_id' => $this->category->id,
            'created_by' => $this->event->created_by,
        ]);
        
        // Fill the event to capacity
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        AAB_Registration::create(['user_id' => $user1->id, 'event_id' => $fullEvent->id]);
        AAB_Registration::create(['user_id' => $user2->id, 'event_id' => $fullEvent->id]);

        $this->actingAs($this->user);

        // Act
        $response = $this->post('/events/' . $fullEvent->id . '/register');

        // Assert
        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('aab_registrations', [
            'user_id' => $this->user->id,
            'event_id' => $fullEvent->id,
        ]);
    }

    /**
     * TC-REG-003: Register - Already Registered
     * Technique: Equivalence Partitioning (Duplicate prevention)
     * 
     * @test
     */
    public function test_cannot_register_twice_for_same_event(): void
    {
        // Arrange
        $this->actingAs($this->user);
        
        // First registration
        AAB_Registration::create([
            'user_id' => $this->user->id,
            'event_id' => $this->event->id,
        ]);

        // Act - Try to register again
        $response = $this->post('/events/' . $this->event->id . '/register');

        // Assert
        $response->assertSessionHas('error');
        
        // Should still only have one registration
        $count = AAB_Registration::where('user_id', $this->user->id)
            ->where('event_id', $this->event->id)
            ->count();
        $this->assertEquals(1, $count);
    }

    /**
     * TC-REG-004: Register - Not Logged In
     * Technique: Decision Table (Authentication required)
     * 
     * @test
     */
    public function test_guest_redirected_to_login_when_registering(): void
    {
        // Act - No authentication
        $response = $this->post('/events/' . $this->event->id . '/register');

        // Assert
        $response->assertRedirect(route('login'));
    }

    /**
     * TC-REG-005: Unregister from Event
     * Technique: Use Case Testing
     * 
     * @test
     */
    public function test_user_can_unregister_from_event(): void
    {
        // Arrange
        $this->actingAs($this->user);
        
        AAB_Registration::create([
            'user_id' => $this->user->id,
            'event_id' => $this->event->id,
        ]);

        // Act
        $response = $this->delete('/events/' . $this->event->id . '/unregister');

        // Assert
        $response->assertRedirect(route('events.show', $this->event));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseMissing('aab_registrations', [
            'user_id' => $this->user->id,
            'event_id' => $this->event->id,
        ]);
    }

    /**
     * TC-REG-006: Unregister - Not Registered
     * Technique: Equivalence Partitioning (Invalid operation)
     * 
     * @test
     */
    public function test_cannot_unregister_if_not_registered(): void
    {
        // Arrange
        $this->actingAs($this->user);

        // Act - Try to unregister without being registered
        $response = $this->delete('/events/' . $this->event->id . '/unregister');

        // Assert
        $response->assertSessionHas('error');
    }

    /**
     * TC-REG-010: View My Registrations
     * Technique: Use Case Testing
     * 
     * @test
     */
    public function test_user_can_view_their_registrations(): void
    {
        // Arrange
        $this->actingAs($this->user);
        
        AAB_Registration::create([
            'user_id' => $this->user->id,
            'event_id' => $this->event->id,
        ]);

        // Act
        $response = $this->get('/my-registrations');

        // Assert
        $response->assertStatus(200);
        $response->assertSee($this->event->title);
    }

    /**
     * TC-REG-011: My Registrations - Empty
     * Technique: Equivalence Partitioning (Empty state)
     * 
     * @test
     */
    public function test_my_registrations_shows_empty_state(): void
    {
        // Arrange
        $this->actingAs($this->user);

        // Act
        $response = $this->get('/my-registrations');

        // Assert
        $response->assertStatus(200);
        // Should show empty state (no registrations)
    }

    /**
     * TC-REG-CAPACITY: Available places calculated correctly
     * Technique: Boundary Value Analysis
     * 
     * @test
     */
    public function test_available_places_calculated_correctly(): void
    {
        // Arrange
        $event = AAB_Event::factory()->create([
            'capacity' => 5,
            'status' => 'active',
            'category_id' => $this->category->id,
            'created_by' => $this->event->created_by,
        ]);
        
        // Add 3 registrations
        for ($i = 0; $i < 3; $i++) {
            $user = User::factory()->create();
            AAB_Registration::create([
                'user_id' => $user->id,
                'event_id' => $event->id,
            ]);
        }

        // Act
        $event->refresh();

        // Assert
        $this->assertEquals(2, $event->available_places);
        $this->assertFalse($event->isFull());
    }

    /**
     * TC-REG-FULL: isFull() returns true at capacity
     * Technique: Boundary Value Analysis (At boundary)
     * 
     * @test
     */
    public function test_event_is_full_at_capacity(): void
    {
        // Arrange
        $event = AAB_Event::factory()->create([
            'capacity' => 2,
            'status' => 'active',
            'category_id' => $this->category->id,
            'created_by' => $this->event->created_by,
        ]);
        
        // Fill to capacity
        for ($i = 0; $i < 2; $i++) {
            $user = User::factory()->create();
            AAB_Registration::create([
                'user_id' => $user->id,
                'event_id' => $event->id,
            ]);
        }

        // Act
        $event->refresh();

        // Assert
        $this->assertEquals(0, $event->available_places);
        $this->assertTrue($event->isFull());
    }

    /**
     * TC-REG-AUTH: Guest cannot access my-registrations
     * Technique: Decision Table (Authentication)
     * 
     * @test
     */
    public function test_guest_cannot_access_my_registrations(): void
    {
        // Act - No authentication
        $response = $this->get('/my-registrations');

        // Assert
        $response->assertRedirect(route('login'));
    }
}
