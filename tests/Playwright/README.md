# Playwright Tests - AAB_EventPlanner

## Bonus Tool Implementation

This directory contains Playwright-based E2E tests as part of the bonus evaluation criteria.

### Why Playwright?

Playwright was chosen over Selenium for the following advantages:

| Feature | Playwright | Selenium |
|---------|------------|----------|
| Auto-wait | ✅ Built-in | ❌ Manual waits required |
| Multi-browser | ✅ Chrome, Firefox, Safari | ✅ Requires separate drivers |
| Driver management | ✅ Automatic | ❌ WebDriver Manager needed |
| Video recording | ✅ Built-in | ❌ External tools needed |
| Trace viewer | ✅ Interactive debugging | ❌ Not available |
| Screenshot | ✅ Auto on failure | ⚠️ Manual implementation |
| Parallel execution | ✅ Native | ⚠️ pytest-xdist required |

### Installation

```bash
cd tests/Playwright
npm install
npx playwright install
```

### Running Tests

```bash
# Run all tests
npm test

# Run with browser visible
npm run test:headed

# Run with UI mode (interactive)
npm run test:ui

# View HTML report
npm run test:report
```

### Test Coverage

| Test Case ID | Description | Scenario |
|--------------|-------------|----------|
| TC-PW-001 | Complete User Journey | Registration → Login → Event Browse |
| TC-PW-002 | Visual Regression | Login page appearance check |

### Added Value

These tests complement the existing Selenium tests by:
1. Providing cross-browser testing (Chrome, Firefox, Safari)
2. Including performance metrics collection
3. Enabling visual regression testing capability
4. Demonstrating modern testing patterns

### Team

- **Adam** - Test Implementation
- **Afra** - Test Review & Validation
