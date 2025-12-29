"""
Base Page class for Page Object Model.
Contains common methods and setup shared across all page objects.

Test Level: System
Pattern: Page Object Model (POM)
"""

from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.common.by import By
from selenium.common.exceptions import TimeoutException, NoSuchElementException


class BasePage:
    """
    Base class for all Page Objects.
    Contains common methods for interacting with web pages.
    """

    def __init__(self, driver):
        """
        Initialize the base page.
        
        Args:
            driver: Selenium WebDriver instance
        """
        self.driver = driver
        self.wait = WebDriverWait(driver, 10)

    def get_element(self, locator):
        """
        Wait for and return an element.
        
        Args:
            locator: Tuple of (By, value)
            
        Returns:
            WebElement: The found element
        """
        return self.wait.until(EC.presence_of_element_located(locator))

    def get_clickable_element(self, locator):
        """
        Wait for and return a clickable element.
        
        Args:
            locator: Tuple of (By, value)
            
        Returns:
            WebElement: The clickable element
        """
        return self.wait.until(EC.element_to_be_clickable(locator))

    def click_element(self, locator):
        """
        Wait for element to be clickable and click it.
        
        Args:
            locator: Tuple of (By, value)
        """
        element = self.get_clickable_element(locator)
        element.click()

    def enter_text(self, locator, text):
        """
        Clear field and enter text.
        
        Args:
            locator: Tuple of (By, value)
            text: Text to enter
        """
        element = self.get_element(locator)
        element.clear()
        element.send_keys(text)

    def get_text(self, locator):
        """
        Get text content of an element.
        
        Args:
            locator: Tuple of (By, value)
            
        Returns:
            str: Text content
        """
        element = self.get_element(locator)
        return element.text

    def is_element_present(self, locator, timeout=5):
        """
        Check if element is present on page.
        
        Args:
            locator: Tuple of (By, value)
            timeout: Wait timeout in seconds
            
        Returns:
            bool: True if element is present
        """
        try:
            WebDriverWait(self.driver, timeout).until(
                EC.presence_of_element_located(locator)
            )
            return True
        except TimeoutException:
            return False

    def is_element_visible(self, locator, timeout=5):
        """
        Check if element is visible on page.
        
        Args:
            locator: Tuple of (By, value)
            timeout: Wait timeout in seconds
            
        Returns:
            bool: True if element is visible
        """
        try:
            WebDriverWait(self.driver, timeout).until(
                EC.visibility_of_element_located(locator)
            )
            return True
        except TimeoutException:
            return False

    def get_current_url(self):
        """
        Get current page URL.
        
        Returns:
            str: Current URL
        """
        return self.driver.current_url

    def get_page_title(self):
        """
        Get current page title.
        
        Returns:
            str: Page title
        """
        return self.driver.title

    def take_screenshot(self, filename):
        """
        Take a screenshot of current page.
        
        Args:
            filename: Screenshot filename
        """
        self.driver.save_screenshot(filename)

    def wait_for_url_contains(self, text, timeout=10):
        """
        Wait until URL contains specified text.
        
        Args:
            text: Text to look for in URL
            timeout: Wait timeout
            
        Returns:
            bool: True if URL contains text
        """
        try:
            WebDriverWait(self.driver, timeout).until(
                EC.url_contains(text)
            )
            return True
        except TimeoutException:
            return False
