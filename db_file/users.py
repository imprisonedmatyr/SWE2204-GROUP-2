import random
import hashlib

firstnames = ['John', 'Jane', 'Alice', 'Bob', 'Charlie', 'Diana', 'Edward', 'Fiona', 'George', 'Hannah']
lastnames = ['Smith', 'Johnson', 'Williams', 'Jones', 'Brown', 'Davis', 'Miller', 'Wilson', 'Moore', 'Taylor']

def generate_password():
    chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"
    return ''.join(random.choice(chars) for _ in range(8))

def hash_password(password):
    return hashlib.sha256(password.encode()).hexdigest()

with open('insert_users.sql', 'w') as file:
    file.write("INSERT INTO users (firstname, lastname, username, email, password) VALUES\n")
    for i in range(1, 101):
        firstname = random.choice(firstnames)
        lastname = random.choice(lastnames)
        username = f"{firstname.lower()}{i}"
        email = f"{username}@example.com"
        password = generate_password()
        hashed_password = hash_password(password)
        file.write(f"('{firstname}', '{lastname}', '{username}', '{email}', '{hashed_password}') -- {password}")
        if i < 100:
            file.write(",\n")
        else:
            file.write(";\n")

print("SQL insert statements have been written to 'insert_users.sql'")
