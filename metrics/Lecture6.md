- Goal: **Measuring the structural complexity of the Search Module to ensure maintainability and identify refacotring needs**
- Questions
    1. How many decision points (if, loops) are present in the code?
        - in the main code block
            - 1 decision node
        - in the returnData function
            - 2 decision nodes
    2. Are there nested conditional statements 
        - in the main code block
            - 0 nesting (flat structure)
        - in the returnData funtion
            - 1 (a while loop inside an if statement)

---

### **The Cyclomatic Complexity of the Search module**

- Formular:
        - V(G) = Number of Decisions + 1
- For the main block:
    - Complexity = 1 + 1 = 2
- For returnData:
    - Complexity = 2 + 1 = 3

### **The complexity summary for the search module**

| Component         | Cyclomatic Complexity | Max Nesting Depth | Risk Level |
|-------------------------|----------------|--------------------|-----------------|
| Main Block    | 2              | Level 1                  | Low               |
| returnData   | 3              | Level 2                  | Low               |

