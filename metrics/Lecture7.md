- Goal: **Estimate the cost of developing the system to ensure accurate resource allocation**
- Questions
    1. Effort Estimation
        - How much effort (person-hours) is required to build the system?
            - Metric: KLOC (thousands of lines of code) and COCOMO II effort formula
        - How does code complexity impact development effort
            - Metric: Cyclomatic complexity mapped to effort adjustment factors (EAF)
    2. Cost Drivers 
        - What factors influence development costs?
            - Metrics: Team experience, system complexity, required reliability (COCOMO II cost drivers)

---

### **Implementation Steps**

1. Define Project Parameter
    - Size: using linecounter.py to get total number executable line
        - 2326 lines ~ 2 KLOC
    - Define Cost Drivers (EAF)
        - Product:
            - Complexity: Nominal (Search Module complexity = 5 from lecture6.md) -> 1.00
            - Required reliability: Nominal -> 1.00
        - Personnel:
            - Team experience: Low -> 1.10
        - Project:
            - Tool use: Low (PHP, MySQL) ->1.09
        - EAF:
            EAF = 1.00 * 1.00 * 1.10 * 1.09 = 1.20
    - Define scaling factor
        - PREC: Low -> 4.96
        - FLEX: Nominal -> 3.04
        - RESL: Nominal -> 5.46
        - PMAT: Nominal -> 4.68
        - TEAM: Nominal -> 3.29
        - b = 0.91 + 0.01(3.04 + 5.56 + 4.68 + 3.29 + 4.96) = 1.12
2. Caltulate effort
    - Fomular: 
        E = 2.45 * (KLOC)^b * EAF
    - using: 
        EAF = 1.2, b = 1.12, KLOC = 2
    - Calculation:
        E = 2.45 * (2)^1.12 * 1.2 = 6 person months
