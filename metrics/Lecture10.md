# Software Testing and Metrics Implementation

## 1. Test Concepts and Definitions

- **Run**: The E-Library defines a run as a user action (e.g., login, borrow, search) initiated through the web interface.
- **Direct Input Variables**: Email, password, search keywords, borrow requests.
- **Indirect Input Variables**: Server load, session timeouts.

---

## 2. Test Case Design and Management

- **Use-Case Based Test Cases**: Defined for operations like registration, login, borrowing books, and feedback submission.
- **Test Case Management**:
  - Total cases estimated based on developer time and scope.
  - Feature and regression tests prepared using equivalence classes and boundary conditions.

---

## 3. Types of Tests Performed

- **Certification Tests**: Ensured user account creation and login works under constraints.
- **Feature Tests**: Each module (e.g., search, favorites) was tested independently.
- **Load Tests**: Simulated 100+ users using Apache Bench to test performance.
- **Regression Tests**: Executed after fixing login and book retrieval bugs.
- **System Tests**: Tested full workflows: registration → borrow → feedback.
- **Acceptance Tests**: Conducted by a group of student testers.
- **Installation Tests**: Verified local setup using XAMPP worked across different Linux environments.

---

## 4. Testing Techniques

- **White Box Testing**:
  - Unit tested backend functions (auth, DB queries).
  - Ensured code coverage using manual assertions in PHPUnit.
- **Black Box Testing**:
  - Verified feature behavior (login, search, borrow) without accessing internals.

---

## 5. Testing Levels

- **Unit Testing**: PHP functions tested in isolation.
- **Integration Testing**: Combined auth + DB + session flows tested.
- **System Testing**: End-to-end testing of user journeys.
- **Acceptance Testing**: Feedback from end-users led to UI improvements.
- **Regression Testing**: Done after each major code change.

---

## 6. Estimating Test Cases

Estimated ~100 test cases based on:
- 8 developers × 2 weeks × 20 hours/week = 320 hrs
- Avg prep time = 2 hrs/test case → 100 cases

---

## 7. Test Case Specification Techniques

- **Equivalence Classes**:
  - Valid and invalid login/email formats.
  - Borrowing unavailable vs. available books.
- **Boundary Conditions**:
  - Password length, number of borrowed books (max 3).
- **Visible State Transitions**:
  - From login → dashboard → read/browse/download → logout

---

## 8. Test Time Allocation
Estimate ~ :
- **Feature Test**: 60% of testing time (core modules)
- **Regression Test**: 25%
- **Load Test**: 15%
- Allocated more time to book search, borrow, feedback

---

## 9. Coverage Metrics

- **Statement Coverage**: Estimated ~75% via manual test tracking.
- **Branch Coverage**: Handled using if-else and form states (login logic).
- **GUI Coverage**: ~90% via user interactions on all main pages.
- **Component Coverage**: All major modules tested: auth, book system, feedback, admin.

---

## 10. Test Metrics

- **Pass Rate**: ~90% in final week of testing.
- **Failure Rate**: ~7% due to form errors and database misconfigurations.
- **Pending Rate**: ~3% (tests awaiting integration of new features)

---

## 11. Testability Metrics

- **Test Controllability**:
  - Form-based actions (login, search, borrow) were directly testable (TC = 1).
  - Some feedback-based triggers were harder to simulate (TC = 0.5).
- **Built-in Tests**: Error logger and visit logger tracked failures in real time.

---

## 12. Remaining Defects Estimation

- Used seeded bug logic during testing:
  - Injected 10 dummy errors; 7 detected.
  - Detected 35 total issues.
  - Estimated remaining bugs = ((35×10)/7) - 35 = 15

---

## 13. Comparative Testing

- Two teams tested same build:
  - Team A: 30 bugs
  - Team B: 25 bugs
  - Common: 10 bugs
  - Estimated total = (30×25)/10 = 75 bugs
  - Remaining = 75 - (30 + 25 - 10) = 30 bugs

---

## 14. Phase Containment Effectiveness (PCE)

| Phase        | Injected | Found | Removed | PCE       |
|--------------|----------|-------|---------|-----------|
| Requirements | 5        | 5     | 4       | 80%       |
| Design       | 10       | 8     | 7       | 63.6%     |
| Coding       | 20       | 18    | 15      | 60%       |
