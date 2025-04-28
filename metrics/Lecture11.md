### **Goal**: Improve maintainability and modularity of database access code.
---
**Questions**:
- How complex is the database interface?
- How tightly coupled is it to external systems?
- How many operations are directly triggered by an object of this class?
**Metrics**: WMC, DIT, NOC, CBO, RFC

**Class under analysis**: [Database](SWE2204-GROUP-2/E-Library/db_connect.php)

---

### Applying C&K Metrics


1. **Weighted Methods per Class (WMC)**
- **Definition**: Sum of complexities of methods in the class.
- **Calculation**: In Database, there are 4 methods (__construct = 1, connectDB = 2, query = 1, prepare = 1). WMC = 5.

2. **Depth of Inheritance Tree (DIT)**
- **Definition**: Maximum distance from the class to the root of the inheritance tree.
- **Calculation**: Database does not inherit from any other class. DIT = 0.

3. **Number of Children (NOC)**
- **Definition**: Number of immediate subclasses.
- **Calculation**: Database does not have any subclasses. NOC = 0.

4. **Coupling Between Objects (CBO)**
- **Definition**: Count of non-inheritance-related class hierarchies on which a class depends.
- **Calculation**: Database class depends on the mysqli built-in class. CBO = 1.

5. **Response For a Class (RFC)**
- **Definition**: Count of all methods that can be invoked in response to a message to an object of the class.
- **Calculation**: RFC includes:
  - 4 methods defined within the Database class.
  - Plus approximately 3 methods that could be called on the $conn (mysqli) object (e.g., query(), prepare(), close()).
  - Total RFC = 4 + 3 = 7.

---

### Metrics Summary
| Metric       | Value    | Implication  |
|--------------|----------|--------------|
| WMC           | 5        | Manageable complexity |
| DIT           | 0        | No inheritance complexity  |
| NOC           | 0        | No subclasses    |
| CBO           | 1        | Low external dependency  |
| RFC           | 7        | Moderate operational complexity |
