"""
Utility module for reading configuration properties.
Based on the structure from my_web_test_project.

This module reads test configuration from config.ini file.
"""

import configparser
import os

# Get the directory of this file
current_dir = os.path.dirname(os.path.abspath(__file__))
config_path = os.path.join(current_dir, '..', 'Configurations', 'config.ini')

# Create a RawConfigParser object
config = configparser.RawConfigParser()
config.read(config_path)


class ReadConfig:
    """
    Class to read configuration parameters from config.ini file.
    Provides static methods to access various configuration values.
    """

    @staticmethod
    def get_base_url():
        """
        Get the base URL of the application under test.
        
        Returns:
            str: The base URL configured in config.ini
        """
        return config.get('common info', 'baseUrl')

    @staticmethod
    def get_admin_email():
        """
        Get admin user email.
        
        Returns:
            str: Admin email address
        """
        return config.get('admin', 'email')

    @staticmethod
    def get_admin_password():
        """
        Get admin user password.
        
        Returns:
            str: Admin password
        """
        return config.get('admin', 'password')

    @staticmethod
    def get_manager_email():
        """
        Get manager user email.
        
        Returns:
            str: Manager email address
        """
        return config.get('manager', 'email')

    @staticmethod
    def get_manager_password():
        """
        Get manager user password.
        
        Returns:
            str: Manager password
        """
        return config.get('manager', 'password')

    @staticmethod
    def get_user_email():
        """
        Get regular user email.
        
        Returns:
            str: User email address
        """
        return config.get('user', 'email')

    @staticmethod
    def get_user_password():
        """
        Get regular user password.
        
        Returns:
            str: User password
        """
        return config.get('user', 'password')

    @staticmethod
    def get_test_user_name():
        """
        Get test user name for registration tests.
        
        Returns:
            str: Test user name
        """
        return config.get('test_user', 'name')

    @staticmethod
    def get_test_user_email():
        """
        Get test user email for registration tests.
        
        Returns:
            str: Test user email
        """
        return config.get('test_user', 'email')

    @staticmethod
    def get_test_user_password():
        """
        Get test user password for registration tests.
        
        Returns:
            str: Test user password
        """
        return config.get('test_user', 'password')
