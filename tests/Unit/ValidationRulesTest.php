<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Http\Requests\AAB_LoginRequest;
use App\Http\Requests\AAB_RegisterRequest;
use Illuminate\Support\Facades\Validator;

/**
 * Unit Tests for Validation Rules
 * 
 * Test Level: Unit
 * Test Type: Functional
 * Technique: Equivalence Partitioning, Boundary Value Analysis
 * 
 * Related Test Cases: TC-AUTH-005 to TC-AUTH-016
 */
class ValidationRulesTest extends TestCase
{
    /**
     * TC-AUTH-007: Password minimum length validation (boundary: below minimum)
     * Technique: Boundary Value Analysis
     * 
     * @test
     */
    public function test_login_password_below_minimum_length(): void
    {
        $request = new AAB_LoginRequest();
        $rules = $request->rules();
        
        // Password rule should contain min:3
        $this->assertIsArray($rules['password']);
        $this->assertContains('min:3', $rules['password']);
    }

    /**
     * TC-AUTH-008: Password at minimum length validation
     * Technique: Boundary Value Analysis
     * 
     * @test  
     */
    public function test_login_password_at_minimum_length(): void
    {
        $request = new AAB_LoginRequest();
        $rules = $request->rules();
        
        // Verify the minimum is set to 3
        $passwordRules = $rules['password'];
        $this->assertContains('min:3', $passwordRules);
    }

    /**
     * TC-AUTH-005: Email is required
     * Technique: Equivalence Partitioning
     * 
     * @test
     */
    public function test_login_email_required(): void
    {
        $request = new AAB_LoginRequest();
        $rules = $request->rules();
        
        $this->assertIsArray($rules['email']);
        $this->assertContains('required', $rules['email']);
    }

    /**
     * TC-AUTH-006: Email format validation
     * Technique: Equivalence Partitioning
     * 
     * @test
     */
    public function test_login_email_format(): void
    {
        $request = new AAB_LoginRequest();
        $rules = $request->rules();
        
        $this->assertContains('email', $rules['email']);
    }

    /**
     * TC-AUTH-013: Registration password minimum length (8 characters)
     * Technique: Boundary Value Analysis
     * 
     * @test
     */
    public function test_registration_password_minimum_length(): void
    {
        $request = new AAB_RegisterRequest();
        $rules = $request->rules();
        
        $this->assertIsArray($rules['password']);
        $this->assertContains('min:8', $rules['password']);
    }

    /**
     * TC-AUTH-012: Registration password confirmation required
     * Technique: Equivalence Partitioning
     * 
     * @test
     */
    public function test_registration_password_confirmation(): void
    {
        $request = new AAB_RegisterRequest();
        $rules = $request->rules();
        
        $this->assertContains('confirmed', $rules['password']);
    }

    /**
     * TC-AUTH-011: Email uniqueness for registration
     * Technique: Equivalence Partitioning
     * 
     * @test
     */
    public function test_registration_email_unique(): void
    {
        $request = new AAB_RegisterRequest();
        $rules = $request->rules();
        
        $this->assertContains('unique:users', $rules['email']);
    }

    /**
     * TC-AUTH-015: Name is required for registration
     * Technique: Equivalence Partitioning
     * 
     * @test
     */
    public function test_registration_name_required(): void
    {
        $request = new AAB_RegisterRequest();
        $rules = $request->rules();
        
        $this->assertContains('required', $rules['name']);
    }

    /**
     * TC-AUTH-016: Name maximum length validation (255 characters)
     * Technique: Boundary Value Analysis
     * 
     * @test
     */
    public function test_registration_name_max_length(): void
    {
        $request = new AAB_RegisterRequest();
        $rules = $request->rules();
        
        $this->assertContains('max:255', $rules['name']);
    }

    /**
     * TC-AUTH-REG-PHONE: Phone is optional
     * Technique: Equivalence Partitioning
     * 
     * @test
     */
    public function test_registration_phone_optional(): void
    {
        $request = new AAB_RegisterRequest();
        $rules = $request->rules();
        
        $this->assertContains('nullable', $rules['phone']);
    }

    /**
     * Test that login request is authorized (always returns true for guest)
     * Technique: White Box
     * 
     * @test
     */
    public function test_login_request_authorization(): void
    {
        $request = new AAB_LoginRequest();
        $this->assertTrue($request->authorize());
    }

    /**
     * Test that register request is authorized (always returns true for guest)
     * Technique: White Box
     * 
     * @test
     */
    public function test_register_request_authorization(): void
    {
        $request = new AAB_RegisterRequest();
        $this->assertTrue($request->authorize());
    }
}
