"""
Register Page Object for AAB_EventPlanner.
Implements Page Object Model pattern for the registration functionality.

Test Level: System
Pattern: Page Object Model (POM)
Related Test Cases: TC-AUTH-010 to TC-AUTH-016
"""

from selenium.webdriver.common.by import By
from pages.BasePage import BasePage


class RegisterPage(BasePage):
    """
    Page Object for the Registration page.
    Contains locators and methods for user registration functionality.
    """

    # Locators - Element identifiers on the register page
    INPUT_NAME = (By.NAME, "name")
    INPUT_EMAIL = (By.NAME, "email")
    INPUT_PASSWORD = (By.NAME, "password")
    INPUT_PASSWORD_CONFIRM = (By.NAME, "password_confirmation")
    BUTTON_REGISTER = (By.CSS_SELECTOR, "button[type='submit']")
    LINK_LOGIN = (By.CSS_SELECTOR, "a[href*='login']")
    ERROR_MESSAGE = (By.CSS_SELECTOR, ".alert-error")
    FIELD_ERROR = (By.CSS_SELECTOR, ".form-error")
    PAGE_TITLE = (By.CSS_SELECTOR, ".auth-title")
    BRAND_NAME = (By.CSS_SELECTOR, ".auth-brand")

    def __init__(self, driver):
        """
        Initialize RegisterPage.
        
        Args:
            driver: Selenium WebDriver instance
        """
        super().__init__(driver)

    def enter_name(self, name):
        """
        Enter name in the name field.
        
        Args:
            name: User's name to enter
        """
        self.enter_text(self.INPUT_NAME, name)

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

    def enter_password_confirmation(self, password):
        """
        Enter password in the confirmation field.
        
        Args:
            password: Password confirmation to enter
        """
        self.enter_text(self.INPUT_PASSWORD_CONFIRM, password)

    def click_register_button(self):
        """
        Click the register button.
        """
        self.click_element(self.BUTTON_REGISTER)

    def click_login_link(self):
        """
        Click the login link to navigate to login page.
        """
        self.click_element(self.LINK_LOGIN)

    def register(self, name, email, password, password_confirm=None):
        """
        Perform complete registration action.
        
        Args:
            name: User's name
            email: User's email
            password: User's password
            password_confirm: Password confirmation (defaults to password if not provided)
        """
        if password_confirm is None:
            password_confirm = password
            
        self.enter_name(name)
        self.enter_email(email)
        self.enter_password(password)
        self.enter_password_confirmation(password_confirm)
        self.click_register_button()

    def get_error_message(self):
        """
        Get the error message displayed on the page.
        
        Returns:
            str: Error message text, or None if not present
        """
        if self.is_element_visible(self.ERROR_MESSAGE):
            return self.get_text(self.ERROR_MESSAGE)
        return None

    def is_register_page(self):
        """
        Verify if current page is the registration page.
        
        Returns:
            bool: True if on register page
        """
        return self.is_element_visible(self.PAGE_TITLE) and \
               "Sign Up" in self.get_text(self.PAGE_TITLE)

    def is_error_displayed(self):
        """
        Check if error message is displayed.
        
        Returns:
            bool: True if error is visible
        """
        return self.is_element_visible(self.ERROR_MESSAGE)

    def has_field_errors(self):
        """
        Check if any field validation errors are displayed.
        
        Returns:
            bool: True if field errors are visible
        """
        return self.is_element_visible(self.FIELD_ERROR)
