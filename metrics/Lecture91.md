# System Reliability Measurement

{- Goal: **Measure the System Reliability to ensure dependable user experience and system performance**  
- Questions
    1. Availability Questions
        - What percentage of time is the system operational and accessible to users?<br>
            - 99.9% target (measured via uptime logs)
        - How is system uptime monitored and logged?<br>
            - Via cron-job-based heartbeat checks every minute; results logged to `hertbeat_log.txt`
    2. Failure and Recovery Questions
        - How are system failures defined and categorized?<br>
            - Failures are categorized into Low, Medium, and High severity based on impact
        - How often do critical failures (e.g., login, database failures) occur?<br>
            - Tracked using `failures` database table with timestamps
    3. Fault Tolerance and Resilience Questions
        - How does the system recover from unexpected failures (e.g., database disconnection)?<br>
            - Reconnect logic and session regeneration implemented in PHP
        - Are there backup or retry mechanisms after a failure event?<br>
            - Manual database export; plans for retry mechanisms for failed downloads

---

### **Reliability Metric Categories and Weighting**

| Metric Category        | Monitoring Tool | Target Threshold | Measurement Frequency |
|-------------------------|-----------------|------------------|------------------------|
| Uptime Percentage       | Cron job + log.txt | ≥99.9%          | Every minute           |
| Mean Time Between Failures (MTBF) | PHP script analyzing `failures` table | Increase over time  | Daily |
| Mean Time to Recovery (MTTR) | Manual and automatic recovery logs | <10 minutes | After each failure |
| Failure Rate            | Error logger + `failures` DB | As low as possible | Weekly review |

---

## **Step 1: Metric Collection and Calculation**

1. **Availability**:
   - Uptime hours: 860 hours
   - Downtime hours: 1 hour
   - **Availability Calculation**:
     
    $$
    \text{Availability} = \frac{\text{Uptime}}{\text{Uptime} + \text{Downtime}}
    $$

    $$
    \text{Availability} = \frac{860}{860+1} \approx 99.88\%
    $$

2. **Failure Rate**:
   - Number of critical failures over a week: 2
   - Requests handled in a week: 20,000

$$
\text{Failure Rate} = \frac{2}{20000} \approx 0.01\%
$$


3. **MTBF (Mean Time Between Failures)**:
   - Sample timestamps: [1694456400, 1694470800, 1694514000]
   - **MTBF Calculation**:
   ```php
   $failures = [1694456400, 1694470800, 1694514000];
   $mtbf = ($failures[2] - $failures[0]) / (count($failures) - 1);
   echo "MTBF: " . round($mtbf / 3600, 2) . " hours";
   ```
   - Result: Approximately 5 hours


---

### **Step 2: Analysis and Reliability Growth**
- **Trend Monitoring**:
  - Weekly failure counts analyzed with log_analyzer.php to spot trends and   reliability improvements.
- **Exponential Reliability Model**:
```php
$lambda = 0.002;
$time = 120;
$reliability = exp(-$lambda * $time);
echo "Predicted reliability: " . round($reliability, 4);
```
- Predicted Reliability after 2 hours: ~78%

---


