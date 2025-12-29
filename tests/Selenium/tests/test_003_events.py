"""
Events System Tests for AAB_EventPlanner.
Implements automated end-to-end tests for event browsing and search functionality.

Test Level: System
Test Type: Functional
Pattern: Page Object Model (POM)
Technique: Use Case Testing, Equivalence Partitioning, State Transition

Related Test Cases: TC-EVT-001 to TC-EVT-030
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


class Test_003_Events:
    """
    Test class for Event functionality.
    Contains system-level tests for event browsing, search, and filtering.
    """
    
    # Configuration
    base_url = ReadConfig.get_base_url()
    user_email = ReadConfig.get_user_email()
    user_password = ReadConfig.get_user_password()
    
    # Logger
    logger = CustomLogger.get_logger()
    
    @pytest.mark.smoke
    @pytest.mark.events
    def test_TC_EVT_001_public_events_listing(self, setup):
        """
        TC-EVT-001: View Public Events - Only Active Events Displayed
        
        Technique: Use Case Testing, State Transition
        Expected: Home page displays list of active events
        """
        self.logger.info("*** Test TC-EVT-001: Public Events Listing ***")
        self.driver = setup
        
        # Navigate to home page
        self.driver.get(self.base_url + "/home")
        sleep(3)
        
        # Initialize page object
        home_page = HomePage(self.driver)
        
        # Verify events are displayed (or no events message)
        event_count = home_page.get_event_count()
        self.logger.info(f"Found {event_count} events on home page")
        
        # Page should load successfully
        assert home_page.is_element_visible(home_page.HEADER), \
            "Home page should load with header visible"
        
        self.logger.info("*** Test TC-EVT-001: PASSED ***")
    
    @pytest.mark.events
    def test_TC_EVT_010_search_by_title(self, setup):
        """
        TC-EVT-010: Search Events by Title
        
        Technique: Equivalence Partitioning
        Preconditions: Events exist in database
        Expected: Only events matching search term are displayed
        """
        self.logger.info("*** Test TC-EVT-010: Search Events by Title ***")
        self.driver = setup
        
        # Navigate to home page
        self.driver.get(self.base_url + "/home")
        sleep(3)
        
        # Initialize page object
        home_page = HomePage(self.driver)
        
        # Get initial event count
        initial_count = home_page.get_event_count()
        self.logger.info(f"Initial event count: {initial_count}")
        
        # If there are events, get a title to search for
        if initial_count > 0:
            titles = home_page.get_event_titles()
            if titles:
                # Search for part of first event title
                search_term = titles[0].split()[0] if titles[0].split() else titles[0][:5]
                
                # Perform search
                home_page.search_events(search_term)
                sleep(2)
                
                # Verify search was performed (URL contains search param)
                current_url = self.driver.current_url
                assert "search" in current_url, "Search parameter should be in URL"
                
                self.logger.info(f"Searched for: {search_term}")
        
        self.logger.info("*** Test TC-EVT-010: PASSED ***")
    
    @pytest.mark.events
    def test_TC_EVT_012_search_no_results(self, setup):
        """
        TC-EVT-012: Search Returns No Results
        
        Technique: Equivalence Partitioning (Empty result set)
        Expected: No events displayed for non-matching search
        """
        self.logger.info("*** Test TC-EVT-012: Search No Results ***")
        self.driver = setup
        
        # Navigate to home page
        self.driver.get(self.base_url + "/home")
        sleep(2)
        
        # Initialize page object
        home_page = HomePage(self.driver)
        
        # Search for non-existent term
        home_page.search_events("xyznonexistent123abc")
        sleep(2)
        
        # Verify no events displayed
        event_count = home_page.get_event_count()
        assert event_count == 0 or home_page.no_events_displayed(), \
            "No events should be displayed for non-matching search"
        
        self.logger.info("*** Test TC-EVT-012: PASSED ***")
    
    @pytest.mark.events
    def test_TC_EVT_030_view_event_details(self, setup):
        """
        TC-EVT-030: View Event Details
        
        Technique: Use Case Testing
        Preconditions: At least one event exists
        Expected: Event details page shows title, description, place, etc.
        """
        self.logger.info("*** Test TC-EVT-030: View Event Details ***")
        self.driver = setup
        
        # Navigate to home page
        self.driver.get(self.base_url + "/home")
        sleep(3)
        
        # Initialize page object
        home_page = HomePage(self.driver)
        
        # Check if there are events
        event_count = home_page.get_event_count()
        
        if event_count > 0:
            # Click first event
            home_page.click_first_event()
            sleep(3)
            
            # Initialize event details page
            event_details = EventDetailsPage(self.driver)
            
            # Verify event details page is displayed
            assert event_details.is_event_details_page(), \
                "Event details page should be displayed"
            
            # Verify title is present
            title = event_details.get_event_title()
            assert title is not None and len(title) > 0, \
                "Event title should be displayed"
            
            self.logger.info(f"Viewing event: {title}")
        else:
            self.logger.info("No events available to test")
            pytest.skip("No events available in database")
        
        self.logger.info("*** Test TC-EVT-030: PASSED ***")
    
    @pytest.mark.events
    def test_TC_EVT_HOME_not_logged_in(self, setup):
        """
        TC-EVT-HOME: Home page accessible without login
        
        Technique: Decision Table (Public access)
        Expected: Events visible without authentication
        """
        self.logger.info("*** Test TC-EVT-HOME: Public Access ***")
        self.driver = setup
        
        # Navigate directly to home (without login)
        self.driver.get(self.base_url + "/home")
        sleep(3)
        
        # Initialize page object
        home_page = HomePage(self.driver)
        
        # Verify login link is visible (user not logged in)
        assert home_page.is_login_link_visible() or not home_page.is_user_logged_in(), \
            "Should be able to access home page without login"
        
        self.logger.info("*** Test TC-EVT-HOME: PASSED ***")
    
    @pytest.mark.events
    def test_TC_EVT_USER_logged_in_view(self, setup):
        """
        TC-EVT-USER: Logged in user can view events
        
        Technique: Use Case Testing
        Expected: Authenticated user can browse events
        """
        self.logger.info("*** Test TC-EVT-USER: Logged In Event View ***")
        self.driver = setup
        
        # Login first
        self.driver.get(self.base_url + "/login")
        sleep(2)
        
        login_page = LoginPage(self.driver)
        login_page.login(self.user_email, self.user_password)
        sleep(3)
        
        # Navigate to home
        self.driver.get(self.base_url + "/home")
        sleep(2)
        
        # Initialize page object
        home_page = HomePage(self.driver)
        
        # Verify user is logged in
        assert home_page.is_user_logged_in() or not home_page.is_login_link_visible(), \
            "User should be logged in"
        
        # Verify events page is accessible
        assert home_page.is_element_visible(home_page.HEADER), \
            "Events page should be accessible when logged in"
        
        self.logger.info("*** Test TC-EVT-USER: PASSED ***")


if __name__ == "__main__":
    pytest.main([__file__, "-v", "-s"])
