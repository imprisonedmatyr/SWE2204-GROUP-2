use Library_web_db;

GRANT ALL PRIVILEGES ON Library_web_db.* TO 'root'@'localhost';

FLUSH PRIVILEGES;

delete from users;
INSERT INTO users (firstname, lastname, username, email, password) VALUES
('George', 'Johnson', 'george1', 'george1@example.com', '7ffa2d4a657dda6a84b76727679a7aec47cbb384ca61e6dfac188ccd9612eebe') ; -- n2tDDH9C,


