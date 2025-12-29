"""
Pytest configuration and fixtures for Selenium tests.
Following the structure from my_web_test_project.
"""

import pytest
from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.chrome.options import Options
from webdriver_manager.chrome import ChromeDriverManager
import os
import sys

# Add the parent directory to path for imports
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))


@pytest.fixture(scope="function")
def setup():
    """
    Setup fixture that initializes Chrome WebDriver.
    Yields the driver for tests and quits after test completion.
    
    Yields:
        WebDriver: Configured Chrome WebDriver instance
    """
    # Chrome options
    chrome_options = Options()
    # Uncomment below for headless mode
    # chrome_options.add_argument("--headless")
    chrome_options.add_argument("--start-maximized")
    chrome_options.add_argument("--disable-notifications")
    
    # Initialize driver with WebDriver Manager
    driver = webdriver.Chrome(
        service=Service(ChromeDriverManager().install()),
        options=chrome_options
    )
    
    # Set implicit wait
    driver.implicitly_wait(10)
    
    yield driver
    
    # Teardown - quit driver
    driver.quit()


@pytest.fixture(scope="function")
def setup_headless():
    """
    Setup fixture for headless Chrome (for CI/CD environments).
    
    Yields:
        WebDriver: Configured headless Chrome WebDriver instance
    """
    chrome_options = Options()
    chrome_options.add_argument("--headless")
    chrome_options.add_argument("--no-sandbox")
    chrome_options.add_argument("--disable-dev-shm-usage")
    chrome_options.add_argument("--window-size=1920,1080")
    
    driver = webdriver.Chrome(
        service=Service(ChromeDriverManager().install()),
        options=chrome_options
    )
    
    driver.implicitly_wait(10)
    
    yield driver
    
    driver.quit()


def pytest_configure(config):
    """
    Configure pytest with custom markers.
    """
    config.addinivalue_line(
        "markers", "smoke: mark test as smoke test"
    )
    config.addinivalue_line(
        "markers", "regression: mark test as regression test"
    )
    config.addinivalue_line(
        "markers", "authentication: mark test as authentication test"
    )
    config.addinivalue_line(
        "markers", "events: mark test as events test"
    )
    config.addinivalue_line(
        "markers", "registration: mark test as registration test"
    )
