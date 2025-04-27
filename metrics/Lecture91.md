# GQM Implementation for User Experience Enhancement

## Goal-Question-Metrics Structure

**Paradigm:** Goal-Question-Metrics (GQM)  
**Primary Goal (GA):** Enhance user experience through data-driven improvements

### Goal Questions & Corresponding Metrics
| Question | Metric |
|----------|--------|
| Q1: Preferred books | M1: Book Views |
| Q2: Desired genres | M2: Book Ratings |
| Q3: Content relevance | M3: Session Duration |
| Q4: App responsiveness | M4: Page Load Times | 

## Metric Implementation Details

### 1. Book Views (M1)
```sql
// Database trigger on book open
UPDATE books SET views = views + 1 WHERE id = ?
```
- **Attribute:** View count per book
- **Scale:** Ratio
- **Collection:** Real-time in `bookinfo.php`

### 2. Book Ratings (M2)
```sql
// Rating submission handler
INSERT INTO user_metrics (metric_type, value) VALUES ('rating', $validatedRating)
```
- **Scale:** Ordinal (1-5 stars)
- **Validation:** Server-side range checking

### 3. Session Durations (M3)
```php
// Session time calculation
$duration = microtime(true) - $_SESSION['start_time'];
```
- **Unit:** Minutes
- **Storage:** Daily aggregate in `user_metrics`

### 4. Page Load Times (M4)
```sql
// Page load measurement
$loadTime = round(microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"], 3);
```
- **Precision:** Milliseconds
- **Threshold:** Alert if >2s
