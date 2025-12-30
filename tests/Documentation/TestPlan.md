# AAB_EventPlanner - Test Plan Document

---

## 1. Introduction

### 1.1 Purpose
This document defines the test plan for the **AAB_EventPlanner** Laravel web application. It follows ISTQB principles and the guidelines specified in `Guideline_Test_Qualite_Logiciel.md`.

### 1.2 Scope
The System Under Test (SUT) is a complete event management system that includes:
- **Authentication**: Login, Registration, Logout
- **Event Management**: CRUD operations on events
- **Category Management**: CRUD operations on categories
- **User Management**: CRUD operations on users (Admin only)
- **Registration System**: User registration to events
- **Profile Management**: User profile updates

### 1.3 Test Objectives
- Verify functional correctness of all features
- Ensure role-based access control works correctly
- Validate input validations
- Verify system behavior under edge cases
- Test integration between modules
- Automate critical user journeys

---

## 2. Features to be Tested

### 2.1 Authentication Module
| Feature ID | Feature | Priority |
|------------|---------|----------|
| AUTH-001 | User Login | High |
| AUTH-002 | User Registration | High |
| AUTH-003 | User Logout | High |
| AUTH-004 | Role-based Redirection | Medium |

### 2.2 Event Module
| Feature ID | Feature | Priority |
|------------|---------|----------|
| EVT-001 | Public Event Listing | High |
| EVT-002 | Event Search | Medium |
| EVT-003 | Event Category Filter | Medium |
| EVT-004 | Event Details View | High |
| EVT-005 | Event Creation (Admin) | High |
| EVT-006 | Event Update (Admin) | High |
| EVT-007 | Event Archive (Admin) | Medium |

### 2.3 Registration Module
| Feature ID | Feature | Priority |
|------------|---------|----------|
| REG-001 | Register for Event | High |
| REG-002 | Unregister from Event | Medium |
| REG-003 | View My Registrations | Medium |
| REG-004 | Capacity Check | High |

### 2.4 Category Module
| Feature ID | Feature | Priority |
|------------|---------|----------|
| CAT-001 | View Categories | Medium |
| CAT-002 | Create Category (Admin) | Medium |
| CAT-003 | Update Category (Admin) | Low |
| CAT-004 | Delete Category (Admin) | Low |

### 2.5 User Management Module
| Feature ID | Feature | Priority |
|------------|---------|----------|
| USR-001 | View Users (Admin/Manager) | Medium |
| USR-002 | Create User (Admin) | Medium |
| USR-003 | Update User (Admin) | Medium |
| USR-004 | Delete User (Admin) | Low |

### 2.6 Profile Module
| Feature ID | Feature | Priority |
|------------|---------|----------|
| PRF-001 | View Profile | Medium |
| PRF-002 | Update Profile Info | Medium |
| PRF-003 | Update Password | Medium |
| PRF-004 | Update Avatar | Low |

---

## 3. Test Strategy

### 3.1 Test Levels

| Level | Description | Tools |
|-------|-------------|-------|
| **Unit Testing** | Test individual model methods, services, and controller methods | PHPUnit |
| **Integration Testing** | Test API endpoints and module interactions | PHPUnit, Laravel HTTP Tests |
| **System Testing** | End-to-end user scenarios | Selenium + Pytest (POM) |

### 3.2 Test Types

| Type | Description |
|------|-------------|
| **Functional Testing** | Verify features work as per requirements |
| **Non-Functional Testing** | Performance (response times), Security (access control) |

### 3.3 Test Design Techniques

| Technique | Application |
|-----------|-------------|
| **Equivalence Partitioning** | Input validation (valid/invalid email, password length) |
| **Boundary Value Analysis** | Capacity limits, password min/max length |
| **Decision Table** | Role-based access control |
| **State Transition** | Event status changes (active → archived) |
| **Use Case Testing** | Complete user journeys |

---

## 4. Test Environment

### 4.1 Hardware Requirements
- Development machine with minimum 4GB RAM
- Local web server (XAMPP)

### 4.2 Software Requirements
| Component | Version |
|-----------|---------|
| PHP | ^8.2 |
| Laravel | 11.x |
| MySQL | 8.x |
| Chrome | Latest |
| Python | 3.x |
| Selenium WebDriver | 4.x |
| Pytest | Latest |

### 4.3 Test Data
- Admin: `admin@eventplanner.com` / `admin123`
- Manager: `manager@eventplanner.com` / `manager123`  
- User: `user@eventplanner.com` / `user123`

---

## 5. Entry and Exit Criteria

### 5.1 Entry Criteria
- Application is deployed and accessible
- Database is seeded with test data
- All test dependencies are installed

### 5.2 Exit Criteria
- All critical tests passed
- Test coverage meets minimum requirements
- All defects documented and prioritized

---

## 6. Risks and Mitigation

| Risk | Probability | Impact | Mitigation |
|------|-------------|--------|------------|
| Database state inconsistency | Medium | High | Use database transactions, reset after tests |
| Selenium WebDriver compatibility | Low | Medium | Use WebDriver Manager |
| Test data dependencies | Medium | Medium | Independent test data setup |

---

## 7. Deliverables

1. Static Test Evidence (Code Review Report)
2. Test Cases Documentation (Markdown tables)
3. Automated Test Scripts (PHPUnit + Selenium/Pytest POM + Playwright)
4. Performance Test Scripts (PHPUnit)
5. Test Execution Report
6. Traceability Matrix
7. Final Test Report

---

## 8. Schedule

| Phase | Activities | Duration |
|-------|------------|----------|
| Analysis | Requirement extraction, test condition identification | Day 1 |
| Static Testing | Code review, static analysis | Day 1 |
| Test Design | Test case creation | Day 1-2 |
| Test Implementation | Script development | Day 2-3 |
| Test Execution | Manual + Automated | Day 3-4 |
| Reporting | Results compilation | Day 4 |

---

**Document Version**: 1.1  
**Date**: December 29, 2025  
**Author**: Adam, Afra
