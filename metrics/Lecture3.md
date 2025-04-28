**Concept:** Goal Based Metrics

**Pradigm Chosen:** Goal-Question-Metrics (GQM)

- Goal A: **(GA) Enhance user experience**
- Goal Questions
	1. (Q1) Which books do users prefer?
	2. (Q2) Are there specific genres or titles users wish we had more of?
	3. (Q3) Do users find the books in featured content relevant to their interests?
	4. (Q4) How do users rate the apps Loading speeds and responsiveness when browsing and reding books?
	5. (Q5) How often do users complete reading a book once they start it
- Metrics for achieving the goals
	1. (M1) Book views - tracks the number of times a book is opened
	2. (M2) Book ratings - Captures user ratings (star-based) for each book
	3. (M3) Session durations - Measures the times users spend per session
	4. (M4) page load times - Evaluates the speed at which pages load
	5. (M5) Completion Rate - Measures how often users finish readind a book

![Graph](/md-assets/Rplot.png)

- How we have implemeted the metrics
1. Book views
	- **How its tracked**: Each time a user opens a book, its view count is incremented in the database by 1  
	- **Attribute:** View count 
	- **Scale:** Ratio scale 
	- **Unit:** Count or views
2. Book ratings
	- **How its tracked**: Users rate books using a 1 t0 5 star system, which is stored in the database
	- **Attribute:** Rating (1-5 stars) 
	- **Scale:** Ordinal scale
	- **Unit:** Stars(1-5)
3. Session Durations
   	- **How its tracked**: The system logs when a user starts and ends a session calculating the total time spent
   	- **Attribute**: Duration per session
   	- **Scale**: Ratio scale
   	- **Unit**: Minutes
4. Completion rate
   	- **How its tracked**: The system logs when a user starts and ends a session calculating the total time spent
   	- **Attribute**: Completion percentage
   	- **Scale**: Ratio scale
   	- **Unit**: Percentage
5. Page load Times
	- **How its tracked**: A timestamp is recorded when a user requests a page, and another is recorded when it loads, calculating the delay.
   	- **Attribute**: Load time
   	- **Scale**: Ratio scale
   	- **Unit**: Seconds
