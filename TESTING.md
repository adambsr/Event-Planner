# 🧪 AAB EventPlanner - Testing & Quality Assurance

<div align="center">

![PHPUnit](https://img.shields.io/badge/PHPUnit-10.5-3C9CD7?style=for-the-badge&logo=php&logoColor=white)
![Selenium](https://img.shields.io/badge/Selenium-4.0+-43B02A?style=for-the-badge&logo=selenium&logoColor=white)
![Playwright](https://img.shields.io/badge/Playwright-Latest-2EAD33?style=for-the-badge&logo=playwright&logoColor=white)
![Pytest](https://img.shields.io/badge/Pytest-7.0+-0A9EDC?style=for-the-badge&logo=pytest&logoColor=white)

**Comprehensive Testing Documentation for AAB EventPlanner**

[Strategy](#-testing-strategy) • [Tools](#️-testing-tools) • [Running Tests](#-running-tests) • [Coverage](#-test-coverage) • [Reports](#-test-reports)

</div>

---

## 📋 Overview

This document provides comprehensive documentation for the testing and quality assurance practices implemented in AAB EventPlanner. Our testing approach follows industry best practices with a multi-layered strategy covering unit, integration, and end-to-end testing.

---

## 🎯 Testing Strategy

### Testing Pyramid

```
                    ╔═══════════════════╗
                    ║    E2E Tests      ║  ← Playwright / Selenium
                    ║   (System Level)  ║     Cross-browser validation
                    ╠═══════════════════╣
                    ║                   ║
                ╔═══╣  Integration      ╠═══╗  ← PHPUnit Feature Tests
                ║   ║     Tests         ║   ║     API & Controller testing
                ║   ╠═══════════════════╣   ║
                ║   ║                   ║   ║
            ╔═══╣   ║   Unit Tests      ║   ╠═══╗  ← PHPUnit Unit Tests
            ║   ║   ║                   ║   ║   ║     Model & Validation
            ╚═══╩═══╩═══════════════════╩═══╩═══╝
```

### Test Types

| Type | Purpose | Tools |
|------|---------|-------|
| **Unit Tests** | Test individual components in isolation | PHPUnit |
| **Integration Tests** | Test component interactions | PHPUnit Feature Tests |
| **System/E2E Tests** | Test complete user workflows | Selenium, Playwright |
| **Static Analysis** | Code review & quality checks | Manual Review, IDE Analysis |
| **Performance Tests** | Response time validation | PHPUnit Assertions |

### Testing Approach

| Technique | Application |
|-----------|-------------|
| **Black Box** | Equivalence Partitioning, Boundary Value Analysis |
| **White Box** | Code path testing, Branch coverage |
| **Use Case** | User journey validation |
| **State Transition** | Event status workflow testing |

---

## 🛠️ Testing Tools

### Backend Testing

| Tool | Version | Purpose |
|------|---------|---------|
| **PHPUnit** | ^10.5 | Unit & Integration testing |
| **Laravel Testing** | Built-in | HTTP testing, Database testing |
| **Mockery** | ^1.6 | Mocking & stubbing |
| **Faker** | ^1.23 | Test data generation |

### Frontend/E2E Testing

| Tool | Version | Purpose |
|------|---------|---------|
| **Selenium** | 4.0+ | Browser automation |
| **Playwright** | Latest | Cross-browser E2E testing |
| **Pytest** | 7.0+ | Python test runner for Selenium |
| **WebDriver Manager** | 4.0+ | Automatic driver management |

### Static Analysis

| Tool | Purpose |
|------|---------|
| **Manual Code Review** | Logic and security analysis |
| **VS Code Analysis** | Syntax and type checking |
| **Laravel Pint** | Code style enforcement |

---

## 📁 Test Directory Structure

```
tests/
├── TestCase.php              # Base test case class
├── Unit/                     # Unit tests
│   ├── EventModelTest.php    # Event model tests
│   ├── UserModelTest.php     # User model tests
│   └── ValidationRulesTest.php
├── Feature/                  # Integration tests
│   ├── AuthenticationTest.php
│   ├── CategoryTest.php
│   ├── EventTest.php
│   ├── PerformanceTest.php
│   ├── ProfileTest.php
│   └── RegistrationTest.php
├── Selenium/                 # Selenium E2E tests
│   ├── pages/               # Page Object Models
│   │   ├── BasePage.py
│   │   ├── LoginPage.py
│   │   ├── RegisterPage.py
│   │   ├── HomePage.py
│   │   └── EventDetailsPage.py
│   ├── tests/               # Test files
│   │   ├── test_001_login.py
│   │   ├── test_002_registration.py
│   │   ├── test_003_events.py
│   │   └── test_004_event_registration.py
│   ├── utilities/           # Helper utilities
│   ├── pytest.ini           # Pytest configuration
│   └── requirements.txt     # Python dependencies
├── Playwright/              # Playwright E2E tests (Bonus)
│   ├── tests/
│   │   └── user-journey.spec.ts
│   ├── playwright.config.ts
│   ├── package.json
│   └── README.md
└── Documentation/           # Test documentation
    ├── TestCases.md         # All test cases
    └── StaticTestReport.md  # Static analysis findings
```

---

## 🚀 Running Tests

### PHPUnit Tests

```bash
# Run all tests
php artisan test

# Run with verbose output
php artisan test -v

# Run specific test suite
php artisan test --testsuite=Unit
php artisan test --testsuite=Feature

# Run specific test file
php artisan test tests/Feature/AuthenticationTest.php

# Run specific test method
php artisan test --filter=test_user_can_login

# Run with code coverage
php artisan test --coverage

# Generate HTML coverage report
php artisan test --coverage-html=coverage-report
```

### Selenium Tests

```bash
# Navigate to Selenium directory
cd tests/Selenium

# Create virtual environment (recommended)
python -m venv .venv
.venv\Scripts\activate  # Windows
# source .venv/bin/activate  # Linux/Mac

# Install dependencies
pip install -r requirements.txt

# Run all Selenium tests
pytest

# Run with verbose output
pytest -v -s

# Run specific test file
pytest tests/test_001_login.py

# Run tests by marker
pytest -m smoke
pytest -m authentication
pytest -m events

# Generate HTML report
pytest --html=report.html --self-contained-html
```

### Playwright Tests

```bash
# Navigate to Playwright directory
cd tests/Playwright

# Install dependencies
npm install
npx playwright install

# Run all tests
npm test

# Run with browser visible
npm run test:headed

# Run in UI mode (interactive debugging)
npm run test:ui

# Run specific browser
npx playwright test --project=chromium
npx playwright test --project=firefox
npx playwright test --project=webkit

# Generate and view HTML report
npm run test:report
```

---

## 📊 Test Coverage

### Coverage by Module

| Module | Unit | Integration | E2E | Total Cases |
|--------|:----:|:-----------:|:---:|:-----------:|
| Authentication | ✅ | ✅ | ✅ | 21 |
| Events | ✅ | ✅ | ✅ | 15 |
| Categories | — | ✅ | ✅ | 5 |
| Registrations | — | ✅ | ✅ | 11 |
| Profile | — | ✅ | — | 4 |
| Users | — | ✅ | — | 3 |
| Security | — | ✅ | — | 3 |
| Performance | — | ✅ | — | 4 |

### Coverage by Test Level

| Level | Count | Percentage |
|-------|:-----:|:----------:|
| Unit | 10 | 13% |
| Integration | 25 | 33% |
| System | 35 | 46% |
| Performance | 4 | 5% |
| Bonus (Cross-browser) | 6 | 8% |
| **Total** | **80** | **100%** |

### Coverage by Test Type

| Type | Count |
|------|:-----:|
| Functional | 63 |
| Non-Functional (Security) | 4 |
| Non-Functional (Performance) | 4 |
| Cross-browser (Bonus) | 6 |

---

## 📝 Test Cases Summary

### Authentication Tests (AUTH)

| ID | Test Case | Priority | Status |
|----|-----------|:--------:|:------:|
| TC-AUTH-001 | Valid Login - Admin | High | ✅ |
| TC-AUTH-002 | Valid Login - Regular User | High | ✅ |
| TC-AUTH-003 | Invalid Login - Wrong Password | High | ✅ |
| TC-AUTH-004 | Invalid Login - Non-existent Email | High | ✅ |
| TC-AUTH-010 | Valid Registration | High | ✅ |
| TC-AUTH-011 | Registration - Duplicate Email | High | ✅ |
| TC-AUTH-020 | Valid Logout | High | ✅ |

### Event Tests (EVT)

| ID | Test Case | Priority | Status |
|----|-----------|:--------:|:------:|
| TC-EVT-001 | View Public Events | High | ✅ |
| TC-EVT-010 | Search by Title | Medium | ✅ |
| TC-EVT-020 | Filter by Category | Medium | ✅ |
| TC-EVT-040 | Create Event - Valid (Admin) | High | ✅ |
| TC-EVT-045 | Create Event - Non-Admin Access | High | ✅ |

### Registration Tests (REG)

| ID | Test Case | Priority | Status |
|----|-----------|:--------:|:------:|
| TC-REG-001 | Register for Event | High | ✅ |
| TC-REG-002 | Register - Event Full | High | ✅ |
| TC-REG-003 | Register - Already Registered | High | ✅ |
| TC-REG-005 | Unregister from Event | Medium | ✅ |

### Security Tests (SEC)

| ID | Test Case | Priority | Status |
|----|-----------|:--------:|:------:|
| TC-SEC-001 | Access Admin Without Auth | High | ✅ |
| TC-SEC-002 | Access Admin as User | High | ✅ |
| TC-SEC-003 | CSRF Protection | High | ✅ |

### Performance Tests (PERF)

| ID | Test Case | Threshold | Status |
|----|-----------|:---------:|:------:|
| TC-PERF-001 | Home Page Load Time | < 3s | ✅ |
| TC-PERF-002 | Login Response Time | < 2s | ✅ |
| TC-PERF-003 | Events Pagination Performance | < 3s | ✅ |
| TC-PERF-004 | Search Performance | < 3s | ✅ |

> 📋 **Full test case documentation:** [tests/Documentation/TestCases.md](tests/Documentation/TestCases.md)

---

## 📈 Test Reports

### PHPUnit Reports

```bash
# Console output (default)
php artisan test

# JUnit XML format (for CI)
php artisan test --log-junit=test-results.xml

# HTML Coverage Report
php artisan test --coverage-html=coverage-report
```

**Sample Output:**
```
   PASS  Tests\Unit\EventModelTest
  ✓ event belongs to category
  ✓ event has registrations
  ✓ event is full when capacity reached

   PASS  Tests\Feature\AuthenticationTest
  ✓ user can view login page
  ✓ user can login with correct credentials
  ✓ user cannot login with incorrect credentials

  Tests:    42 passed (128 assertions)
  Duration: 4.52s
```

### Selenium Reports

HTML reports are generated at `tests/Selenium/report.html`:

```bash
pytest --html=report.html --self-contained-html
```

**Report Contents:**
- Test results summary (passed/failed/skipped)
- Test execution duration
- Error messages and stack traces
- Screenshots on failure

### Playwright Reports

HTML reports generated at `tests/Playwright/playwright-report/`:

```bash
npx playwright show-report
```

**Features:**
- Interactive timeline view
- Video recordings on failure
- Trace viewer for debugging
- Cross-browser results comparison

---

## 📸 Screenshots & Evidence

### Selenium Screenshots

Screenshots are automatically captured on test failure:

```
tests/Selenium/
├── screenshots/
│   ├── login_failed_*.png
│   ├── registration_error_*.png
│   └── ...
└── Logs/
    └── test_execution_*.log
```

### Playwright Screenshots

```
tests/Playwright/
├── screenshots/
│   └── visual-regression/
├── test-results/
│   └── [test-name]/
│       ├── screenshot.png
│       └── video.webm
└── playwright-report/
```

---

## 🔄 CI/CD Integration

### GitHub Actions Example

```yaml
name: Tests

on: [push, pull_request]

jobs:
  phpunit:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
          coverage: xdebug
          
      - name: Install Dependencies
        run: composer install --no-progress
        
      - name: Run PHPUnit Tests
        run: php artisan test --coverage
        
  selenium:
    runs-on: ubuntu-latest
    needs: phpunit
    steps:
      - uses: actions/checkout@v4
      
      - name: Setup Python
        uses: actions/setup-python@v4
        with:
          python-version: '3.11'
          
      - name: Install Selenium Dependencies
        run: |
          cd tests/Selenium
          pip install -r requirements.txt
          
      - name: Run Selenium Tests
        run: |
          cd tests/Selenium
          pytest --html=report.html
          
  playwright:
    runs-on: ubuntu-latest
    needs: phpunit
    steps:
      - uses: actions/checkout@v4
      
      - name: Setup Node.js
        uses: actions/setup-node@v4
        with:
          node-version: '20'
          
      - name: Install Playwright
        run: |
          cd tests/Playwright
          npm ci
          npx playwright install --with-deps
          
      - name: Run Playwright Tests
        run: |
          cd tests/Playwright
          npm test
```

---

## 🔍 Static Analysis

### Code Review Findings Summary

| Severity | Count |
|----------|:-----:|
| High | 0 |
| Medium | 3 |
| Low | 7 |
| Info | 1 |

### Key Findings

| ID | Category | Severity | Status |
|----|----------|:--------:|:------:|
| F-01 | SQL Injection Risk | Low | Fixed |
| F-07 | Missing Event Status Check | Medium | To Fix |
| F-10 | Authorization Gap | Medium | To Review |

> 📋 **Full static analysis report:** [tests/Documentation/StaticTestReport.md](tests/Documentation/StaticTestReport.md)

---

## ⚠️ Known Limitations

| Area | Limitation | Workaround |
|------|------------|------------|
| Database | Tests use in-memory SQLite | Use same DB in CI for integration tests |
| File Uploads | Not fully tested in E2E | Mock storage in unit tests |
| Email | Not tested | Use array mailer for testing |
| Performance | Basic timing only | Consider dedicated load testing tools |
| Browsers | Playwright Safari requires macOS | Skip Safari in Linux CI |

---

## 📚 Test Techniques Reference

| Technique | When to Use |
|-----------|-------------|
| **Equivalence Partitioning** | Group similar inputs |
| **Boundary Value Analysis** | Test edge cases (min, max, limits) |
| **Decision Table** | Multiple conditions & combinations |
| **State Transition** | Workflow/status changes |
| **Use Case Testing** | User journey validation |

---

## 🧑‍💻 Contributing to Tests

### Writing New Tests

1. **Unit Tests:** Place in `tests/Unit/`
2. **Feature Tests:** Place in `tests/Feature/`
3. **E2E Tests:** Place in appropriate Selenium/Playwright folder
4. Follow existing naming conventions
5. Document test cases in `tests/Documentation/TestCases.md`

### Test Naming Convention

```php
// PHPUnit
public function test_user_can_login_with_valid_credentials()
public function test_event_creation_requires_title()

// Selenium/Playwright
test_001_valid_login
test_002_invalid_login_wrong_password
```

### Best Practices

- ✅ Each test should be independent
- ✅ Use factories for test data
- ✅ Clean up after tests (RefreshDatabase)
- ✅ Use descriptive test names
- ✅ One assertion per test (when possible)
- ✅ Test both success and failure paths

---

## 📞 Support

For testing-related questions or issues:

1. Check existing test documentation
2. Review test case definitions
3. Consult the team leads

---

<div align="center">

**Quality is not an act, it's a habit.**

[⬆ Back to Top](#-aab-eventplanner---testing--quality-assurance)

</div>
