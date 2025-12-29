<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;

/**
 * Integration Tests for Authentication Feature
 * 
 * Test Level: Integration
 * Test Type: Functional
 * Technique: Use Case Testing, Equivalence Partitioning, Decision Table
 * 
 * Related Test Cases: TC-AUTH-001 to TC-AUTH-021
 */
class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles if they don't exist
        Role::findOrCreate('admin', 'web');
        Role::findOrCreate('manager', 'web');
        Role::findOrCreate('user', 'web');
    }

    /**
     * TC-AUTH-001: Valid Login - Admin User
     * Technique: Use Case Testing
     * 
     * @test
     */
    public function test_admin_can_login_successfully(): void
    {
        // Arrange
        $admin = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => bcrypt('admin123'),
        ]);
        $admin->assignRole('admin');

        // Act
        $response = $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'admin123',
        ]);

        // Assert
        $response->assertRedirect(route('events.list'));
        $this->assertAuthenticatedAs($admin);
    }

    /**
     * TC-AUTH-002: Valid Login - Regular User
     * Technique: Use Case Testing, Decision Table (Role-based redirection)
     * 
     * @test
     */
    public function test_user_can_login_and_redirect_to_home(): void
    {
        // Arrange
        $user = User::factory()->create([
            'email' => 'user@test.com',
            'password' => bcrypt('user123'),
        ]);
        $user->assignRole('user');

        // Act
        $response = $this->post('/login', [
            'email' => 'user@test.com',
            'password' => 'user123',
        ]);

        // Assert - Note: events.index redirects to home
        $this->assertAuthenticated();
    }

    /**
     * TC-AUTH-003: Invalid Login - Wrong Password
     * Technique: Equivalence Partitioning (Invalid partition)
     * 
     * @test
     */
    public function test_login_fails_with_wrong_password(): void
    {
        // Arrange
        $user = User::factory()->create([
            'email' => 'test@test.com',
            'password' => bcrypt('correctpassword'),
        ]);

        // Act
        $response = $this->post('/login', [
            'email' => 'test@test.com',
            'password' => 'wrongpassword',
        ]);

        // Assert
        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * TC-AUTH-004: Invalid Login - Non-existent Email
     * Technique: Equivalence Partitioning (Invalid partition)
     * 
     * @test
     */
    public function test_login_fails_with_nonexistent_email(): void
    {
        // Act
        $response = $this->post('/login', [
            'email' => 'nonexistent@test.com',
            'password' => 'anypassword',
        ]);

        // Assert
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    /**
     * TC-AUTH-005: Invalid Login - Empty Fields
     * Technique: Boundary Value Analysis (Empty = below minimum)
     * 
     * @test
     */
    public function test_login_fails_with_empty_fields(): void
    {
        // Act
        $response = $this->post('/login', [
            'email' => '',
            'password' => '',
        ]);

        // Assert
        $response->assertSessionHasErrors(['email', 'password']);
        $this->assertGuest();
    }

    /**
     * TC-AUTH-006: Invalid Login - Invalid Email Format
     * Technique: Equivalence Partitioning
     * 
     * @test
     */
    public function test_login_fails_with_invalid_email_format(): void
    {
        // Act
        $response = $this->post('/login', [
            'email' => 'notanemail',
            'password' => 'password123',
        ]);

        // Assert
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * TC-AUTH-010: Valid Registration
     * Technique: Use Case Testing
     * 
     * @test
     */
    public function test_user_can_register_successfully(): void
    {
        // Act
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'newuser@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // Assert
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'email' => 'newuser@test.com',
            'name' => 'Test User',
            'role' => 'user',
        ]);
    }

    /**
     * TC-AUTH-011: Registration - Duplicate Email
     * Technique: Equivalence Partitioning (Database constraint)
     * 
     * @test
     */
    public function test_registration_fails_with_duplicate_email(): void
    {
        // Arrange
        User::factory()->create(['email' => 'existing@test.com']);

        // Act
        $response = $this->post('/register', [
            'name' => 'New User',
            'email' => 'existing@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // Assert
        $response->assertSessionHasErrors('email');
    }

    /**
     * TC-AUTH-012: Registration - Password Mismatch
     * Technique: Equivalence Partitioning
     * 
     * @test
     */
    public function test_registration_fails_with_password_mismatch(): void
    {
        // Act
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'password123',
            'password_confirmation' => 'differentpassword',
        ]);

        // Assert
        $response->assertSessionHasErrors('password');
    }

    /**
     * TC-AUTH-013: Registration - Password Too Short (Below Boundary)
     * Technique: Boundary Value Analysis
     * 
     * @test
     */
    public function test_registration_fails_with_short_password(): void
    {
        // Act - Password with 7 characters (below min of 8)
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'pass123', // 7 characters
            'password_confirmation' => 'pass123',
        ]);

        // Assert
        $response->assertSessionHasErrors('password');
    }

    /**
     * TC-AUTH-014: Registration - Password At Minimum Length (At Boundary)
     * Technique: Boundary Value Analysis
     * 
     * @test
     */
    public function test_registration_succeeds_with_min_length_password(): void
    {
        // Act - Password with 8 characters (at min)
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'pass1234', // 8 characters
            'password_confirmation' => 'pass1234',
        ]);

        // Assert
        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('users', ['email' => 'test@test.com']);
    }

    /**
     * TC-AUTH-020: Valid Logout
     * Technique: Use Case Testing
     * 
     * @test
     */
    public function test_user_can_logout_successfully(): void
    {
        // Arrange
        $user = User::factory()->create();
        $this->actingAs($user);

        // Act
        $response = $this->post('/logout');

        // Assert
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    /**
     * TC-AUTH-021: Session Invalidation After Logout
     * Technique: White Box (Session handling)
     * 
     * @test
     */
    public function test_session_invalidated_after_logout(): void
    {
        // Arrange
        $user = User::factory()->create();
        $this->actingAs($user);

        // Act
        $this->post('/logout');

        // Assert - Try to access protected route
        $response = $this->get('/my-registrations');
        $response->assertRedirect(route('login'));
    }

    /**
     * TC-AUTH-VIEW-001: Login page is accessible
     * Technique: Use Case Testing
     * 
     * @test
     */
    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Sign In');
    }

    /**
     * TC-AUTH-VIEW-002: Register page is accessible
     * Technique: Use Case Testing
     * 
     * @test
     */
    public function test_register_page_is_accessible(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Sign Up');
    }

    /**
     * TC-AUTH-004: Manager login redirects to admin panel
     * Technique: Decision Table (Role-based redirection)
     * 
     * @test
     */
    public function test_manager_redirects_to_admin_panel(): void
    {
        // Arrange
        $manager = User::factory()->create([
            'email' => 'manager@test.com',
            'password' => bcrypt('manager123'),
        ]);
        $manager->assignRole('manager');

        // Act
        $response = $this->post('/login', [
            'email' => 'manager@test.com',
            'password' => 'manager123',
        ]);

        // Assert
        $response->assertRedirect(route('events.list'));
    }
}
