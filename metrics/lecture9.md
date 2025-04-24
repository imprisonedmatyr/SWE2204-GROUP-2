# Software Reliability Implementation
## 1. Reliability Concepts and Definitions
**Key Reliability Aspects Implemented:**
- **Availability**: The system is accessible 24/7 via web browsers.
                    Sessions are maintained securely, and 
                    uptime is logged in server-side files.

- **Failure Definition**: A failure is defined as any event that prevents successful login, book search, or admin operations (upload book contents).
Which is then logged on every occurence. e.g Sample error logging in ['bookinfo.php'](../E-Library/bookinfo.php).
- **Reliability Monitoring**: Using custom PHP scripts, system reliability metrics such as failure rate, uptime/downtime, and request-response failures are continuously tracked.

---

### 1.2 Key Reliability Metrics Implemented

#### 1.2.1 Mean Time Between Failures (MTBF)
- Errors are logged using a custom `database table 'failures'` to track the timestamp, affected module, and severity.
- Failures are classified as:
  - **Low** (non-blocking UI errors)
  - **Medium** (login/bookmarks issues)
  - **High** (database or session failures)
- MTBF is calculated by tracking timestamps between critical system failures.

```php
$failures = [1694456400, 1694470800, 1694514000]; // sample timestamps
$mtbf = ($failures[2] - $failures[0]) / (count($failures) - 1);
echo "MTBF: " . round($mtbf / 3600, 2) . " hours";
```

#### 1.2.2 Availability
- Server uptime and downtime are written to a `log.txt` file.
- Uptime is tracked using a cron job that checks the status every minute.
- Availability is calculated and shown on the admin dashboard.

```php
$uptime = 860;    // hours
$downtime = 1;    // hour
$availability = $uptime / ($uptime + $downtime);
echo "System Availability: " . round($availability * 100, 2) . "%";
```

- **Target Availability**: 99.9%

---

## 2. Reliability Engineering Implementation

### 2.1 Error Prevention and Detection
- **Input Validation**: Implemented in all forms (login, search, register) using PHP server-side validation and JavaScript.
- **Transaction Management**: All borrowing, returning, and user updates are wrapped in MySQL transactions to prevent data corruption.
- **Error Logging**: `error_logger.php` stores errors into a MySQL `error_log` table and writes detailed entries to a `.log` file.
- **Automated Testing**: Implemented via PHPUnit scripts for modules like login, borrow, return, and user registration.

### 2.2 Fault Tolerance
- **Database Connection Failover**: Includes reconnect logic when the MySQL connection is lost.
- **Session Management**: Uses `session_start()` with `session_regenerate_id()` to prevent hijacking and support stable sessions across unexpected reloads.
- **Backup Procedures**: Admin interface includes a manual database export button with download option.

---

## 3. Reliability Growth Models

### 3.1 Data Collection
- All exceptions and critical PHP errors are logged to `error_log` with timestamps and page references.
- `log_analyzer.php` script plots failure trends and generates simple reports for admins.

### 3.2 Analysis Methods
- **Exponential Reliability Model** is used via PHP:
```php
$lambda = 0.002;
$time = 120;
$reliability = exp(-$lambda * $time);
echo "Predicted reliability: " . round($reliability, 4);
```
- **Trend Analysis**: A dashboard chart shows weekly failure counts for admin review.
- **User Feedback Log**: A built-in feedback system captures perceived bugs from users.

---

## 4. Quality Management

### 4.1 Prevention Methods
- **Code Review**: Manual peer reviews were conducted for every pull request via GitHub.
- **CI/CD**: Code pushed to GitHub runs tests via GitHub Actions with PHPUnit.
- **Performance Monitoring**: A lightweight PHP script logs load time per page for each session.

### 4.2 Quality Metrics
- **Code Coverage**: Monitored via PHPUnit test reports (~75% target coverage).
- **Response Times**: Measured using PHP microtime at request start and end.

---

## 5. Operational Profile

### 5.1 System Operations
- User Authentication (Login/Logout)
- Book Search (AJAX, keyword based)
- Book Borrowing & Return
- Bookmarking System
- Admin Control Panel (User bans, Book uploads)

### 5.2 Testing Strategy
- **Unit Tests**: Written for all PHP classes (auth, database, utils).
- **Integration Tests**: Login → borrow → bookmark flow tested.
- **Load Testing**: Simulated 100 concurrent logins using Apache Bench.
- **UAT**: Performed by a test group of students at the university lab.

---

## 6. Release Criteria

### 6.1 Quality Gates
- All PHPUnit tests must pass
- Response time must stay under 2s for 90% of pages
- No critical or high-severity unresolved bugs in error log
- User registration, borrowing, and admin must function correctly

### 6.2 Monitoring
- **Admin Monitoring Page**: Displays system status, uptime, error trends
- **Feedback Collector**: Gathers user-reported or experienced bugs
- **Weekly Log Review**: Admin examines logs for anomalies

---

## 7. Future Improvements

### 7.1 Planned Enhancements
- More granular logging (e.g., user actions before failure)
- AI-based pattern detection on failure logs
- Retry mechanisms for failed actions e.g downloads
- Integrate a third-party monitoring tool (e.g., UptimeRobot)

### 7.2 Research Areas
- Integrate machine learning to predict failure spikes
- Analyze user behavior to optimize performance
- Study user peak times to tune session expiry/failover