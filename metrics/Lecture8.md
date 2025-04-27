### Goal: **Evaluate and ensure the software quality of the system, to meet user needs and deliver a reliable, efficient, and maintainable system.**
- Questions
    1. Functionality
        - Does the system provide all required functionalities (search, user registration, manage users)?
            - Metric: Functionality coverage ->(Number of implemented features/ Number of required features) * 100
        - Does the system accurately perform tasks without errors?
            - Metric: Accuracy rate -> (Number of corresct responses / number of total responses) * 100
    2. Reliability 
        - How often does the system experience failures or crashes?
            - Metrics: Mean Time Between Failures -> Total uptime / Number of failures
    3. Usability
        - How easy is it for users to complete common tasks (search, register)?
            - Metric: Task Completion Rate -> (Number of users completing tasks / Total users) * 100
        - How satisfied are users with their overall experience?
            - Metric: Average rating from user satisfaction surveys (using a Likert scale of 1-5)
    4. Efficiency
        - How quickly does the system respond to user actions?
            -  Metric: Average Response Time
    5. Maintainability
        -  How easy is it to analyze and understand the system's code and architecture?
            -  Metric: Measure of the complexity of the system’s code (Cyclomatic Complexity for the search module = 5)
    6. Portability
        - How easily can the system be installed and configured in new environments?
            - Metric: Deployment Time

### **Summary**
| Characteristic  | Metric                     | Value      |
|-----------------|-----------------------------|------------|
| Functionality   | Functionality coverage      | Above 95%  |
|                 | Accuracy rate               | 98%        |
| Reliability     | Mean Time Between Failures  | 5000s      |
| Usability       | Task Completion rate        | 96%        |
|                 | Average rating              | 4.5        |
| Efficiency      | Average response time       | 150ms      |
| Maintainability | System complexity           | Moderate   |
| Portability     | Deployment Time             | 2 minutes  |
