<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

/**
 * Integration Tests for Profile Feature
 * 
 * Test Level: Integration
 * Test Type: Functional
 * Technique: Use Case Testing, Equivalence Partitioning
 * 
 * Related Test Cases: TC-PRF-001 to TC-PRF-004
 */
class ProfileTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        Role::findOrCreate('user', 'web');
        
        $this->user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => Hash::make('password123'),
        ]);
        $this->user->assignRole('user');
    }

    /**
     * TC-PRF-001: View Profile
     * Technique: Use Case Testing
     * 
     * @test
     */
    public function test_user_can_view_profile(): void
    {
        // Arrange
        $this->actingAs($this->user);

        // Act
        $response = $this->get('/profile');

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Test User');
        $response->assertSee('test@test.com');
    }

    /**
     * TC-PRF-002: Update Profile Name
     * Technique: Use Case Testing
     * 
     * @test
     */
    public function test_user_can_update_profile_name(): void
    {
        // Arrange
        $this->actingAs($this->user);

        // Act
        $response = $this->put('/profile', [
            'name' => 'Updated Name',
            'email' => 'test@test.com',
        ]);

        // Assert
        $response->assertRedirect(route('profile.index'));
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'Updated Name',
        ]);
    }

    /**
     * TC-PRF-002b: Update Profile Email
     * Technique: Use Case Testing
     * 
     * @test
     */
    public function test_user_can_update_profile_email(): void
    {
        // Arrange
        $this->actingAs($this->user);

        // Act
        $response = $this->put('/profile', [
            'name' => 'Test User',
            'email' => 'newemail@test.com',
        ]);

        // Assert
        $response->assertRedirect(route('profile.index'));
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'email' => 'newemail@test.com',
        ]);
    }

    /**
     * TC-PRF-003: Update Password - Valid
     * Technique: Use Case Testing
     * 
     * @test
     */
    public function test_user_can_update_password(): void
    {
        // Arrange
        $this->actingAs($this->user);

        // Act
        $response = $this->put('/profile/password', [
            'current_password' => 'password123',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        // Assert
        $response->assertRedirect(route('profile.index'));
        
        // Verify new password works
        $this->user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $this->user->password));
    }

    /**
     * TC-PRF-004: Update Password - Wrong Current Password
     * Technique: Equivalence Partitioning (Invalid input)
     * 
     * @test
     */
    public function test_cannot_update_password_with_wrong_current(): void
    {
        // Arrange
        $this->actingAs($this->user);

        // Act
        $response = $this->put('/profile/password', [
            'current_password' => 'wrongpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        // Assert
        $response->assertSessionHasErrors('current_password');
        
        // Verify old password still works
        $this->user->refresh();
        $this->assertTrue(Hash::check('password123', $this->user->password));
    }

    /**
     * TC-PRF-PASSWORD-MISMATCH: Password confirmation mismatch
     * Technique: Equivalence Partitioning
     * 
     * @test
     */
    public function test_cannot_update_password_with_mismatch(): void
    {
        // Arrange
        $this->actingAs($this->user);

        // Act
        $response = $this->put('/profile/password', [
            'current_password' => 'password123',
            'password' => 'newpassword123',
            'password_confirmation' => 'differentpassword',
        ]);

        // Assert
        $response->assertSessionHasErrors('password');
    }

    /**
     * TC-PRF-EMAIL-UNIQUE: Cannot update to existing email
     * Technique: Equivalence Partitioning (Unique constraint)
     * 
     * @test
     */
    public function test_cannot_update_to_existing_email(): void
    {
        // Arrange
        $this->actingAs($this->user);
        User::factory()->create(['email' => 'existing@test.com']);

        // Act
        $response = $this->put('/profile', [
            'name' => 'Test User',
            'email' => 'existing@test.com',
        ]);

        // Assert
        $response->assertSessionHasErrors('email');
    }

    /**
     * TC-PRF-AUTH: Guest cannot access profile
     * Technique: Decision Table (Authentication)
     * 
     * @test
     */
    public function test_guest_cannot_access_profile(): void
    {
        // Act - No authentication
        $response = $this->get('/profile');

        // Assert
        $response->assertRedirect(route('login'));
    }

    /**
     * TC-PRF-NAME-REQUIRED: Name is required
     * Technique: Equivalence Partitioning
     * 
     * @test
     */
    public function test_profile_update_requires_name(): void
    {
        // Arrange
        $this->actingAs($this->user);

        // Act
        $response = $this->put('/profile', [
            'name' => '',
            'email' => 'test@test.com',
        ]);

        // Assert
        $response->assertSessionHasErrors('name');
    }

    /**
     * TC-PRF-EMAIL-REQUIRED: Email is required
     * Technique: Equivalence Partitioning
     * 
     * @test
     */
    public function test_profile_update_requires_email(): void
    {
        // Arrange
        $this->actingAs($this->user);

        // Act
        $response = $this->put('/profile', [
            'name' => 'Test User',
            'email' => '',
        ]);

        // Assert
        $response->assertSessionHasErrors('email');
    }
}
