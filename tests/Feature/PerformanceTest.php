<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\AAB_Event;
use App\Models\AAB_Category;
use Spatie\Permission\Models\Role;

/**
 * Performance Tests for AAB_EventPlanner
 * 
 * Test Level: System
 * Test Type: Non-Functional (Performance)
 * Technique: Benchmark Testing
 * 
 * Related Test Cases: TC-PERF-001, TC-PERF-002
 * 
 * These tests verify that critical pages respond within acceptable time limits.
 * Performance thresholds are based on user experience guidelines (< 3 seconds).
 */
class PerformanceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Maximum acceptable response time in seconds.
     */
    private const MAX_RESPONSE_TIME_HOME = 3.0;
    private const MAX_RESPONSE_TIME_LOGIN = 2.0;
    private const MAX_RESPONSE_TIME_EVENTS = 3.0;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles
        Role::findOrCreate('admin', 'web');
        Role::findOrCreate('user', 'web');
        
        // Seed some test data for realistic performance testing
        $category = AAB_Category::factory()->create(['name' => 'Performance Test Category']);
        
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        
        // Create multiple events to simulate realistic load
        for ($i = 0; $i < 20; $i++) {
            AAB_Event::factory()->create([
                'category_id' => $category->id,
                'created_by' => $admin->id,
                'status' => 'active',
            ]);
        }
    }

    /**
     * TC-PERF-001: Home Page Load Time Performance Test
     * 
     * Technique: Benchmark Testing
     * Preconditions: Application running, events exist in database
     * Expected: Home page response time < 3 seconds
     * 
     * @test
     */
    public function test_TC_PERF_001_home_page_loads_within_acceptable_time(): void
    {
        // Arrange
        $startTime = microtime(true);
        
        // Act
        $response = $this->get('/home');
        
        // Calculate response time
        $endTime = microtime(true);
        $responseTime = $endTime - $startTime;
        
        // Assert
        $response->assertStatus(200);
        
        $this->assertLessThan(
            self::MAX_RESPONSE_TIME_HOME,
            $responseTime,
            sprintf(
                'Home page response time (%.3f seconds) exceeds maximum allowed (%.1f seconds)',
                $responseTime,
                self::MAX_RESPONSE_TIME_HOME
            )
        );
        
        // Log performance result for reporting
        $this->addToAssertionCount(1);
        fwrite(STDERR, sprintf(
            "\n[PERF] TC-PERF-001: Home page loaded in %.3f seconds (max: %.1f s) - PASS\n",
            $responseTime,
            self::MAX_RESPONSE_TIME_HOME
        ));
    }

    /**
     * TC-PERF-002: Login Response Time Performance Test
     * 
     * Technique: Benchmark Testing
     * Preconditions: Application running, valid user exists
     * Expected: Login process response time < 2 seconds
     * 
     * @test
     */
    public function test_TC_PERF_002_login_completes_within_acceptable_time(): void
    {
        // Arrange
        $user = User::factory()->create([
            'email' => 'perftest@test.com',
            'password' => bcrypt('password123'),
        ]);
        $user->assignRole('user');
        
        $startTime = microtime(true);
        
        // Act
        $response = $this->post('/login', [
            'email' => 'perftest@test.com',
            'password' => 'password123',
        ]);
        
        // Calculate response time
        $endTime = microtime(true);
        $responseTime = $endTime - $startTime;
        
        // Assert - should redirect (302) on successful login
        $response->assertStatus(302);
        $this->assertAuthenticated();
        
        $this->assertLessThan(
            self::MAX_RESPONSE_TIME_LOGIN,
            $responseTime,
            sprintf(
                'Login response time (%.3f seconds) exceeds maximum allowed (%.1f seconds)',
                $responseTime,
                self::MAX_RESPONSE_TIME_LOGIN
            )
        );
        
        // Log performance result for reporting
        fwrite(STDERR, sprintf(
            "\n[PERF] TC-PERF-002: Login completed in %.3f seconds (max: %.1f s) - PASS\n",
            $responseTime,
            self::MAX_RESPONSE_TIME_LOGIN
        ));
    }

    /**
     * TC-PERF-003: Events Listing with Pagination Performance Test
     * 
     * Technique: Benchmark Testing
     * Preconditions: Multiple events exist (20+)
     * Expected: Events page with pagination responds < 3 seconds
     * 
     * @test
     */
    public function test_TC_PERF_003_events_listing_pagination_performance(): void
    {
        // Arrange
        $startTime = microtime(true);
        
        // Act - Request paginated events
        $response = $this->get('/home?page=1');
        
        // Calculate response time
        $endTime = microtime(true);
        $responseTime = $endTime - $startTime;
        
        // Assert
        $response->assertStatus(200);
        
        $this->assertLessThan(
            self::MAX_RESPONSE_TIME_EVENTS,
            $responseTime,
            sprintf(
                'Events listing response time (%.3f seconds) exceeds maximum (%.1f seconds)',
                $responseTime,
                self::MAX_RESPONSE_TIME_EVENTS
            )
        );
        
        // Log performance result
        fwrite(STDERR, sprintf(
            "\n[PERF] TC-PERF-003: Events listing loaded in %.3f seconds (max: %.1f s) - PASS\n",
            $responseTime,
            self::MAX_RESPONSE_TIME_EVENTS
        ));
    }

    /**
     * TC-PERF-004: Search Functionality Performance Test
     * 
     * Technique: Benchmark Testing
     * Expected: Search responds within acceptable time
     * 
     * @test
     */
    public function test_TC_PERF_004_search_performance(): void
    {
        // Arrange
        $startTime = microtime(true);
        
        // Act - Perform search
        $response = $this->get('/home?search=test');
        
        // Calculate response time
        $endTime = microtime(true);
        $responseTime = $endTime - $startTime;
        
        // Assert
        $response->assertStatus(200);
        
        $this->assertLessThan(
            self::MAX_RESPONSE_TIME_EVENTS,
            $responseTime,
            sprintf(
                'Search response time (%.3f seconds) exceeds maximum (%.1f seconds)',
                $responseTime,
                self::MAX_RESPONSE_TIME_EVENTS
            )
        );
        
        fwrite(STDERR, sprintf(
            "\n[PERF] TC-PERF-004: Search completed in %.3f seconds (max: %.1f s) - PASS\n",
            $responseTime,
            self::MAX_RESPONSE_TIME_EVENTS
        ));
    }
}
