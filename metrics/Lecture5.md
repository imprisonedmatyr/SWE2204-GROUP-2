- Goal: **Measuring Size of the Search Module to estimate effort needed to make it**
- Questions
	1. Functional Completeness Questions
		- How many unique user inputs does the Search Module require?<br>
			- 3 (Book Name, Author, Genre)
		- What are the number and types of outputs provided by the Search Module ?<br>
			- 2 (search results, error messages)
		- How many external interfaces (e.g., APIs, external systems) does the module need to interact with?<br>
			- 0
    2. Complexity Questions
		- What is the count of logical internal files (e.g., tables, datasets) accessed or updated by the module?<br>
			- 2 (book database, jsonfile with book names)
		- How many external queries or references to external files are performed by the Search Module?<br>
			- 0
			
---

### **Function Point Categories and Complexity Weighting**

| Component Type         | Low Complexity | Average Complexity | High Complexity |
|-------------------------|----------------|--------------------|-----------------|
| External Inputs (EI)    | 3              | 4                  | 6               |
| External Outputs (EO)   | 4              | 5                  | 7               |
| External Inquiries (EQ) | 3              | 4                  | 6               |
| Internal Logical Files (ILF) | 7         | 10                 | 15              |
| External Interface Files (EIF) | 5       | 7                  | 10              |

---

### **Step 1: Classifying and Assigning Weights**

1. **External Inputs (EI)**:
   - Inputs: Book Name, Author, Genre
   - Count: 3
   - Complexity: Low 
   - Weight: 3 per input  
   Total: **3 × 3 = 9**

2. **External Outputs (EO)**:
   - Outputs: Search results, Error messages
   - Count: 2
   - Complexity: Low 
   - Weight: 4 per output  
   Total: **2 × 4 = 8**

3. **External Inquiries (EQ)**:
   - None reported (Count: 0)
   - Total: **0**

4. **Internal Logical Files (ILF)**:
   - Files: Book database, JSON file with book names
   - Count: 2
   - Complexity: Low 
   - Weight: 7 per file  
   Total: **2 × 7 = 14**

5. **External Interface Files (EIF)**:
   - None reported (Count: 0)
   - Total: **0**

---

### **Step 2: Adding Up Raw Function Points**
Now, summing up all the calculated components:

**Raw Function Points (RFP) = EI + EO + EQ + ILF + EIF**  
**RFP = 9 + 8 + 0 + 14 + 0 = 31**

---

### **Step 3: Adjusting for Complexity**
Adjusted Function Points (AFP) = RFP × VAF  
VAF = 1:  
**AFP = 31 × 1 = 31**
