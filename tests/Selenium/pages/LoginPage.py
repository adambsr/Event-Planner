"""
Login Page Object for AAB_EventPlanner.
Implements Page Object Model pattern for the login functionality.

Test Level: System
Pattern: Page Object Model (POM)
Related Test Cases: TC-AUTH-001 to TC-AUTH-008
"""

from selenium.webdriver.common.by import By
from pages.BasePage import BasePage


class LoginPage(BasePage):
    """
    Page Object for the Login page.
    Contains locators and methods for login functionality.
    """

    # Locators - Element identifiers on the login page
    INPUT_EMAIL = (By.NAME, "email")
    INPUT_PASSWORD = (By.NAME, "password")
    BUTTON_LOGIN = (By.CSS_SELECTOR, "button[type='submit']")
    LINK_REGISTER = (By.CSS_SELECTOR, "a[href*='register']")
    ERROR_MESSAGE = (By.CSS_SELECTOR, ".alert-error")
    SUCCESS_MESSAGE = (By.CSS_SELECTOR, ".alert-success")
    PAGE_TITLE = (By.CSS_SELECTOR, ".auth-title")
    BRAND_NAME = (By.CSS_SELECTOR, ".auth-brand")

    def __init__(self, driver):
        """
        Initialize LoginPage.
        
        Args:
            driver: Selenium WebDriver instance
        """
        super().__init__(driver)

    def enter_email(self, email):
        """
        Enter email in the email field.
        
        Args:
            email: Email address to enter
        """
        self.enter_text(self.INPUT_EMAIL, email)

    def enter_password(self, password):
        """
        Enter password in the password field.
        
        Args:
            password: Password to enter
        """
        self.enter_text(self.INPUT_PASSWORD, password)

    def click_login_button(self):
        """
        Click the login button.
        """
        self.click_element(self.BUTTON_LOGIN)

    def click_register_link(self):
        """
        Click the register link to navigate to registration page.
        """
        self.click_element(self.LINK_REGISTER)

    def login(self, email, password):
        """
        Perform complete login action.
        
        Args:
            email: User email
            password: User password
        """
        self.enter_email(email)
        self.enter_password(password)
        self.click_login_button()

    def get_error_message(self):
        """
        Get the error message displayed on the page.
        
        Returns:
            str: Error message text, or None if not present
        """
        if self.is_element_visible(self.ERROR_MESSAGE):
            return self.get_text(self.ERROR_MESSAGE)
        return None

    def get_success_message(self):
        """
        Get the success message displayed on the page.
        
        Returns:
            str: Success message text, or None if not present
        """
        if self.is_element_visible(self.SUCCESS_MESSAGE):
            return self.get_text(self.SUCCESS_MESSAGE)
        return None

    def is_login_page(self):
        """
        Verify if current page is the login page.
        
        Returns:
            bool: True if on login page
        """
        return self.is_element_visible(self.PAGE_TITLE) and \
               "Sign In" in self.get_text(self.PAGE_TITLE)

    def is_error_displayed(self):
        """
        Check if error message is displayed.
        
        Returns:
            bool: True if error is visible
        """
        return self.is_element_visible(self.ERROR_MESSAGE)

    def is_brand_visible(self):
        """
        Check if brand name is visible.
        
        Returns:
            bool: True if brand is visible
        """
        return self.is_element_visible(self.BRAND_NAME)
