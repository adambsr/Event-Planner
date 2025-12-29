<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\AAB_Category;
use App\Models\AAB_Event;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

/**
 * Integration Tests for Category Feature
 * 
 * Test Level: Integration
 * Test Type: Functional
 * Technique: Use Case Testing, Equivalence Partitioning, Decision Table
 * 
 * Related Test Cases: TC-CAT-001 to TC-CAT-005
 */
class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $manager;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create permissions
        Permission::findOrCreate('view categories', 'web');
        Permission::findOrCreate('create categories', 'web');
        Permission::findOrCreate('edit categories', 'web');
        Permission::findOrCreate('delete categories', 'web');
        
        // Create roles with permissions
        $adminRole = Role::findOrCreate('admin', 'web');
        $adminRole->givePermissionTo(['view categories', 'create categories', 'edit categories', 'delete categories']);
        
        $managerRole = Role::findOrCreate('manager', 'web');
        $managerRole->givePermissionTo(['view categories']);
        
        Role::findOrCreate('user', 'web');
        
        // Create users
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
        
        $this->manager = User::factory()->create();
        $this->manager->assignRole('manager');
        
        $this->user = User::factory()->create();
        $this->user->assignRole('user');
    }

    /**
     * TC-CAT-001: View Categories (Admin/Manager)
     * Technique: Use Case Testing
     * 
     * @test
     */
    public function test_admin_can_view_categories(): void
    {
        // Arrange
        $this->actingAs($this->admin);
        AAB_Category::factory()->create(['name' => 'Technology']);
        AAB_Category::factory()->create(['name' => 'Sports']);

        // Act
        $response = $this->get('/admin/categories');

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Technology');
        $response->assertSee('Sports');
    }

    /**
     * TC-CAT-001b: Manager can view categories
     * Technique: Decision Table (Role-based access)
     * 
     * @test
     */
    public function test_manager_can_view_categories(): void
    {
        // Arrange
        $this->actingAs($this->manager);
        AAB_Category::factory()->create(['name' => 'Arts']);

        // Act
        $response = $this->get('/admin/categories');

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Arts');
    }

    /**
     * TC-CAT-002: Create Category (Admin)
     * Technique: Use Case Testing
     * 
     * @test
     */
    public function test_admin_can_create_category(): void
    {
        // Arrange
        $this->actingAs($this->admin);

        // Act
        $response = $this->post('/admin/categories', [
            'name' => 'New Category',
        ]);

        // Assert
        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas('aab_categories', [
            'name' => 'New Category',
        ]);
    }

    /**
     * TC-CAT-003: Create Category - Duplicate Name
     * Technique: Equivalence Partitioning (Unique constraint)
     * 
     * @test
     */
    public function test_cannot_create_duplicate_category(): void
    {
        // Arrange
        $this->actingAs($this->admin);
        AAB_Category::factory()->create(['name' => 'Existing Category']);

        // Act
        $response = $this->post('/admin/categories', [
            'name' => 'Existing Category',
        ]);

        // Assert
        $response->assertSessionHasErrors('name');
    }

    /**
     * TC-CAT-004: Delete Category - Has Events
     * Technique: Decision Table (Business rule)
     * 
     * @test
     */
    public function test_cannot_delete_category_with_events(): void
    {
        // Arrange
        $this->actingAs($this->admin);
        
        $category = AAB_Category::factory()->create(['name' => 'Category With Events']);
        
        // Create an event in this category
        AAB_Event::factory()->create([
            'category_id' => $category->id,
            'created_by' => $this->admin->id,
        ]);

        // Act
        $response = $this->delete('/admin/categories/' . $category->id);

        // Assert
        $response->assertRedirect(route('categories.index'));
        $response->assertSessionHas('error');
        
        // Category should still exist
        $this->assertDatabaseHas('aab_categories', [
            'id' => $category->id,
        ]);
    }

    /**
     * TC-CAT-005: Delete Category - Empty
     * Technique: Use Case Testing
     * 
     * @test
     */
    public function test_admin_can_delete_empty_category(): void
    {
        // Arrange
        $this->actingAs($this->admin);
        $category = AAB_Category::factory()->create(['name' => 'Empty Category']);

        // Act
        $response = $this->delete('/admin/categories/' . $category->id);

        // Assert
        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseMissing('aab_categories', [
            'id' => $category->id,
        ]);
    }

    /**
     * TC-CAT-UPDATE: Update Category
     * Technique: Use Case Testing
     * 
     * @test
     */
    public function test_admin_can_update_category(): void
    {
        // Arrange
        $this->actingAs($this->admin);
        $category = AAB_Category::factory()->create(['name' => 'Old Name']);

        // Act
        $response = $this->put('/admin/categories/' . $category->id, [
            'name' => 'New Name',
        ]);

        // Assert
        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas('aab_categories', [
            'id' => $category->id,
            'name' => 'New Name',
        ]);
    }

    /**
     * TC-CAT-AUTH: Guest cannot access categories
     * Technique: Decision Table (Authentication)
     * 
     * @test
     */
    public function test_guest_cannot_access_categories(): void
    {
        // Act - No authentication
        $response = $this->get('/admin/categories');

        // Assert
        $response->assertRedirect(route('login'));
    }

    /**
     * TC-CAT-MANAGER-CREATE: Manager cannot create category
     * Technique: Decision Table (Authorization)
     * 
     * @test
     */
    public function test_manager_cannot_create_category(): void
    {
        // Arrange
        $this->actingAs($this->manager);

        // Act
        $response = $this->post('/admin/categories', [
            'name' => 'Manager Category',
        ]);

        // Assert
        $response->assertStatus(403);
    }

    /**
     * TC-CAT-EMPTY-NAME: Cannot create category with empty name
     * Technique: Equivalence Partitioning (Invalid input)
     * 
     * @test
     */
    public function test_cannot_create_category_with_empty_name(): void
    {
        // Arrange
        $this->actingAs($this->admin);

        // Act
        $response = $this->post('/admin/categories', [
            'name' => '',
        ]);

        // Assert
        $response->assertSessionHasErrors('name');
    }
}
