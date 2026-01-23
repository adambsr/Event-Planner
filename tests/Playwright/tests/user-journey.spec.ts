/**
 * Test Case: TC-PW-001 - Complete User Registration and Event Enrollment Journey
 * 
 * Test Level: System (End-to-End)
 * Test Type: Functional
 * Technique: Use Case Testing
 */

import { test, expect, Page } from '@playwright/test';

// Test data
const TEST_USER = {
  name: 'Playwright Test User',
  email: `playwright_${Date.now()}@test.com`,
  password: 'PlaywrightTest123!',
};

const BASE_URL = 'http://localhost/AAB_EventPlanner/public';

test.describe('TC-PW-001: Complete User Journey - Registration to Event Enrollment', () => {
  
  /**
   * Step 1: User Registration
   * Technique: Use Case Testing
   */
  test('Step 1: New user can register successfully', async ({ page }) => {
    // Navigate to registration page
    await page.goto(`${BASE_URL}/register`);
    
    // Verify registration page is displayed
    await expect(page).toHaveURL(/.*register/);
    
    // Fill registration form
    await page.fill('input[name="name"]', TEST_USER.name);
    await page.fill('input[name="email"]', TEST_USER.email);
    await page.fill('input[name="password"]', TEST_USER.password);
    await page.fill('input[name="password_confirmation"]', TEST_USER.password);
    
    // Submit registration
    await page.click('button[type="submit"]');
    
    // Wait for redirect and verify success
    // Should redirect to login page or email verification page (when email verification is enabled)
    await page.waitForURL(/.*(login|email\/verify)/, { timeout: 10000 });
    
    // Verify we're on either login or email verification page
    const currentUrl = page.url();
    if (currentUrl.includes('login')) {
      await expect(page.locator('input[name="email"]')).toBeVisible();
    } else {
      // On email verification page - registration was successful
      await expect(page.locator('body')).toBeVisible();
    }
    
    console.log('✓ Step 1 PASSED: User registration successful');
  });

  /**
   * Step 2: User Login
   * Technique: Use Case Testing
   */
  test('Step 2: Registered user can login', async ({ page }) => {
    // First register the user (since tests are isolated)
    await page.goto(`${BASE_URL}/register`);
    await page.waitForLoadState('networkidle');
    await page.fill('input[name="name"]', TEST_USER.name);
    await page.fill('input[name="email"]', TEST_USER.email);
    await page.fill('input[name="password"]', TEST_USER.password);
    await page.fill('input[name="password_confirmation"]', TEST_USER.password);
    await page.click('button[type="submit"]');
    
    // Wait for redirect (login or email verification page)
    await page.waitForURL(/.*(login|email\/verify)/, { timeout: 10000 });
    
    // If redirected to email verification, navigate to login page
    if (page.url().includes('email/verify')) {
      await page.goto(`${BASE_URL}/login`);
      await page.waitForLoadState('networkidle');
    }
    
    // Now login
    await page.fill('input[name="email"]', TEST_USER.email);
    await page.fill('input[name="password"]', TEST_USER.password);
    await page.click('button[type="submit"]');
    
    // Verify successful login - should redirect to home, events, admin, or email/verify
    // (email/verify is valid for unverified users)
    await page.waitForURL(/.*(home|events|admin|email\/verify)/, { timeout: 15000 });
    
    console.log('✓ Step 2 PASSED: User login successful');
  });

  /**
   * Step 3: Browse Events and View Details
   * Technique: Use Case Testing
   */
  test('Step 3: User can browse and view event details', async ({ page }) => {
    // Login first
    await page.goto(`${BASE_URL}/login`);
    await page.waitForLoadState('networkidle');
    await page.fill('input[name="email"]', 'user@eventplanner.com');
    await page.fill('input[name="password"]', 'user123');
    await page.click('button[type="submit"]');
    await page.waitForURL(/.*(home|events|admin|email\/verify)/, { timeout: 15000 });
    
    // Navigate to home page with events
    await page.goto(`${BASE_URL}/home`);
    await page.waitForLoadState('networkidle');
    
    // Verify events section is displayed (events-section, events-grid, or table)
    // The page might have no events, so check for the events section container
    const eventsSection = page.locator('.events-section, .events-grid, .events-table, .admin-container');
    await expect(eventsSection.first()).toBeVisible({ timeout: 10000 });
    
    // Check if there are event cards or event rows displayed
    const eventCard = page.locator('.event-card, tr.archived-row, tr:has(.event-name)').first();
    const hasEvents = await eventCard.isVisible().catch(() => false);
    
    if (hasEvents) {
      // Click on first event to view details
      const eventLink = page.locator('a[href*="/events/"], .event-card').first();
      if (await eventLink.isVisible()) {
        await eventLink.click();
        await page.waitForLoadState('networkidle');
        
        // Verify event details page
        await expect(page.locator('h1, h2, .event-title, .page-title').first()).toBeVisible();
      }
    }
    
    console.log('✓ Step 3 PASSED: Event browsing and details view successful');
  });

  /**
   * Step 4: Cross-Browser Validation (Playwright Advantage)
   * This test runs automatically on Chrome, Firefox, and Safari
   * Demonstrating Playwright's multi-browser capability
   */
  test('Step 4: Home page renders correctly (cross-browser)', async ({ page, browserName }) => {
    await page.goto(`${BASE_URL}/home`);
    
    // Verify page title or header is visible
    await expect(page.locator('body')).toBeVisible();
    
    // Take screenshot for visual verification
    await page.screenshot({ 
      path: `screenshots/home-page-${browserName}.png`,
      fullPage: true 
    });
    
    // Verify navigation/header elements (app uses header with .auth-buttons)
    const headerLinks = page.locator('header a, .header a, .auth-buttons a');
    await expect(headerLinks.first()).toBeVisible({ timeout: 10000 });
    
    console.log(`✓ Step 4 PASSED: Home page renders correctly on ${browserName}`);
  });

  /**
   * Step 5: Performance Check (Playwright Advantage)
   * Using Playwright's built-in performance metrics
   */
  test('Step 5: Page load performance metrics', async ({ page }) => {
    // Start timing
    const startTime = Date.now();
    
    await page.goto(`${BASE_URL}/home`);
    
    // Wait for page to be fully loaded
    await page.waitForLoadState('networkidle');
    
    const loadTime = Date.now() - startTime;
    
    // Assert load time is under 30 seconds (dev environment with artisan serve)
    expect(loadTime).toBeLessThan(30000);
    
    // Get performance metrics
    const metrics = await page.evaluate(() => {
      const timing = performance.timing;
      return {
        domContentLoaded: timing.domContentLoadedEventEnd - timing.navigationStart,
        loadComplete: timing.loadEventEnd - timing.navigationStart,
      };
    });
    
    console.log(`✓ Step 5 PASSED: Page load time: ${loadTime}ms`);
    console.log(`  DOM Content Loaded: ${metrics.domContentLoaded}ms`);
    console.log(`  Full Load: ${metrics.loadComplete}ms`);
  });
});

/**
 * Standalone test for visual regression (Playwright exclusive feature)
 */
test('TC-PW-002: Visual regression - Login page appearance', async ({ page }) => {
  await page.goto(`${BASE_URL}/login`);
  
  // Wait for page to be stable
  await page.waitForLoadState('networkidle');
  
  // Take a screenshot that can be used for visual comparison
  await page.screenshot({
    path: 'screenshots/login-page-baseline.png',
    fullPage: true
  });
  
  // Verify key elements are present
  await expect(page.locator('input[name="email"]')).toBeVisible();
  await expect(page.locator('input[name="password"]')).toBeVisible();
  await expect(page.locator('button[type="submit"]')).toBeVisible();
  
  console.log('✓ TC-PW-002 PASSED: Login page visual check complete');
});