# **Software Testing and Metrics Implementation - GQM Approach**

## **Goal: Measuring the Effectiveness and Reliability of the E-Library Testing Process**

---

### **1. Questions**

#### 1.1 **Functional Completeness Questions**

- **How well does the testing cover all functional aspects of the E-Library system?**
    - **Test Cases Designed**: 
        - Estimated 100 test cases for core functionality.
        - Includes use-case-based tests (e.g., login, borrow, feedback).
        - Comprehensive coverage across modules (search, borrow, login, feedback).
    
- **What are the primary user actions and their associated test cases?**
    - **User Actions**: Login, borrow, search, and feedback submission.
    - **Test Cases**:
        - Registration and login.
        - Borrowing books.
        - Searching for books.
        - Submitting feedback.

#### 1.2 **Complexity Questions**

- **What is the complexity of the features being tested?**
    - **Feature Complexity**:
        - Login system: Simple form validation.
        - Borrowing system: Involves database interaction and availability checks.
        - Feedback submission: Simple form processing.
    
- **How is test coverage measured across different components of the system?**
    - **Test Coverage**:
        - **Statement Coverage**: ~75%.
        - **Branch Coverage**: Covered using if-else and form states (login logic).
        - **GUI Coverage**: ~90%, based on user interactions.
        - **Component Coverage**: All major modules tested.

---

### **2. Metrics**

#### 2.1 **Function Point Categories and Complexity Weighting**

| Component Type            | Low Complexity | Average Complexity | High Complexity |
|---------------------------|----------------|--------------------|-----------------|
| External Inputs (EI)       | 3              | 4                  | 6               |
| External Outputs (EO)      | 4              | 5                  | 7               |
| External Inquiries (EQ)    | 3              | 4                  | 6               |
| Internal Logical Files (ILF) | 7             | 10                 | 15              |
| External Interface Files (EIF) | 5          | 7                  | 10              |

#### 2.2 **Function Point Calculation**

- **External Inputs (EI)**: 
    - Inputs: Email, password, search keywords, borrow requests.
    - Count: 4
    - Weight: 3
    - Total: **4 × 3 = 12**
    
- **External Outputs (EO)**:
    - Outputs: Success or failure messages (e.g., login success, error messages).
    - Count: 2
    - Weight: 4
    - Total: **2 × 4 = 8**
    
- **Internal Logical Files (ILF)**:
    - Files: Database with user and book data.
    - Count: 1
    - Weight: 7
    - Total: **1 × 7 = 7**

- **External Interface Files (EIF)**:
    - Count: 0 (No external interfaces)
    - Total: **0**

- **Adjusted Function Points (AFP)**:
    - RFP (Raw Function Points) = EI + EO + ILF + EIF = 12 + 8 + 7 + 0 = **27**
    - VAF (Value Adjustment Factor): 1
    - **AFP = 27 × 1 = 27**

---

### **3. Step-by-Step Calculation for Reliability Metrics**

#### 3.1 **Availability Calculation**
   
**Goal**: Calculate the availability of the E-Library system based on uptime and downtime.

$$
\text{Availability} = \frac{\text{Uptime}}{\text{Uptime} + \text{Downtime}}
$$

- **Uptime**: 860 hours
- **Downtime**: 1 hour

$$
\text{Availability} = \frac{860}{860 + 1} \approx 99.88\%
$$

---

#### 3.2 **Failure Rate Calculation**

**Goal**: Measure the failure rate during testing.

$$
\text{Failure Rate} = \frac{\text{Critical Failures}}{\text{Total Requests}}
$$

- **Critical Failures**: 2 failures
- **Total Requests**: 20,000

$$
\text{Failure Rate} = \frac{2}{20000} \approx 0.01\%
$$

---

#### 3.3 **Mean Time Between Failures (MTBF)**

**Goal**: Calculate the mean time between failures for system reliability.

- **Failures timestamps**: [1694456400, 1694470800, 1694514000]
- **MTBF Calculation**:

```php
$failures = [1694456400, 1694470800, 1694514000];
$mtbf = ($failures[2] - $failures[0]) / (count($failures) - 1);
echo "MTBF: " . round($mtbf / 3600, 2) . " hours";
```
- **Result**: Approximately 5 hours between failures.

---

### 4. **Test Time and Test Case Estimation**
- **Test Time Allocation**:
  - **Feature Test**: 60% of total testing time focused on core functionality.
  - **Regression Test**: 25% focused on ensuring previously fixed issues don't recur.
  - **Load Test**: 15% of time dedicated to performance testing.
 
---

### 5. **Reliability Metrics Overview**
- **Pass Rate**: ~90% in final week of testing.
- **Failure Rate**: ~7% due to issues such as form errors and database misconfigurations.
- **Pending Rate**: ~3% (tests awaiting integration of new features).

---

### 6. **Phase Containment Effectiveness (PCE)**
| Phase	      | Injected	| Found	 | Removed	| PCE   |
|-------------|-----------|--------|----------|-------|
| Requirements | 5	       | 5	    | 4     	| 80%   |
| Design	     | 10	       | 8	    | 7	      | 63.6% |
| Coding	     | 20	       | 18	    | 15	    | 60%   |
