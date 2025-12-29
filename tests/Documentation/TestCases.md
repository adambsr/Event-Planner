# AAB_EventPlanner - Test Cases Documentation

---

## 1. Test Case Overview

This document contains all test cases organized by module and test level. Each test case includes:
- Test technique used
- Test level (Unit/Integration/System)
- Test type (Functional/Non-Functional)
- Preconditions, steps, and expected results

---

## 2. Authentication Module Test Cases

### 2.1 Login Feature (AUTH-001)

| TC ID | Test Case | Technique | Level | Type | Preconditions | Test Steps | Expected Result | Priority |
|-------|-----------|-----------|-------|------|---------------|------------|-----------------|----------|
| TC-AUTH-001 | Valid Login - Admin | Equivalence Partitioning | System | Functional | User exists with admin role | 1. Navigate to /login<br>2. Enter email: admin@eventplanner.com<br>3. Enter password: admin123<br>4. Click Login | User redirected to /admin/events | High |
| TC-AUTH-002 | Valid Login - Regular User | Equivalence Partitioning | System | Functional | User exists with user role | 1. Navigate to /login<br>2. Enter email: user@eventplanner.com<br>3. Enter password: user123<br>4. Click Login | User redirected to /home | High |
| TC-AUTH-003 | Invalid Login - Wrong Password | Equivalence Partitioning | System | Functional | User exists | 1. Navigate to /login<br>2. Enter valid email<br>3. Enter wrong password<br>4. Click Login | Error message "Invalid email or password" | High |
| TC-AUTH-004 | Invalid Login - Non-existent Email | Equivalence Partitioning | System | Functional | None | 1. Navigate to /login<br>2. Enter non-existent email<br>3. Enter any password<br>4. Click Login | Error message displayed | High |
| TC-AUTH-005 | Invalid Login - Empty Fields | Boundary Value Analysis | System | Functional | None | 1. Navigate to /login<br>2. Leave fields empty<br>3. Click Login | Validation error for required fields | Medium |
| TC-AUTH-006 | Invalid Login - Invalid Email Format | Equivalence Partitioning | System | Functional | None | 1. Navigate to /login<br>2. Enter "notanemail"<br>3. Enter password<br>4. Click Login | Email validation error | Medium |
| TC-AUTH-007 | Password Min Length | Boundary Value Analysis | Unit | Functional | None | Password with 2 characters | Validation fails (min 3) | Medium |
| TC-AUTH-008 | Password At Min Length | Boundary Value Analysis | Unit | Functional | None | Password with 3 characters | Validation passes | Medium |

### 2.2 Registration Feature (AUTH-002)

| TC ID | Test Case | Technique | Level | Type | Preconditions | Test Steps | Expected Result | Priority |
|-------|-----------|-----------|-------|------|---------------|------------|-----------------|----------|
| TC-AUTH-010 | Valid Registration | Equivalence Partitioning | System | Functional | Email not in use | 1. Navigate to /register<br>2. Enter valid name<br>3. Enter unique email<br>4. Enter matching passwords (8+ chars)<br>5. Click Register | Success message, redirect to login | High |
| TC-AUTH-011 | Registration - Duplicate Email | Equivalence Partitioning | Integration | Functional | Email already exists | 1. Navigate to /register<br>2. Enter existing email<br>3. Complete form<br>4. Click Register | Error "Email already taken" | High |
| TC-AUTH-012 | Registration - Password Mismatch | Equivalence Partitioning | System | Functional | None | 1. Enter different passwords in password and confirm fields | Validation error for password confirmation | High |
| TC-AUTH-013 | Registration - Password Too Short | Boundary Value Analysis | Unit | Functional | None | Enter password with 7 characters | Validation fails (min 8) | Medium |
| TC-AUTH-014 | Registration - Password At Min Length | Boundary Value Analysis | Unit | Functional | None | Enter password with 8 characters | Validation passes | Medium |
| TC-AUTH-015 | Registration - Empty Name | Equivalence Partitioning | Unit | Functional | None | Leave name field empty | Validation error | Medium |
| TC-AUTH-016 | Registration - Name Max Length | Boundary Value Analysis | Unit | Functional | None | Enter name with 256 characters | Validation fails (max 255) | Low |

### 2.3 Logout Feature (AUTH-003)

| TC ID | Test Case | Technique | Level | Type | Preconditions | Test Steps | Expected Result | Priority |
|-------|-----------|-----------|-------|------|---------------|------------|-----------------|----------|
| TC-AUTH-020 | Valid Logout | Use Case Testing | System | Functional | User logged in | 1. Click user menu<br>2. Click Logout | User redirected to login page, session destroyed | High |
| TC-AUTH-021 | Session Invalidation | White Box | Integration | Functional | User logged in | 1. Logout<br>2. Try to access protected route | Redirect to login | High |

---

## 3. Event Module Test Cases

### 3.1 Event Listing (EVT-001)

| TC ID | Test Case | Technique | Level | Type | Preconditions | Test Steps | Expected Result | Priority |
|-------|-----------|-----------|-------|------|---------------|------------|-----------------|----------|
| TC-EVT-001 | View Public Events | Use Case Testing | System | Functional | Active events exist | Navigate to /home | Display only active events | High |
| TC-EVT-002 | Archived Events Hidden | State Transition | Integration | Functional | Archived events exist | Navigate to /home | Archived events not displayed | High |
| TC-EVT-003 | Events Pagination | Boundary Value Analysis | System | Functional | More than 12 events | Navigate to /home | 12 events per page, pagination visible | Medium |

### 3.2 Event Search (EVT-002)

| TC ID | Test Case | Technique | Level | Type | Preconditions | Test Steps | Expected Result | Priority |
|-------|-----------|-----------|-------|------|---------------|------------|-----------------|----------|
| TC-EVT-010 | Search by Title | Equivalence Partitioning | System | Functional | Events exist | 1. Enter search term<br>2. Submit | Events matching title displayed | Medium |
| TC-EVT-011 | Search by Description | Equivalence Partitioning | Integration | Functional | Events exist | Search with description keyword | Events matching description displayed | Medium |
| TC-EVT-012 | Search No Results | Equivalence Partitioning | System | Functional | None | Search "xyznonexistent123" | No events message | Medium |
| TC-EVT-013 | Search Empty Term | Boundary Value Analysis | System | Functional | Events exist | Search with empty term | All active events displayed | Low |

### 3.3 Event Category Filter (EVT-003)

| TC ID | Test Case | Technique | Level | Type | Preconditions | Test Steps | Expected Result | Priority |
|-------|-----------|-----------|-------|------|---------------|------------|-----------------|----------|
| TC-EVT-020 | Filter by Category | Equivalence Partitioning | System | Functional | Events with categories exist | Select category filter | Only events in category displayed | Medium |
| TC-EVT-021 | Filter by Weekday | Equivalence Partitioning | System | Functional | Events exist | Select weekday filter | Only events on that day displayed | Medium |
| TC-EVT-022 | Combined Filters | Decision Table | System | Functional | Events exist | Apply category + weekday filters | Events matching both criteria | Medium |

### 3.4 Event Details (EVT-004)

| TC ID | Test Case | Technique | Level | Type | Preconditions | Test Steps | Expected Result | Priority |
|-------|-----------|-----------|-------|------|---------------|------------|-----------------|----------|
| TC-EVT-030 | View Event Details | Use Case Testing | System | Functional | Event exists | Navigate to /events/{id} | Event details displayed (title, description, date, place, price, capacity) | High |
| TC-EVT-031 | View Non-existent Event | Equivalence Partitioning | System | Functional | None | Navigate to /events/99999 | 404 error page | Medium |

### 3.5 Event CRUD - Admin (EVT-005, EVT-006, EVT-007)

| TC ID | Test Case | Technique | Level | Type | Preconditions | Test Steps | Expected Result | Priority |
|-------|-----------|-----------|-------|------|---------------|------------|-----------------|----------|
| TC-EVT-040 | Create Event - Valid | Equivalence Partitioning | Integration | Functional | Admin logged in | 1. Navigate to /admin/events/create<br>2. Fill all required fields<br>3. Submit | Event created successfully | High |
| TC-EVT-041 | Create Event - Missing Title | Equivalence Partitioning | Unit | Functional | Admin logged in | Submit form without title | Validation error | High |
| TC-EVT-042 | Create Event - End Before Start | Boundary Value Analysis | Unit | Functional | Admin logged in | End date before start date | Validation error | High |
| TC-EVT-043 | Update Event | Use Case Testing | Integration | Functional | Admin logged in, event exists | 1. Edit event<br>2. Change title<br>3. Save | Event updated | Medium |
| TC-EVT-044 | Archive Event | State Transition | Integration | Functional | Admin logged in, active event | Delete/Archive event | Event status changed to archived | Medium |
| TC-EVT-045 | Create Event - Non-Admin Access | Decision Table | Integration | Security | Regular user logged in | Try POST to /admin/events | 403 Forbidden | High |

---

## 4. Registration Module Test Cases

### 4.1 Event Registration (REG-001, REG-002)

| TC ID | Test Case | Technique | Level | Type | Preconditions | Test Steps | Expected Result | Priority |
|-------|-----------|-----------|-------|------|---------------|------------|-----------------|----------|
| TC-REG-001 | Register for Event | Use Case Testing | System | Functional | User logged in, event not full | 1. View event<br>2. Click Register | Registration success message | High |
| TC-REG-002 | Register - Event Full | Boundary Value Analysis | Integration | Functional | User logged in, event at capacity | Try to register | Error "Event is full" | High |
| TC-REG-003 | Register - Already Registered | Equivalence Partitioning | Integration | Functional | User already registered | Try to register again | Error "Already registered" | High |
| TC-REG-004 | Register - Not Logged In | Decision Table | System | Functional | Not logged in | Click Register button | Redirect to login | High |
| TC-REG-005 | Unregister from Event | Use Case Testing | System | Functional | User registered for event | Click Unregister | Unregistration success | Medium |
| TC-REG-006 | Unregister - Not Registered | Equivalence Partitioning | Integration | Functional | User not registered | Try to unregister | Error message | Low |

### 4.2 My Registrations (REG-003)

| TC ID | Test Case | Technique | Level | Type | Preconditions | Test Steps | Expected Result | Priority |
|-------|-----------|-----------|-------|------|---------------|------------|-----------------|----------|
| TC-REG-010 | View My Registrations | Use Case Testing | System | Functional | User logged in with registrations | Navigate to /my-registrations | List of user's registrations | Medium |
| TC-REG-011 | My Registrations - Empty | Equivalence Partitioning | System | Functional | User with no registrations | Navigate to /my-registrations | Empty state message | Low |

---

## 5. Category Module Test Cases

| TC ID | Test Case | Technique | Level | Type | Preconditions | Test Steps | Expected Result | Priority |
|-------|-----------|-----------|-------|------|---------------|------------|-----------------|----------|
| TC-CAT-001 | View Categories | Use Case Testing | System | Functional | Admin/Manager logged in | Navigate to /admin/categories | List of categories | Medium |
| TC-CAT-002 | Create Category | Equivalence Partitioning | Integration | Functional | Admin logged in | Create new category | Category created | Medium |
| TC-CAT-003 | Create Category - Duplicate | Equivalence Partitioning | Integration | Functional | Category exists | Create with existing name | Validation error | Medium |
| TC-CAT-004 | Delete Category - Has Events | Decision Table | Integration | Functional | Category with events | Try to delete | Error "Contains events" | Medium |
| TC-CAT-005 | Delete Category - Empty | Equivalence Partitioning | Integration | Functional | Category without events | Delete category | Category deleted | Low |

---

## 6. User Management Test Cases

| TC ID | Test Case | Technique | Level | Type | Preconditions | Test Steps | Expected Result | Priority |
|-------|-----------|-----------|-------|------|---------------|------------|-----------------|----------|
| TC-USR-001 | View Users List | Use Case Testing | System | Functional | Admin logged in | Navigate to /admin/users | List of users | Medium |
| TC-USR-002 | Create User - Admin | Decision Table | Integration | Functional | Admin logged in | Create user with admin role | User created with admin role | Medium |
| TC-USR-003 | Manager Cannot Create | Decision Table | Integration | Security | Manager logged in | Try to access create user | 403 Forbidden | High |

---

## 7. Profile Module Test Cases

| TC ID | Test Case | Technique | Level | Type | Preconditions | Test Steps | Expected Result | Priority |
|-------|-----------|-----------|-------|------|---------------|------------|-----------------|----------|
| TC-PRF-001 | View Profile | Use Case Testing | System | Functional | User logged in | Navigate to /profile | Profile page displayed | Medium |
| TC-PRF-002 | Update Name | Equivalence Partitioning | Integration | Functional | User logged in | Change name and save | Name updated | Medium |
| TC-PRF-003 | Update Password - Valid | Equivalence Partitioning | Integration | Functional | User logged in | Enter correct current, new matching passwords | Password updated | Medium |
| TC-PRF-004 | Update Password - Wrong Current | Equivalence Partitioning | Integration | Functional | User logged in | Enter wrong current password | Error message | Medium |

---

## 8. Non-Functional Test Cases

### 8.1 Security Tests

| TC ID | Test Case | Technique | Level | Type | Preconditions | Test Steps | Expected Result | Priority |
|-------|-----------|-----------|-------|------|---------------|------------|-----------------|----------|
| TC-SEC-001 | Access Admin Without Auth | Decision Table | System | Security | Not logged in | Navigate to /admin/events | Redirect to login | High |
| TC-SEC-002 | Access Admin as User | Decision Table | System | Security | Regular user logged in | Navigate to /admin/events | Access denied or limited view | High |
| TC-SEC-003 | CSRF Protection | White Box | Integration | Security | None | Submit form without CSRF token | 419 error | High |

### 8.2 Performance Tests

| TC ID | Test Case | Technique | Level | Type | Preconditions | Test Steps | Expected Result | Priority |
|-------|-----------|-----------|-------|------|---------------|------------|-----------------|----------|
| TC-PERF-001 | Home Page Load Time | Benchmark | System | Performance | None | Load home page | Response time < 3 seconds | Medium |
| TC-PERF-002 | Login Response Time | Benchmark | System | Performance | None | Complete login | Response time < 2 seconds | Medium |

---

## 9. Test Case Summary

### By Test Level

| Level | Count |
|-------|-------|
| Unit | 10 |
| Integration | 25 |
| System | 35 |

### By Test Type

| Type | Count |
|------|-------|
| Functional | 63 |
| Non-Functional (Security) | 4 |
| Non-Functional (Performance) | 2 |

### By Technique

| Technique | Count |
|-----------|-------|
| Equivalence Partitioning | 28 |
| Boundary Value Analysis | 12 |
| Decision Table | 10 |
| Use Case Testing | 15 |
| State Transition | 3 |
| White Box | 2 |

---

**Document Version**: 1.0  
**Date**: December 26, 2025
