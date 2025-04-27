## **Goal: Measuring the Effectiveness and Reliability of the E-Library Testing Process**
### **1. Questions**

#### 1.1 **Functional Completeness Questions**

- **How well does the testing cover all functional aspects of the E-Library system?**
    - **Test Cases Designed**: Estimated 100 test cases for core functionality. Includes use-case-based tests (e.g., login).
    
- **What are the primary user actions and their associated test cases?**
    - **User Actions**: Login and search.
    - **Test Cases**:
        - Registration and login.
        - Searching for books.

#### 1.2 **Coverage Questions**

- **How is test coverage measured across different components of the system?**
    - **Test Coverage**:
        - **Statement Coverage**: ~75%.
        - **Branch Coverage**: Covered using if-else and form states (login logic).
        - **GUI Coverage**: ~90%, based on user interactions.
        - **Component Coverage**: All major modules tested.

---

### **2. Test Case Estimation**
- **Total Test Time Available**: 100 hours
- **Average Time per Test Case**: 4 hours
- **Cost per Test**: UGX 20000
- **Total Budget**: UGX 220000
- **Total Avialable Staff**: 4 persons

### Estimations:

- **Maximum Test Cases by Time**:
  
$$
\text{Test Cases} = \frac{100 \* 4}{4 } = 160 \, \text{cases}
$$

- **Maximum Test Cases by Budget**:
  
$$
\text{Test Cases} = \frac{220000}{20000} = 110 \, \text{cases}
$$

- **Maximum Test Cases** = Min(110,100) = 100 test cases
---

### 3. **Test Time**
- **Test Time Allocation**:
  - **Feature Test**: 60% of total testing time focused on core functionality.
  - **Regression Test**: 25% focused on ensuring previously fixed issues don't recur.
  - **Load Test**: 15% of time dedicated to performance testing.
 
---

### 4. **Reliability Metrics Overview**
- **Pass Rate**: ~90% in final week of testing.
- **Failure Rate**: ~7% due to issues such as form errors and database misconfigurations.
- **Pending Rate**: ~3% (tests awaiting integration of new features).

---

### 5. **Phase Containment Effectiveness (PCE)**
| Phase	      | Injected	| Found	 | Removed	| PCE   |
|-------------|-----------|--------|----------|-------|
| Requirements | 5	       | 5	    | 4     	| 80%   |
| Design	     | 10	       | 8	    | 7	      | 63.6% |
| Coding	     | 20	       | 18	    | 15	    | 60%   |
