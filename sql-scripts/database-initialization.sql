DROP DATABASE IF EXISTS film_sales_database;
CREATE DATABASE film_sales_database;
USE film_sales_database;

CREATE TABLE customers (
    customer_email VARCHAR(100),
    customer_password VARCHAR(100),
    customer_firstname VARCHAR(100),
    customer_lastname VARCHAR(100),
    customer_date_of_birth DATE,
    customer_address VARCHAR(200),
    customer_credit_card_number VARCHAR(100),
    PRIMARY KEY (customer_email)
);

CREATE TABLE films (
    film_id INT NOT NULL AUTO_INCREMENT,
    film_title VARCHAR(100),
    film_genre VARCHAR(100),
    film_price INT,
    film_thumbnail_filename VARCHAR(100),
    PRIMARY KEY (film_id)
);

CREATE TABLE films_in_cart (
    customer_email VARCHAR(100),
    film_id INT,
    film_in_cart_quantity INT,
    PRIMARY KEY (customer_email, film_id),
    FOREIGN KEY (customer_email) REFERENCES customers (customer_email),
    FOREIGN KEY (film_id) REFERENCES films (film_id)
);

CREATE TABLE purchases (
    purchase_id INT NOT NULL AUTO_INCREMENT,
    purchase_date DATE,
    purchase_time TIME,
    customer_email VARCHAR(100),
    PRIMARY KEY (purchase_id),
    FOREIGN KEY (customer_email) REFERENCES customers (customer_email)
);

CREATE TABLE films_purchased (
    film_id INT,
    purchase_id INT,
    film_purchased_quantity INT,
    PRIMARY KEY (film_id, purchase_id),
    FOREIGN KEY (film_id) REFERENCES films (film_id),
    FOREIGN KEY (purchase_id) REFERENCES purchases (purchase_id)
);

INSERT INTO customers (customer_email, customer_password, customer_firstname, customer_lastname, customer_date_of_birth, customer_address, customer_credit_card_number) VALUES
    ("okoye@abcd.com", "$2y$10$629FkEHi0aUwQhv9XrjsYOsYjZrpIfYKCoRrhOyxUdViNluNJjEle", "Okoye", "Bassey", "1991-04-24", "No. 1, Okoye Street, Lagos.", "1234567890"),
    ("adekunle@abcd.com", "$2y$10$6QKl7eMnB64fRGNMSL7gTOqrG9pxNgUKUh0/6VxQhtJ144vQcbfAW", "Adekunle", "Hassan", "1994-08-03", "No. 1, Adekunle Street, Lagos.", "0987654321");

INSERT INTO films (film_title, film_genre, film_price, film_thumbnail_filename) VALUES
    ("Titanic", "Adventure", 2000, "titanic.jpg"),
    ("Clash of Titans", "Action", 2300, "clash-of-titans.jpg"),
    ("Romeo and Juliet", "Romance", 1500, "romeo-and-juliet.jpg"),
    ("Ghajini", "Action", 1000, "ghajini.jpg"),
    ("Like Stars on Earth", "Educational", 1300, "like-stars-on-earth.jpg"),
    ("12 Years a Slave", "History", 2300, "12-years-a-slave.jpg"),
    ("Django", "Action", 2100, "django.jpg"),
    ("30 Days in Jamaica", "Comedy", 700, "30-days-in-jamaica.jpg");
