"""
Authentication System Tests for AAB_EventPlanner.
Implements automated end-to-end tests for login functionality.

Test Level: System
Test Type: Functional
Pattern: Page Object Model (POM)
Technique: Use Case Testing, Equivalence Partitioning

Related Test Cases: TC-AUTH-001 to TC-AUTH-008
"""

import pytest
import sys
import os
from time import sleep

# Add parent directories to path
sys.path.insert(0, os.path.dirname(os.path.dirname(os.path.abspath(__file__))))

from pages.LoginPage import LoginPage
from pages.HomePage import HomePage
from utilities.readProperties import ReadConfig
from utilities.customLogger import CustomLogger


class Test_001_Login:
    """
    Test class for Login functionality.
    Contains system-level tests for authentication feature.
    """
    
    # Configuration
    base_url = ReadConfig.get_base_url()
    admin_email = ReadConfig.get_admin_email()
    admin_password = ReadConfig.get_admin_password()
    user_email = ReadConfig.get_user_email()
    user_password = ReadConfig.get_user_password()
    
    # Logger
    logger = CustomLogger.get_logger()
    
    @pytest.mark.smoke
    @pytest.mark.authentication
    def test_TC_AUTH_VIEW_001_login_page_accessible(self, setup):
        """
        TC-AUTH-VIEW-001: Verify login page is accessible.
        
        Technique: Use Case Testing
        Expected: Login page loads successfully with Sign In title visible
        """
        self.logger.info("*** Test TC-AUTH-VIEW-001: Login Page Accessible ***")
        self.driver = setup
        
        # Navigate to login page
        self.driver.get(self.base_url + "/login")
        sleep(2)
        
        # Initialize page object
        login_page = LoginPage(self.driver)
        
        # Verify login page is displayed
        assert login_page.is_login_page(), "Login page should be displayed"
        assert login_page.is_brand_visible(), "Brand should be visible"
        
        self.logger.info("*** Test TC-AUTH-VIEW-001: PASSED ***")
    
    @pytest.mark.smoke
    @pytest.mark.authentication
    def test_TC_AUTH_001_valid_admin_login(self, setup):
        """
        TC-AUTH-001: Valid Login - Admin User
        
        Technique: Use Case Testing
        Preconditions: Admin user exists in database
        Expected: Admin user redirected to admin events page
        """
        self.logger.info("*** Test TC-AUTH-001: Valid Admin Login ***")
        self.driver = setup
        
        # Navigate to login page
        self.driver.get(self.base_url + "/login")
        sleep(2)
        
        # Initialize page object
        login_page = LoginPage(self.driver)
        
        # Perform login
        login_page.login(self.admin_email, self.admin_password)
        sleep(3)
        
        # Verify redirect to admin events page
        current_url = self.driver.current_url
        assert "admin/events" in current_url or "events" in current_url, \
            f"Admin should be redirected to events page, got: {current_url}"
        
        self.logger.info("*** Test TC-AUTH-001: PASSED ***")
    
    @pytest.mark.smoke
    @pytest.mark.authentication
    def test_TC_AUTH_002_valid_user_login(self, setup):
        """
        TC-AUTH-002: Valid Login - Regular User
        
        Technique: Use Case Testing, Decision Table (Role-based redirection)
        Preconditions: Regular user exists in database
        Expected: User redirected to home page
        """
        self.logger.info("*** Test TC-AUTH-002: Valid User Login ***")
        self.driver = setup
        
        # Navigate to login page
        self.driver.get(self.base_url + "/login")
        sleep(2)
        
        # Initialize page object
        login_page = LoginPage(self.driver)
        
        # Perform login
        login_page.login(self.user_email, self.user_password)
        sleep(3)
        
        # Verify redirect to home page
        current_url = self.driver.current_url
        assert "home" in current_url or "events" in current_url, \
            f"User should be redirected to home page, got: {current_url}"
        
        self.logger.info("*** Test TC-AUTH-002: PASSED ***")
    
    @pytest.mark.authentication
    def test_TC_AUTH_003_invalid_login_wrong_password(self, setup):
        """
        TC-AUTH-003: Invalid Login - Wrong Password
        
        Technique: Equivalence Partitioning (Invalid partition)
        Expected: Error message displayed, user stays on login page
        """
        self.logger.info("*** Test TC-AUTH-003: Invalid Login - Wrong Password ***")
        self.driver = setup
        
        # Navigate to login page
        self.driver.get(self.base_url + "/login")
        sleep(2)
        
        # Initialize page object
        login_page = LoginPage(self.driver)
        
        # Attempt login with wrong password
        login_page.login(self.admin_email, "wrongpassword123")
        sleep(2)
        
        # Verify error is displayed
        assert login_page.is_error_displayed(), "Error message should be displayed"
        error_text = login_page.get_error_message()
        assert error_text is not None, "Error message text should exist"
        
        # Verify still on login page
        assert login_page.is_login_page(), "User should remain on login page"
        
        self.logger.info("*** Test TC-AUTH-003: PASSED ***")
    
    @pytest.mark.authentication
    def test_TC_AUTH_004_invalid_login_nonexistent_email(self, setup):
        """
        TC-AUTH-004: Invalid Login - Non-existent Email
        
        Technique: Equivalence Partitioning (Invalid partition)
        Expected: Error message displayed
        """
        self.logger.info("*** Test TC-AUTH-004: Invalid Login - Non-existent Email ***")
        self.driver = setup
        
        # Navigate to login page
        self.driver.get(self.base_url + "/login")
        sleep(2)
        
        # Initialize page object
        login_page = LoginPage(self.driver)
        
        # Attempt login with non-existent email
        login_page.login("nonexistent@notreal.com", "anypassword")
        sleep(2)
        
        # Verify error is displayed or still on login page
        assert login_page.is_login_page() or login_page.is_error_displayed(), \
            "Should show error or remain on login page"
        
        self.logger.info("*** Test TC-AUTH-004: PASSED ***")
    
    @pytest.mark.authentication
    def test_TC_AUTH_006_invalid_email_format(self, setup):
        """
        TC-AUTH-006: Invalid Login - Invalid Email Format
        
        Technique: Equivalence Partitioning
        Expected: Validation error for invalid email format
        """
        self.logger.info("*** Test TC-AUTH-006: Invalid Email Format ***")
        self.driver = setup
        
        # Navigate to login page
        self.driver.get(self.base_url + "/login")
        sleep(2)
        
        # Initialize page object
        login_page = LoginPage(self.driver)
        
        # Attempt login with invalid email format
        login_page.login("notanemail", "password123")
        sleep(2)
        
        # Browser should block submission or show error
        # Either still on login page or error displayed
        current_url = self.driver.current_url
        assert "login" in current_url or login_page.is_error_displayed(), \
            "Should show validation error or remain on login page"
        
        self.logger.info("*** Test TC-AUTH-006: PASSED ***")


if __name__ == "__main__":
    pytest.main([__file__, "-v", "-s"])
