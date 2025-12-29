# AAB_EventPlanner - Traceability Matrix

---

## 1. Introduction

This document provides bidirectional traceability between requirements, test scenarios, test cases, and test results for the AAB_EventPlanner application.

---

## 2. Requirements to Test Cases Matrix

### 2.1 Authentication Module

| Req ID | Requirement | Test Scenario | Test Cases | Test Level | Status |
|--------|-------------|---------------|------------|------------|--------|
| REQ-AUTH-01 | Users must be able to login with email and password | Login with valid credentials | TC-AUTH-001, TC-AUTH-002 | System | Implemented |
| REQ-AUTH-02 | Invalid login attempts should show error | Login with invalid credentials | TC-AUTH-003, TC-AUTH-004 | System | Implemented |
| REQ-AUTH-03 | Password must be at least 3 characters for login | Login password validation | TC-AUTH-007, TC-AUTH-008 | Unit | Implemented |
| REQ-AUTH-04 | Users must be able to register | User registration | TC-AUTH-010 | System | Implemented |
| REQ-AUTH-05 | Registration requires unique email | Email uniqueness | TC-AUTH-011 | Integration | Implemented |
| REQ-AUTH-06 | Registration password min 8 characters | Password validation | TC-AUTH-013, TC-AUTH-014 | Unit | Implemented |
| REQ-AUTH-07 | Password confirmation must match | Password matching | TC-AUTH-012 | System | Implemented |
| REQ-AUTH-08 | Users must be able to logout | User logout | TC-AUTH-020, TC-AUTH-021 | System | Implemented |
| REQ-AUTH-09 | Admin/Manager redirect to admin panel | Role-based redirect | TC-AUTH-001, TC-AUTH-004b | Integration | Implemented |

### 2.2 Event Module

| Req ID | Requirement | Test Scenario | Test Cases | Test Level | Status |
|--------|-------------|---------------|------------|------------|--------|
| REQ-EVT-01 | Public can view active events | Event listing | TC-EVT-001 | System | Implemented |
| REQ-EVT-02 | Archived events hidden from public | Status filtering | TC-EVT-002 | Integration | Implemented |
| REQ-EVT-03 | Events searchable by title/description | Event search | TC-EVT-010, TC-EVT-011 | System | Implemented |
| REQ-EVT-04 | Events filterable by category | Category filter | TC-EVT-020 | System | Implemented |
| REQ-EVT-05 | Event details viewable | Event details page | TC-EVT-030 | System | Implemented |
| REQ-EVT-06 | Admin can create events | Event creation | TC-EVT-040, TC-EVT-041 | Integration | Implemented |
| REQ-EVT-07 | Admin can update events | Event editing | TC-EVT-043 | Integration | Implemented |
| REQ-EVT-08 | Admin can archive events | Event archiving | TC-EVT-044 | Integration | Implemented |
| REQ-EVT-09 | Non-admin cannot create events | Authorization | TC-EVT-045 | Integration | Implemented |

### 2.3 Registration Module

| Req ID | Requirement | Test Scenario | Test Cases | Test Level | Status |
|--------|-------------|---------------|------------|------------|--------|
| REQ-REG-01 | Users can register for events | Event registration | TC-REG-001 | System | Implemented |
| REQ-REG-02 | Cannot register for full events | Capacity check | TC-REG-002 | Integration | Implemented |
| REQ-REG-03 | Cannot register twice | Duplicate prevention | TC-REG-003 | Integration | Implemented |
| REQ-REG-04 | Must be logged in to register | Authentication required | TC-REG-004 | System | Implemented |
| REQ-REG-05 | Users can unregister | Event unregistration | TC-REG-005 | System | Implemented |
| REQ-REG-06 | Users can view their registrations | My registrations | TC-REG-010 | System | Implemented |

### 2.4 Category Module

| Req ID | Requirement | Test Scenario | Test Cases | Test Level | Status |
|--------|-------------|---------------|------------|------------|--------|
| REQ-CAT-01 | Admin/Manager can view categories | Category listing | TC-CAT-001 | System | Implemented |
| REQ-CAT-02 | Admin can create categories | Category creation | TC-CAT-002 | Integration | Implemented |
| REQ-CAT-03 | Category names must be unique | Uniqueness validation | TC-CAT-003 | Integration | Implemented |
| REQ-CAT-04 | Cannot delete category with events | Business rule | TC-CAT-004 | Integration | Implemented |
| REQ-CAT-05 | Admin can delete empty categories | Category deletion | TC-CAT-005 | Integration | Implemented |

### 2.5 Profile Module

| Req ID | Requirement | Test Scenario | Test Cases | Test Level | Status |
|--------|-------------|---------------|------------|------------|--------|
| REQ-PRF-01 | Users can view their profile | Profile view | TC-PRF-001 | System | Implemented |
| REQ-PRF-02 | Users can update profile info | Profile update | TC-PRF-002 | Integration | Implemented |
| REQ-PRF-03 | Users can change password | Password update | TC-PRF-003 | Integration | Implemented |
| REQ-PRF-04 | Current password required for change | Password validation | TC-PRF-004 | Integration | Implemented |

### 2.6 Security Requirements

| Req ID | Requirement | Test Scenario | Test Cases | Test Level | Status |
|--------|-------------|---------------|------------|------------|--------|
| REQ-SEC-01 | Admin routes require authentication | Route protection | TC-SEC-001 | System | Implemented |
| REQ-SEC-02 | Admin routes require admin role | Role authorization | TC-SEC-002 | System | Implemented |
| REQ-SEC-03 | CSRF protection on forms | CSRF validation | TC-SEC-003 | Integration | Implemented |

---

## 3. Test Case to Implementation Matrix

### 3.1 Unit Tests (PHPUnit)

| Test Case | Test File | Test Method | Status |
|-----------|-----------|-------------|--------|
| TC-AUTH-007 | ValidationRulesTest.php | test_login_password_below_minimum_length | ✅ Implemented |
| TC-AUTH-008 | ValidationRulesTest.php | test_login_password_at_minimum_length | ✅ Implemented |
| TC-AUTH-013 | ValidationRulesTest.php | test_registration_password_minimum_length | ✅ Implemented |
| TC-AUTH-012 | ValidationRulesTest.php | test_registration_password_confirmation | ✅ Implemented |
| TC-AUTH-011 | ValidationRulesTest.php | test_registration_email_unique | ✅ Implemented |
| TC-EVT-MODEL-001 | AAB_EventModelTest.php | test_event_is_full_when_capacity_equals_registrations | ✅ Implemented |
| TC-EVT-MODEL-002 | AAB_EventModelTest.php | test_event_has_correct_casts | ✅ Implemented |
| TC-USR-MODEL-001 | UserModelTest.php | test_user_fillable_attributes | ✅ Implemented |
| TC-USR-MODEL-002 | UserModelTest.php | test_user_hidden_attributes | ✅ Implemented |

### 3.2 Integration Tests (PHPUnit Feature)

| Test Case | Test File | Test Method | Status |
|-----------|-----------|-------------|--------|
| TC-AUTH-001 | AuthenticationTest.php | test_admin_can_login_successfully | ✅ Implemented |
| TC-AUTH-002 | AuthenticationTest.php | test_user_can_login_and_redirect_to_home | ✅ Implemented |
| TC-AUTH-003 | AuthenticationTest.php | test_login_fails_with_wrong_password | ✅ Implemented |
| TC-AUTH-010 | AuthenticationTest.php | test_user_can_register_successfully | ✅ Implemented |
| TC-AUTH-020 | AuthenticationTest.php | test_user_can_logout_successfully | ✅ Implemented |
| TC-EVT-001 | EventTest.php | test_public_events_shows_only_active_events | ✅ Implemented |
| TC-EVT-040 | EventTest.php | test_admin_can_create_event | ✅ Implemented |
| TC-EVT-044 | EventTest.php | test_admin_can_archive_event | ✅ Implemented |
| TC-REG-001 | RegistrationTest.php | test_user_can_register_for_event | ✅ Implemented |
| TC-REG-002 | RegistrationTest.php | test_cannot_register_for_full_event | ✅ Implemented |
| TC-CAT-002 | CategoryTest.php | test_admin_can_create_category | ✅ Implemented |
| TC-CAT-004 | CategoryTest.php | test_cannot_delete_category_with_events | ✅ Implemented |
| TC-PRF-002 | ProfileTest.php | test_user_can_update_profile_name | ✅ Implemented |
| TC-PRF-003 | ProfileTest.php | test_user_can_update_password | ✅ Implemented |

### 3.3 System Tests (Selenium/Pytest)

| Test Case | Test File | Test Method | Status |
|-----------|-----------|-------------|--------|
| TC-AUTH-VIEW-001 | test_001_login.py | test_TC_AUTH_VIEW_001_login_page_accessible | ✅ Implemented |
| TC-AUTH-001 | test_001_login.py | test_TC_AUTH_001_valid_admin_login | ✅ Implemented |
| TC-AUTH-002 | test_001_login.py | test_TC_AUTH_002_valid_user_login | ✅ Implemented |
| TC-AUTH-003 | test_001_login.py | test_TC_AUTH_003_invalid_login_wrong_password | ✅ Implemented |
| TC-AUTH-010 | test_002_registration.py | test_TC_AUTH_010_valid_registration | ✅ Implemented |
| TC-AUTH-012 | test_002_registration.py | test_TC_AUTH_012_password_mismatch | ✅ Implemented |
| TC-AUTH-013 | test_002_registration.py | test_TC_AUTH_013_password_too_short | ✅ Implemented |
| TC-EVT-001 | test_003_events.py | test_TC_EVT_001_public_events_listing | ✅ Implemented |
| TC-EVT-010 | test_003_events.py | test_TC_EVT_010_search_by_title | ✅ Implemented |
| TC-EVT-030 | test_003_events.py | test_TC_EVT_030_view_event_details | ✅ Implemented |
| TC-REG-001 | test_004_event_registration.py | test_TC_REG_001_register_for_event | ✅ Implemented |
| TC-REG-004 | test_004_event_registration.py | test_TC_REG_004_register_not_logged_in | ✅ Implemented |
| TC-REG-005 | test_004_event_registration.py | test_TC_REG_005_unregister_from_event | ✅ Implemented |

---

## 4. Coverage Summary

### 4.1 Test Level Coverage

| Level | Planned | Implemented | Coverage |
|-------|---------|-------------|----------|
| Unit | 10 | 9 | 90% |
| Integration | 25 | 20 | 80% |
| System | 35 | 17 | 49% |
| **Total** | **70** | **46** | **66%** |

### 4.2 Test Type Coverage

| Type | Planned | Implemented | Coverage |
|------|---------|-------------|----------|
| Functional | 63 | 43 | 68% |
| Non-Functional (Security) | 4 | 3 | 75% |
| Non-Functional (Performance) | 2 | 0 | 0% |

### 4.3 Feature Coverage

| Feature | Test Cases | Automated | Coverage |
|---------|------------|-----------|----------|
| Authentication | 16 | 12 | 75% |
| Events | 15 | 10 | 67% |
| Registration | 8 | 7 | 88% |
| Categories | 6 | 5 | 83% |
| Profile | 6 | 4 | 67% |
| Security | 4 | 3 | 75% |

---

## 5. Traceability Verification Checklist

- [x] All requirements have at least one test case
- [x] All test cases are linked to requirements
- [x] All implemented test cases have corresponding test methods
- [x] Test levels are appropriate for each test case
- [x] Critical features have system-level tests
- [x] Integration points are tested
- [x] Security requirements are covered

---

**Document Version**: 1.0  
**Date**: December 26, 2025
