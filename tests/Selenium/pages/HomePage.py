"""
Home Page Object for AAB_EventPlanner.
Implements Page Object Model pattern for the public events listing page.

Test Level: System
Pattern: Page Object Model (POM)
Related Test Cases: TC-EVT-001, TC-EVT-010 to TC-EVT-022
"""

from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import Select
from pages.BasePage import BasePage


class HomePage(BasePage):
    """
    Page Object for the Home/Events listing page.
    Contains locators and methods for browsing events.
    """

    # Locators - Element identifiers on the home page
    SEARCH_INPUT = (By.NAME, "search")
    SEARCH_BUTTON = (By.CSS_SELECTOR, "button[type='submit']")
    CATEGORY_FILTER = (By.NAME, "category_id")
    WEEKDAY_FILTER = (By.NAME, "weekday")
    EVENT_CARDS = (By.CSS_SELECTOR, ".event-card")
    EVENT_TITLE = (By.CSS_SELECTOR, ".event-card .event-title")
    EVENT_LINK = (By.CSS_SELECTOR, ".event-card a")
    NO_EVENTS_MESSAGE = (By.CSS_SELECTOR, ".no-events")
    USER_DROPDOWN = (By.CSS_SELECTOR, ".user-dropdown")
    LOGOUT_LINK = (By.CSS_SELECTOR, "a[href*='logout']")
    LOGIN_LINK = (By.CSS_SELECTOR, "a[href*='login']")
    PAGINATION = (By.CSS_SELECTOR, ".pagination")
    HEADER = (By.CSS_SELECTOR, "header")

    def __init__(self, driver):
        """
        Initialize HomePage.
        
        Args:
            driver: Selenium WebDriver instance
        """
        super().__init__(driver)

    def search_events(self, search_term):
        """
        Search for events by keyword.
        
        Args:
            search_term: Search keyword
        """
        self.enter_text(self.SEARCH_INPUT, search_term)
        self.click_element(self.SEARCH_BUTTON)

    def filter_by_category(self, category_id):
        """
        Filter events by category.
        
        Args:
            category_id: Category ID to filter by
        """
        select = Select(self.get_element(self.CATEGORY_FILTER))
        select.select_by_value(str(category_id))

    def filter_by_weekday(self, weekday):
        """
        Filter events by weekday.
        
        Args:
            weekday: Weekday name (e.g., "Monday")
        """
        select = Select(self.get_element(self.WEEKDAY_FILTER))
        select.select_by_value(weekday)

    def get_event_count(self):
        """
        Get the number of event cards displayed.
        
        Returns:
            int: Number of events visible
        """
        if self.is_element_present(self.EVENT_CARDS):
            events = self.driver.find_elements(*self.EVENT_CARDS)
            return len(events)
        return 0

    def get_event_titles(self):
        """
        Get all visible event titles.
        
        Returns:
            list: List of event title strings
        """
        if self.is_element_present(self.EVENT_TITLE):
            titles = self.driver.find_elements(*self.EVENT_TITLE)
            return [title.text for title in titles]
        return []

    def click_first_event(self):
        """
        Click on the first event card to view details.
        """
        self.click_element(self.EVENT_LINK)

    def click_event_by_title(self, title):
        """
        Click on an event by its title.
        
        Args:
            title: Event title to click
            
        Returns:
            bool: True if event was found and clicked
        """
        events = self.driver.find_elements(*self.EVENT_CARDS)
        for event in events:
            if title in event.text:
                event.find_element(By.TAG_NAME, "a").click()
                return True
        return False

    def is_event_displayed(self, title):
        """
        Check if an event with given title is displayed.
        
        Args:
            title: Event title to look for
            
        Returns:
            bool: True if event is displayed
        """
        titles = self.get_event_titles()
        return any(title in t for t in titles)

    def is_user_logged_in(self):
        """
        Check if a user is logged in (user dropdown visible).
        
        Returns:
            bool: True if user is logged in
        """
        return self.is_element_visible(self.USER_DROPDOWN)

    def is_login_link_visible(self):
        """
        Check if login link is visible (user not logged in).
        
        Returns:
            bool: True if login link is visible
        """
        return self.is_element_visible(self.LOGIN_LINK)

    def has_pagination(self):
        """
        Check if pagination is present.
        
        Returns:
            bool: True if pagination is visible
        """
        return self.is_element_visible(self.PAGINATION)

    def no_events_displayed(self):
        """
        Check if "no events" message is displayed.
        
        Returns:
            bool: True if no events message is shown
        """
        return self.is_element_visible(self.NO_EVENTS_MESSAGE) or self.get_event_count() == 0
