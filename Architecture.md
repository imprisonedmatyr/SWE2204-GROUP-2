E-Library web app - Software Architecture Document
==================================================

Note: the architecture described is subject to change as development proceeds

Introduction
---------------

This document outlines the architecture of E-Library web application, a system designed to
allow basic users to browse, read, review, and manage a personal collection of books, while
enabling admin users to manage the entire library, including adding, deleting books, and banning
users. The application is built using HTML, CSS, vanilla JavaScript for the frontend, PHP for the
backend, and MySQL for the database.

System Overview
------------------

E-Library is a web-based application that provides two main types of users:

1. Basic Users
- Browse the library of books.
- Search for books.
- Read books.
- Sign up, log in, and manage a personal library.
- Rate and review books they have read.
2. Admin Users
- Manage the entire book collection (add, edit, delete books).
- Manage user accounts (ban users for misconduct)

The system is composed of three main components:
- Frontend: HTML, CSS, and JavaScript.
- Backend: PHP.
- Database: MySQL.

Architecture Overview
---------------------
The architecture follows the Model-View-Controller (MVC) pattern to separate concerns, ensuring
modularity and maintainability.

### Frontend (View)
The frontend is responsible for presenting the application to users and interacting with them. It uses
the following technologies:
- HTML: Structure of the web pages (home page, book details, login, etc.).
- CSS: Styling of the web pages to provide an attractive user interface.
- JavaScript: Implements interactivity, form validation, asynchronous communication (AJAX),
and dynamic content loading.

### Backend (Controller)
The backend handles all business logic and database interactions. It is built using PHP, which
manages the following operations:
- User Authentication & Management: Sign up, login, and session handling for users.
- Book Management: CRUD (Create, Read, Update, Delete) operations for books.
- Review and Rating System: Store user ratings and reviews.
- Admin Operations: Admin functionalities like banning users and deleting books.

### Database (Model)
The MySQL database stores all persistent data. The following key entities are present in the
database
- **Users:** Stores details like name, email, password (hashed), user type (basic or admin).
- **Books:** Stores book details such as title, author, genre, description, file (or URL for digital
books), and availability.
- **Reviews:** Stores user reviews and ratings for books.
- **Personal Library:** Stores the user's collection of books and related metadata.
- **Admin Logs:** Tracks admin activities (book deletions, user bans, etc.).

Detailed Component Breakdown
----------------------------

### Frontend (View)

• **HTML Structure**
- **Home Page:** Displays the list of available books and includes the search functionality.
- **Book Detail Page:** Displays detailed information about a book, including a link to
read the book (if available), and allows users to rate and review the book.
- **Login/Signup Pages:** Forms for basic users to sign up and log in.
- **Personal Library Page:** Displays books added by the user to their personal collection,
and provides options to remove or add new books.
- **Admin Dashboard:** A page for admins to manage users and books (add/delete books,
ban users).

• **JavaScript**
- **AJAX Requests:** For searching books, submitting reviews, and dynamically updating
pages without full reloads.
- **User Interaction:** Handles user interaction like form validation (signup, login),
dynamic display of reviews, and notifications.

### Backend (Controller)

• **User Authentication**
- PHP handles the user authentication flow.
- Users can sign up (by providing email, password), and the password is securely hashed
using PHP's password_hash() function.
- Users log in using their email and password, and their session is stored using PHP
sessions.

• **Book Management**
- Admin users have *CRUD* capabilities on books stored in the MySQL database.
- Books are stored in a table with fields like id, title, author, genre,
description, file_url, and availability.

• **Personal Library Management**
- Basic users can add books to their personal library after signing up and logging in.
- Users can view and manage the books they have added to their personal library (e.g.,
remove books, view ratings).

• **Reviews and Ratings**
- Users can submit reviews and ratings for books they have read.
- Each review has a user_id, book_id, rating, and review_text.

• **Admin Operations**
- Admins can manage books and users. Admins can also ban users for misconduct or
inappropriate behavior.
- Admin actions are logged in the database to maintain records of user bans and deleted
books.
### Database (Model)

• **books Table**
 - book_id (INT)
 - TITLE (VARCHAR)
 - AUTHOR (VARCHAR)
 - BOOK_COVER (VARCHAR)
 - STATUS ENUM('available', 'UnAvailable')
 - CATEGORY (VARCHAR)
 - GENRE (VARCHAR)
 - PUBLICATION YEAR (INT)
 - PAGES (INT)
 - visits (INT),
 - short_description (VARCHAR)

• **content Table**
 - ChapterID (INT)
 - Chapter_title (VARCHAR)
 - File_path (VARCHAR)
 - book_id (INT)
 - created_at (TIMESTAMP CURRENT_TIMESTAMP),
 - updated_at (TIMESTAMP CURRENT_TIMESTAMP) ON UPDATE CURRENT_TIMESTAMP,
 - 
• **staff Table**
 - FIRSTNAME (VARCHAR)
 - LASTNAME (VARCHAR)
 - STAFFID (VARCHAR)
 - EMAIL (VARCHAR)
 - DEPARTMENT (VARCHAR)

• **users Table**
 - firstname (VARCHAR)
 - lastname (VARCHAR)
 - username (VARCHAR)
 - email (VARCHAR)
 - password (VARCHAR)
 - staffid (VARCHAR)
 - is_staff TINY(INT)
 - is_banned TINY(INT)

• **favorite_books Table**
 - book_id (INT)
 - username (VARCHAR)
 - added_on (TIMESTAMP CURRENT_TIMESTAMP)

• **reviews Table**
 - book_id (INT)
 - username (VARCHAR)
 - review (LONGTEXT)
 - created_at (TIMESTAMP CURRENT_TIMESTAMP)

Security Considerations
-----------------------

1. **Password Management**
- Passwords are stored securely using hashing algorithms (password_hash() in
PHP).
2. **User Sessions**
- PHP sessions are used to manage user logins, and user sessions are securely handled to
prevent session hijacking.
3. **Input Validation and Sanitization**
- All user inputs (e.g., forms, search queries, reviews) are validated and sanitized to
prevent SQL injection and XSS attacks.
4. **Admin Access Control**
- Only users with the admin user type can access admin functionalities (add, delete
books, ban users).

System Flow
-----------

1. **User Registration/Authentication**
- Basic users sign up with an email and password. Upon successful registration, a
session is created for the user.
- Users can log in using their credentials, and the session is maintained for authenticated
access.
2. **Book Interaction**
- Users can browse, search, and read books. They can rate and review books.
- Admins can add, edit, or delete books from the library.
3. **Personal Library**
- Once logged in, basic users can add books to their personal library, separate from the
main library.
- Admins cannot interact with a user's personal library.
4. **Admin Operations**
- Admins can ban users, delete books, and view logs of their activities.

Conclusion
----------

The e-Library application provides a simple yet functional system where users can interact with a
library of books, add to their personal collection, and rate/review books. Admins have full control
over the library and user management. The architecture ensures a clean separation between
frontend, backend, and database, promoting scalability and maintainability