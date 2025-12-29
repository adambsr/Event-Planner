"""
Event Details Page Object for AAB_EventPlanner.
Implements Page Object Model pattern for viewing and interacting with event details.

Test Level: System
Pattern: Page Object Model (POM)
Related Test Cases: TC-EVT-030, TC-REG-001 to TC-REG-006
"""

from selenium.webdriver.common.by import By
from pages.BasePage import BasePage


class EventDetailsPage(BasePage):
    """
    Page Object for the Event Details page.
    Contains locators and methods for viewing event details and registration.
    """

    # Locators - Element identifiers on the event details page
    EVENT_TITLE = (By.CSS_SELECTOR, ".event-title, h1")
    EVENT_DESCRIPTION = (By.CSS_SELECTOR, ".event-description")
    EVENT_DATE = (By.CSS_SELECTOR, ".event-date")
    EVENT_PLACE = (By.CSS_SELECTOR, ".event-place")
    EVENT_PRICE = (By.CSS_SELECTOR, ".event-price")
    EVENT_CAPACITY = (By.CSS_SELECTOR, ".event-capacity")
    EVENT_CATEGORY = (By.CSS_SELECTOR, ".event-category")
    REGISTER_BUTTON = (By.CSS_SELECTOR, "button[type='submit'], form[action*='register'] button")
    UNREGISTER_BUTTON = (By.CSS_SELECTOR, "form[action*='unregister'] button")
    SUCCESS_MESSAGE = (By.CSS_SELECTOR, ".alert-success")
    ERROR_MESSAGE = (By.CSS_SELECTOR, ".alert-error")
    REGISTRATION_STATUS = (By.CSS_SELECTOR, ".registration-status")
    BACK_LINK = (By.CSS_SELECTOR, "a[href*='home'], a[href*='events']")

    def __init__(self, driver):
        """
        Initialize EventDetailsPage.
        
        Args:
            driver: Selenium WebDriver instance
        """
        super().__init__(driver)

    def get_event_title(self):
        """
        Get the event title.
        
        Returns:
            str: Event title text
        """
        return self.get_text(self.EVENT_TITLE)

    def get_event_description(self):
        """
        Get the event description.
        
        Returns:
            str: Event description text
        """
        if self.is_element_visible(self.EVENT_DESCRIPTION):
            return self.get_text(self.EVENT_DESCRIPTION)
        return ""

    def get_event_place(self):
        """
        Get the event location.
        
        Returns:
            str: Event place/location
        """
        if self.is_element_visible(self.EVENT_PLACE):
            return self.get_text(self.EVENT_PLACE)
        return ""

    def click_register_button(self):
        """
        Click the register button to register for the event.
        """
        self.click_element(self.REGISTER_BUTTON)

    def click_unregister_button(self):
        """
        Click the unregister button to cancel registration.
        """
        self.click_element(self.UNREGISTER_BUTTON)

    def is_register_button_visible(self):
        """
        Check if register button is visible.
        
        Returns:
            bool: True if register button is visible
        """
        return self.is_element_visible(self.REGISTER_BUTTON)

    def is_unregister_button_visible(self):
        """
        Check if unregister button is visible.
        
        Returns:
            bool: True if unregister button is visible
        """
        return self.is_element_visible(self.UNREGISTER_BUTTON)

    def get_success_message(self):
        """
        Get the success message displayed after action.
        
        Returns:
            str: Success message text, or None if not present
        """
        if self.is_element_visible(self.SUCCESS_MESSAGE):
            return self.get_text(self.SUCCESS_MESSAGE)
        return None

    def get_error_message(self):
        """
        Get the error message displayed.
        
        Returns:
            str: Error message text, or None if not present
        """
        if self.is_element_visible(self.ERROR_MESSAGE):
            return self.get_text(self.ERROR_MESSAGE)
        return None

    def is_registered(self):
        """
        Check if user is registered for this event.
        
        Returns:
            bool: True if user is registered (unregister button visible)
        """
        return self.is_unregister_button_visible()

    def click_back(self):
        """
        Click back link to return to events list.
        """
        self.click_element(self.BACK_LINK)

    def is_event_details_page(self):
        """
        Verify if current page is an event details page.
        
        Returns:
            bool: True if on event details page
        """
        return self.is_element_visible(self.EVENT_TITLE)
