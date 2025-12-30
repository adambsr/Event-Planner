import { defineConfig, devices } from '@playwright/test';

/**
 * Playwright Configuration for AAB_EventPlanner
 * 
 * Bonus Tool Implementation
 * This configuration provides:
 * - Multi-browser testing (Chrome, Firefox, Safari)
 * - Auto-wait capabilities
 * - Built-in screenshot and video recording
 * - Parallel test execution
 * - HTML reporting
 */
export default defineConfig({
  testDir: './tests',
  
  /* Run tests in files in parallel */
  fullyParallel: true,
  
  /* Fail the build on CI if you accidentally left test.only in the source code */
  forbidOnly: !!process.env.CI,
  
  /* Retry on CI only */
  retries: process.env.CI ? 2 : 0,
  
  /* Reporter to use */
  reporter: [
    ['html', { outputFolder: 'playwright-report' }],
    ['list']
  ],
  
  /* Shared settings for all the projects below */
  use: {
    /* Base URL for the application */
    baseURL: 'http://127.0.0.1:8000',
    
    /* Collect trace when retrying the failed test */
    trace: 'on-first-retry',
    
    /* Capture screenshot on failure */
    screenshot: 'only-on-failure',
    
    /* Record video on failure */
    video: 'on-first-retry',
  },

  /* Configure projects for major browsers */
  projects: [
    {
      name: 'chromium',
      use: { ...devices['Desktop Chrome'] },
    },
    {
      name: 'firefox',
      use: { ...devices['Desktop Firefox'] },
    },
    {
      name: 'webkit',
      use: { ...devices['Desktop Safari'] },
    },
  ],

  /* Timeout settings */
  timeout: 30000,
  expect: {
    timeout: 5000
  },
});
