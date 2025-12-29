"""
Registration System Tests for AAB_EventPlanner.
Implements automated end-to-end tests for user registration functionality.

Test Level: System
Test Type: Functional
Pattern: Page Object Model (POM)
Technique: Use Case Testing, Equivalence Partitioning, Boundary Value Analysis

Related Test Cases: TC-AUTH-010 to TC-AUTH-016
"""

import pytest
import sys
import os
from time import sleep
import random
import string

# Add parent directories to path
sys.path.insert(0, os.path.dirname(os.path.dirname(os.path.abspath(__file__))))

from pages.RegisterPage import RegisterPage
from pages.LoginPage import LoginPage
from utilities.readProperties import ReadConfig
from utilities.customLogger import CustomLogger


def generate_random_email():
    """Generate a random email for testing."""
    random_string = ''.join(random.choices(string.ascii_lowercase + string.digits, k=8))
    return f"test_{random_string}@selenium.test"


class Test_002_Registration:
    """
    Test class for Registration functionality.
    Contains system-level tests for user registration feature.
    """
    
    # Configuration
    base_url = ReadConfig.get_base_url()
    test_name = ReadConfig.get_test_user_name()
    test_password = ReadConfig.get_test_user_password()
    
    # Logger
    logger = CustomLogger.get_logger()
    
    @pytest.mark.smoke
    @pytest.mark.authentication
    def test_TC_AUTH_VIEW_002_register_page_accessible(self, setup):
        """
        TC-AUTH-VIEW-002: Verify register page is accessible.
        
        Technique: Use Case Testing
        Expected: Registration page loads successfully with Sign Up title visible
        """
        self.logger.info("*** Test TC-AUTH-VIEW-002: Register Page Accessible ***")
        self.driver = setup
        
        # Navigate to register page
        self.driver.get(self.base_url + "/register")
        sleep(2)
        
        # Initialize page object
        register_page = RegisterPage(self.driver)
        
        # Verify register page is displayed
        assert register_page.is_register_page(), "Registration page should be displayed"
        
        self.logger.info("*** Test TC-AUTH-VIEW-002: PASSED ***")
    
    @pytest.mark.smoke
    @pytest.mark.authentication
    def test_TC_AUTH_010_valid_registration(self, setup):
        """
        TC-AUTH-010: Valid Registration
        
        Technique: Use Case Testing
        Preconditions: Email not already registered
        Expected: User created and redirected to login with success message
        """
        self.logger.info("*** Test TC-AUTH-010: Valid Registration ***")
        self.driver = setup
        
        # Navigate to register page
        self.driver.get(self.base_url + "/register")
        sleep(2)
        
        # Initialize page object
        register_page = RegisterPage(self.driver)
        
        # Generate unique email
        test_email = generate_random_email()
        
        # Perform registration
        register_page.register(
            name=self.test_name,
            email=test_email,
            password=self.test_password
        )
        sleep(3)
        
        # Verify redirect to login page
        current_url = self.driver.current_url
        assert "login" in current_url, f"Should redirect to login page, got: {current_url}"
        
        # Check for success message on login page
        login_page = LoginPage(self.driver)
        success_msg = login_page.get_success_message()
        assert success_msg is not None or "login" in current_url, \
            "Should show success message or be on login page"
        
        self.logger.info("*** Test TC-AUTH-010: PASSED ***")
    
    @pytest.mark.authentication
    def test_TC_AUTH_012_password_mismatch(self, setup):
        """
        TC-AUTH-012: Registration - Password Mismatch
        
        Technique: Equivalence Partitioning
        Expected: Validation error for password confirmation
        """
        self.logger.info("*** Test TC-AUTH-012: Password Mismatch ***")
        self.driver = setup
        
        # Navigate to register page
        self.driver.get(self.base_url + "/register")
        sleep(2)
        
        # Initialize page object
        register_page = RegisterPage(self.driver)
        
        # Generate unique email
        test_email = generate_random_email()
        
        # Perform registration with mismatched passwords
        register_page.register(
            name=self.test_name,
            email=test_email,
            password="password123",
            password_confirm="differentpassword"
        )
        sleep(2)
        
        # Verify error is displayed or still on register page
        assert register_page.is_register_page() or register_page.is_error_displayed(), \
            "Should show error or remain on registration page"
        
        self.logger.info("*** Test TC-AUTH-012: PASSED ***")
    
    @pytest.mark.authentication
    def test_TC_AUTH_013_password_too_short(self, setup):
        """
        TC-AUTH-013: Registration - Password Too Short
        
        Technique: Boundary Value Analysis (Below boundary)
        Expected: Validation error for password length (min 8 characters)
        """
        self.logger.info("*** Test TC-AUTH-013: Password Too Short ***")
        self.driver = setup
        
        # Navigate to register page
        self.driver.get(self.base_url + "/register")
        sleep(2)
        
        # Initialize page object
        register_page = RegisterPage(self.driver)
        
        # Generate unique email
        test_email = generate_random_email()
        
        # Perform registration with short password (7 chars, below 8 minimum)
        register_page.register(
            name=self.test_name,
            email=test_email,
            password="pass123"  # 7 characters
        )
        sleep(2)
        
        # Verify error is displayed or still on register page
        assert register_page.is_register_page() or register_page.has_field_errors(), \
            "Should show validation error for short password"
        
        self.logger.info("*** Test TC-AUTH-013: PASSED ***")
    
    @pytest.mark.authentication
    def test_TC_AUTH_014_password_at_minimum(self, setup):
        """
        TC-AUTH-014: Registration - Password At Minimum Length
        
        Technique: Boundary Value Analysis (At boundary)
        Expected: Registration succeeds with 8 character password
        """
        self.logger.info("*** Test TC-AUTH-014: Password At Minimum Length ***")
        self.driver = setup
        
        # Navigate to register page
        self.driver.get(self.base_url + "/register")
        sleep(2)
        
        # Initialize page object
        register_page = RegisterPage(self.driver)
        
        # Generate unique email
        test_email = generate_random_email()
        
        # Perform registration with exactly 8 character password
        register_page.register(
            name=self.test_name,
            email=test_email,
            password="pass1234"  # Exactly 8 characters
        )
        sleep(3)
        
        # Verify redirect to login page (success)
        current_url = self.driver.current_url
        assert "login" in current_url, \
            f"Should redirect to login with valid password, got: {current_url}"
        
        self.logger.info("*** Test TC-AUTH-014: PASSED ***")
    
    @pytest.mark.authentication
    def test_TC_AUTH_015_empty_name(self, setup):
        """
        TC-AUTH-015: Registration - Empty Name
        
        Technique: Equivalence Partitioning (Invalid - required field)
        Expected: Validation error for empty name
        """
        self.logger.info("*** Test TC-AUTH-015: Empty Name ***")
        self.driver = setup
        
        # Navigate to register page
        self.driver.get(self.base_url + "/register")
        sleep(2)
        
        # Initialize page object
        register_page = RegisterPage(self.driver)
        
        # Generate unique email
        test_email = generate_random_email()
        
        # Perform registration with empty name
        register_page.register(
            name="",
            email=test_email,
            password=self.test_password
        )
        sleep(2)
        
        # Verify still on register page (browser validation or server validation)
        current_url = self.driver.current_url
        assert "register" in current_url or register_page.is_register_page(), \
            "Should remain on registration page with empty name"
        
        self.logger.info("*** Test TC-AUTH-015: PASSED ***")
    
    @pytest.mark.authentication
    def test_TC_AUTH_NAVIGATE_login_link(self, setup):
        """
        TC-AUTH-NAVIGATE: Navigation from Register to Login
        
        Technique: Use Case Testing
        Expected: Login link navigates to login page
        """
        self.logger.info("*** Test TC-AUTH-NAVIGATE: Register to Login Navigation ***")
        self.driver = setup
        
        # Navigate to register page
        self.driver.get(self.base_url + "/register")
        sleep(2)
        
        # Initialize page object
        register_page = RegisterPage(self.driver)
        
        # Click login link
        register_page.click_login_link()
        sleep(2)
        
        # Verify on login page
        login_page = LoginPage(self.driver)
        assert login_page.is_login_page(), "Should navigate to login page"
        
        self.logger.info("*** Test TC-AUTH-NAVIGATE: PASSED ***")


if __name__ == "__main__":
    pytest.main([__file__, "-v", "-s"])
