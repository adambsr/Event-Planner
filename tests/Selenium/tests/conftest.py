"""
Pytest configuration and fixtures for Selenium tests.
Following the structure from my_web_test_project.
Uses Edge browser (pre-installed on Windows) for reliable execution.
"""

import pytest
from selenium import webdriver
from selenium.webdriver.edge.options import Options
from selenium.webdriver.edge.service import Service
import os
import sys

# Add the parent directory to path for imports
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))


@pytest.fixture(scope="function")
def setup():
    """
    Setup fixture that initializes Edge WebDriver.
    Edge is pre-installed on Windows, no additional download needed.
    Yields the driver for tests and quits after test completion.
    
    Yields:
        WebDriver: Configured Edge WebDriver instance
    """
    # Edge options
    edge_options = Options()
    edge_options.add_argument("--headless")
    edge_options.add_argument("--no-sandbox")
    edge_options.add_argument("--disable-dev-shm-usage")
    edge_options.add_argument("--window-size=1920,1080")
    edge_options.add_argument("--disable-notifications")
    
    # Suppress browser logging noise
    edge_options.add_argument("--log-level=3")  # Only fatal errors
    edge_options.add_argument("--disable-logging")
    edge_options.add_argument("--disable-gpu")
    edge_options.add_argument("--enable-features=NetworkServiceInProcess")
    edge_options.add_experimental_option("excludeSwitches", ["enable-logging", "enable-automation"])
    
    # Suppress EdgeDriver output (including DevTools message)
    service = Service(log_output=os.devnull)
    
    # Edge is pre-installed on Windows - Selenium Manager only downloads EdgeDriver
    driver = webdriver.Edge(service=service, options=edge_options)
    
    # Set implicit wait
    driver.implicitly_wait(10)
    
    yield driver
    
    # Teardown - quit driver
    driver.quit()


@pytest.fixture(scope="function")
def setup_headless():
    """
    Setup fixture for headless Edge (for CI/CD environments).
    
    Yields:
        WebDriver: Configured headless Edge WebDriver instance
    """
    edge_options = Options()
    edge_options.add_argument("--headless")
    edge_options.add_argument("--no-sandbox")
    edge_options.add_argument("--disable-dev-shm-usage")
    edge_options.add_argument("--window-size=1920,1080")
    
    # Suppress browser logging noise
    edge_options.add_argument("--log-level=3")
    edge_options.add_argument("--disable-logging")
    edge_options.add_argument("--disable-gpu")
    edge_options.add_experimental_option("excludeSwitches", ["enable-logging"])
    
    service = Service(log_output=os.devnull)
    driver = webdriver.Edge(service=service, options=edge_options)
    
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
