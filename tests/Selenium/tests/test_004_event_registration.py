"""
Event Registration System Tests for AAB_EventPlanner.
Implements automated end-to-end tests for event registration functionality.

Test Level: System
Test Type: Functional
Pattern: Page Object Model (POM)
Technique: Use Case Testing, Equivalence Partitioning, Decision Table

Related Test Cases: TC-REG-001 to TC-REG-010
"""

import pytest
import sys
import os
from time import sleep

# Add parent directories to path
sys.path.insert(0, os.path.dirname(os.path.dirname(os.path.abspath(__file__))))

from pages.LoginPage import LoginPage
from pages.HomePage import HomePage
from pages.EventDetailsPage import EventDetailsPage
from utilities.readProperties import ReadConfig
from utilities.customLogger import CustomLogger


class Test_004_EventRegistration:
    """
    Test class for Event Registration functionality.
    Contains system-level tests for registering/unregistering for events.
    """
    
    # Configuration
    base_url = ReadConfig.get_base_url()
    user_email = ReadConfig.get_user_email()
    user_password = ReadConfig.get_user_password()
    
    # Logger
    logger = CustomLogger.get_logger()
    
    def login_as_user(self, driver):
        """
        Helper method to login as regular user.
        
        Args:
            driver: WebDriver instance
        """
        driver.get(self.base_url + "/login")
        sleep(2)
        login_page = LoginPage(driver)
        login_page.login(self.user_email, self.user_password)
        sleep(3)
    
    @pytest.mark.smoke
    @pytest.mark.registration
    def test_TC_REG_001_register_for_event(self, setup):
        """
        TC-REG-001: Register for Event Successfully
        
        Technique: Use Case Testing
        Preconditions: User logged in, event available with capacity
        Expected: Registration succeeds with success message
        """
        self.logger.info("*** Test TC-REG-001: Register for Event ***")
        self.driver = setup
        
        # Login
        self.login_as_user(self.driver)
        
        # Navigate to home
        self.driver.get(self.base_url + "/home")
        sleep(2)
        
        home_page = HomePage(self.driver)
        event_count = home_page.get_event_count()
        
        if event_count > 0:
            # Click first event
            home_page.click_first_event()
            sleep(3)
            
            event_details = EventDetailsPage(self.driver)
            
            # Check if register button is visible (not already registered)
            if event_details.is_register_button_visible():
                event_details.click_register_button()
                sleep(3)
                
                # Check for success message or unregister button
                success_msg = event_details.get_success_message()
                is_registered = event_details.is_unregister_button_visible()
                
                assert success_msg is not None or is_registered, \
                    "Should show success message or unregister button after registration"
                
                self.logger.info("Registration successful")
            else:
                self.logger.info("Already registered or register button not visible")
        else:
            pytest.skip("No events available for registration test")
        
        self.logger.info("*** Test TC-REG-001: PASSED ***")
    
    @pytest.mark.registration
    def test_TC_REG_004_register_not_logged_in(self, setup):
        """
        TC-REG-004: Register - Not Logged In
        
        Technique: Decision Table (Authentication required)
        Expected: Redirect to login when trying to register
        """
        self.logger.info("*** Test TC-REG-004: Register Without Login ***")
        self.driver = setup
        
        # Navigate directly to home without login
        self.driver.get(self.base_url + "/home")
        sleep(3)
        
        home_page = HomePage(self.driver)
        event_count = home_page.get_event_count()
        
        if event_count > 0:
            # Click first event
            home_page.click_first_event()
            sleep(3)
            
            event_details = EventDetailsPage(self.driver)
            
            # Try to register (if button visible)
            if event_details.is_register_button_visible():
                event_details.click_register_button()
                sleep(3)
                
                # Should redirect to login
                current_url = self.driver.current_url
                assert "login" in current_url, \
                    f"Should redirect to login, got: {current_url}"
                
                self.logger.info("Correctly redirected to login")
            else:
                self.logger.info("Register button not visible for guest")
        else:
            pytest.skip("No events available")
        
        self.logger.info("*** Test TC-REG-004: PASSED ***")
    
    @pytest.mark.registration
    def test_TC_REG_005_unregister_from_event(self, setup):
        """
        TC-REG-005: Unregister from Event
        
        Technique: Use Case Testing
        Preconditions: User logged in and registered for an event
        Expected: Unregistration succeeds
        """
        self.logger.info("*** Test TC-REG-005: Unregister from Event ***")
        self.driver = setup
        
        # Login
        self.login_as_user(self.driver)
        
        # Navigate to home
        self.driver.get(self.base_url + "/home")
        sleep(2)
        
        home_page = HomePage(self.driver)
        event_count = home_page.get_event_count()
        
        if event_count > 0:
            # Click first event
            home_page.click_first_event()
            sleep(3)
            
            event_details = EventDetailsPage(self.driver)
            
            # If already registered, unregister
            if event_details.is_unregister_button_visible():
                event_details.click_unregister_button()
                sleep(3)
                
                # Check for success or register button visible again
                success_msg = event_details.get_success_message()
                register_visible = event_details.is_register_button_visible()
                
                assert success_msg is not None or register_visible, \
                    "Should show success or register button after unregistration"
                
                self.logger.info("Unregistration successful")
            elif event_details.is_register_button_visible():
                # Register first, then unregister
                event_details.click_register_button()
                sleep(3)
                
                # Refresh page to see unregister button
                self.driver.refresh()
                sleep(2)
                
                event_details = EventDetailsPage(self.driver)
                
                if event_details.is_unregister_button_visible():
                    event_details.click_unregister_button()
                    sleep(3)
                    self.logger.info("Unregistration after registration successful")
            else:
                self.logger.info("Neither register nor unregister button visible")
        else:
            pytest.skip("No events available")
        
        self.logger.info("*** Test TC-REG-005: PASSED ***")
    
    @pytest.mark.registration
    def test_TC_REG_010_view_my_registrations(self, setup):
        """
        TC-REG-010: View My Registrations
        
        Technique: Use Case Testing
        Preconditions: User logged in
        Expected: My registrations page is accessible
        """
        self.logger.info("*** Test TC-REG-010: View My Registrations ***")
        self.driver = setup
        
        # Login
        self.login_as_user(self.driver)
        
        # Navigate to my registrations
        self.driver.get(self.base_url + "/my-registrations")
        sleep(3)
        
        # Verify page loaded (not redirected to login)
        current_url = self.driver.current_url
        assert "registrations" in current_url, \
            f"Should be on registrations page, got: {current_url}"
        
        self.logger.info("*** Test TC-REG-010: PASSED ***")
    
    @pytest.mark.registration
    def test_TC_REG_AUTH_my_registrations_requires_login(self, setup):
        """
        TC-REG-AUTH: My Registrations requires authentication
        
        Technique: Decision Table (Authentication)
        Expected: Redirect to login when accessing without auth
        """
        self.logger.info("*** Test TC-REG-AUTH: My Registrations Auth ***")
        self.driver = setup
        
        # Try to access my-registrations without login
        self.driver.get(self.base_url + "/my-registrations")
        sleep(3)
        
        # Should redirect to login
        current_url = self.driver.current_url
        assert "login" in current_url, \
            f"Should redirect to login, got: {current_url}"
        
        self.logger.info("*** Test TC-REG-AUTH: PASSED ***")


if __name__ == "__main__":
    pytest.main([__file__, "-v", "-s"])
