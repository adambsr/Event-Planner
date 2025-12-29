"""
Custom Logger utility for test reporting.
Creates log files for test execution results.
"""

import logging
import os
from datetime import datetime


class CustomLogger:
    """
    Custom logger class for creating and managing test logs.
    """

    @staticmethod
    def get_logger(name="AAB_EventPlanner_Tests"):
        """
        Create and return a logger instance.
        
        Args:
            name: Logger name (default: AAB_EventPlanner_Tests)
            
        Returns:
            logging.Logger: Configured logger instance
        """
        # Create logs directory if it doesn't exist
        logs_dir = os.path.join(os.path.dirname(os.path.abspath(__file__)), '..', 'Logs')
        if not os.path.exists(logs_dir):
            os.makedirs(logs_dir)

        # Create log filename with timestamp
        timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
        log_file = os.path.join(logs_dir, f"test_log_{timestamp}.log")

        # Configure logger
        logger = logging.getLogger(name)
        logger.setLevel(logging.DEBUG)

        # Prevent duplicate handlers
        if not logger.handlers:
            # File handler
            file_handler = logging.FileHandler(log_file)
            file_handler.setLevel(logging.DEBUG)

            # Console handler
            console_handler = logging.StreamHandler()
            console_handler.setLevel(logging.INFO)

            # Formatter
            formatter = logging.Formatter(
                '%(asctime)s - %(name)s - %(levelname)s - %(message)s',
                datefmt='%Y-%m-%d %H:%M:%S'
            )
            file_handler.setFormatter(formatter)
            console_handler.setFormatter(formatter)

            # Add handlers
            logger.addHandler(file_handler)
            logger.addHandler(console_handler)

        return logger
