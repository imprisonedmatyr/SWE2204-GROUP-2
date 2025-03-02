import random
import mysql.connector

# Define a set of generic reviews
reviews = [
    "This book was amazing!",
    "I enjoyed reading this book.",
    "An interesting read.",
    "Could have been better.",
    "Not my favorite, but still good.",
    "A well-written book.",
    "I would recommend this book.",
    "A captivating story.",
    "The book was just okay.",
    "I loved the characters in this book."
]

# Connect to the MySQL database with a specific user and password
conn = mysql.connector.connect(
    host="localhost",
    user="group2",
    password="group2",
    database="Library_web_db"
)
cursor = conn.cursor()

# Fetch all usernames
cursor.execute("SELECT username FROM users")
usernames = [row[0] for row in cursor.fetchall()]

# Fetch all book IDs
cursor.execute("SELECT book_id FROM books")
book_ids = [row[0] for row in cursor.fetchall()]

with open('insert_reviews.sql', 'w') as file:
    file.write("INSERT INTO reviews (username, book_id, review, rating) VALUES\n")
    for username in usernames:
        selected_book_ids = random.sample(book_ids, 10)
        for i, book_id in enumerate(selected_book_ids):
            review = random.choice(reviews)
            rating = random.randint(1, 5)
            file.write(f"('{username}', '{book_id}', '{review}', {rating})")
            if username == usernames[-1] and i == 9:
                file.write(";\n")
            else:
                file.write(",\n")

print("SQL insert statements have been written to 'insert_reviews.sql'")

# Close the database connection
conn.close()
