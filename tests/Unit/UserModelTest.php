<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\User;

/**
 * Unit Tests for User Model
 * 
 * Test Level: Unit
 * Test Type: Functional
 * Technique: White Box
 * 
 * Related Test Cases: TC-USR-001
 */
class UserModelTest extends TestCase
{
    /**
     * TC-USR-MODEL-001: Test User fillable attributes
     * Technique: White Box
     * 
     * @test
     */
    public function test_user_fillable_attributes(): void
    {
        $user = new User();
        $fillable = $user->getFillable();
        
        $expectedFillable = [
            'name',
            'email',
            'password',
            'role',
            'profile_image',
            'avatar',
            'phone',
        ];
        
        foreach ($expectedFillable as $field) {
            $this->assertContains($field, $fillable, "Field '$field' should be fillable");
        }
    }

    /**
     * TC-USR-MODEL-002: Test User hidden attributes (security)
     * Technique: White Box (Security testing)
     * 
     * @test
     */
    public function test_user_hidden_attributes(): void
    {
        $user = new User();
        $hidden = $user->getHidden();
        
        $this->assertContains('password', $hidden, "Password should be hidden");
        $this->assertContains('remember_token', $hidden, "Remember token should be hidden");
    }

    /**
     * TC-USR-MODEL-003: Test password is automatically hashed
     * Technique: White Box
     * 
     * @test
     */
    public function test_password_cast_as_hashed(): void
    {
        $user = new User();
        $casts = $user->getCasts();
        
        $this->assertArrayHasKey('password', $casts);
        $this->assertEquals('hashed', $casts['password']);
    }

    /**
     * TC-USR-MODEL-004: Test email_verified_at is datetime
     * Technique: White Box
     * 
     * @test
     */
    public function test_email_verified_at_cast(): void
    {
        $user = new User();
        $casts = $user->getCasts();
        
        $this->assertArrayHasKey('email_verified_at', $casts);
        $this->assertEquals('datetime', $casts['email_verified_at']);
    }
}
