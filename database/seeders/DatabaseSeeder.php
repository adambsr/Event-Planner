<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\AAB_Category;
use App\Models\AAB_Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            'view events',
            'edit events',
            'delete events',
            'publish events',
            'view categories',
            'edit categories',
            'delete categories',
            'create categories',
            'view users',
            'create users',
            'edit users',
            'delete users',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Assign all permissions to admin
        $adminRole->givePermissionTo(Permission::all());

        // Assign view-only permissions to manager
        $managerRole->givePermissionTo([
            'view events',
            'view categories',
            'view users',
        ]);

        // Create admin user (or get existing)
        $admin = User::firstOrCreate(
            ['email' => 'admin@eventplanner.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );
        $admin->assignRole('admin');

        // Create manager user (or get existing)
        $manager = User::firstOrCreate(
            ['email' => 'manager@eventplanner.com'],
            [
                'name' => 'Manager',
                'password' => Hash::make('manager123'),
                'role' => 'manager',
            ]
        );
        $manager->assignRole('manager');

        // Create regular user (or get existing)
        $user = User::firstOrCreate(
            ['email' => 'user@eventplanner.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('user123'),
                'role' => 'user',
            ]
        );
        $user->assignRole('user');

        // Create sample categories (or get existing)
        $categoryNames = ['Technology', 'Business', 'Arts & Culture', 'Sports', 'Education'];
        $createdCategories = [];
        
        foreach ($categoryNames as $categoryName) {
            $createdCategories[] = AAB_Category::firstOrCreate(['name' => $categoryName]);
        }

        // Create sample events (only if they don't exist)
        $events = [
            [
                'title' => 'Tech Conference 2024',
                'description' => 'Join us for the biggest technology conference of the year featuring industry leaders and innovative startups.',
                'start_date' => now()->addDays(30),
                'end_date' => now()->addDays(32),
                'place' => 'Convention Center, Downtown',
                'price' => 299.99,
                'category_id' => $createdCategories[0]->id,
                'capacity' => 500,
                'created_by' => $admin->id,
                'is_free' => false,
                'status' => 'active',
            ],
            [
                'title' => 'Free Coding Workshop',
                'description' => 'Learn web development basics in this free hands-on workshop. Perfect for beginners!',
                'start_date' => now()->addDays(15),
                'end_date' => now()->addDays(15),
                'place' => 'Community Center',
                'price' => 0,
                'category_id' => $createdCategories[0]->id,
                'capacity' => 50,
                'created_by' => $admin->id,
                'is_free' => true,
                'status' => 'active',
            ],
            [
                'title' => 'Business Networking Event',
                'description' => 'Connect with local entrepreneurs and business professionals over drinks and appetizers.',
                'start_date' => now()->addDays(20),
                'end_date' => now()->addDays(20),
                'place' => 'Grand Hotel Ballroom',
                'price' => 49.99,
                'category_id' => $createdCategories[1]->id,
                'capacity' => 200,
                'created_by' => $admin->id,
                'is_free' => false,
                'status' => 'active',
            ],
            [
                'title' => 'Art Gallery Opening',
                'description' => 'Exhibition featuring contemporary artists from around the region. Free admission.',
                'start_date' => now()->addDays(10),
                'end_date' => now()->addDays(45),
                'place' => 'Modern Art Museum',
                'price' => 0,
                'category_id' => $createdCategories[2]->id,
                'capacity' => 300,
                'created_by' => $admin->id,
                'is_free' => true,
                'status' => 'active',
            ],
            [
                'title' => 'Marathon Run',
                'description' => 'Annual city marathon with multiple race categories. Register early for best prices!',
                'start_date' => now()->addDays(60),
                'end_date' => now()->addDays(60),
                'place' => 'City Park',
                'price' => 75.00,
                'category_id' => $createdCategories[3]->id,
                'capacity' => 1000,
                'created_by' => $admin->id,
                'is_free' => false,
                'status' => 'active',
            ],
        ];

        foreach ($events as $eventData) {
            AAB_Event::firstOrCreate(
                ['title' => $eventData['title']],
                $eventData
            );
        }

        // Optional: Use factories to create more random data
        // Uncomment the lines below if you want additional random events/categories
        
        // AAB_Category::factory(5)->create(); // Creates 5 more random categories
        // AAB_Event::factory(10)->create(); // Creates 10 more random events

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin user: admin@eventplanner.com / admin123');
        $this->command->info('Manager user: manager@eventplanner.com / manager123');
        $this->command->info('Regular user: user@eventplanner.com / user123');
        $this->command->info('Created ' . count($createdCategories) . ' categories and ' . count($events) . ' events.');
    }
}
