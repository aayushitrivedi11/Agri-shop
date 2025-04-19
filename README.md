Agri-Shop
Agri-Shop is an e-commerce platform designed for agricultural products, allowing farmers to register, browse products, add items to a cart, and manage their shopping experience. It includes an admin panel for managing products, with a focus on a farming-themed interface using green, brown, and beige colors to evoke an earthy aesthetic.
Features

User Features:
Farmer registration and login.
Browse products by category (e.g., Vegetables, Fruits, Grains, Dairy).
Add products to cart with quantity updates for repeated additions.
View and manage cart.


Admin Features:
Secure admin login (default: admin / admin123).
Add products with details (name, price, category, image, stock, description).
Simple, farming-themed admin interface.


Technical Details:
Built with PHP and MySQL.
PDO for secure database interactions.
Responsive design with CSS styled for agricultural aesthetics.



Prerequisites

XAMPP (or similar LAMP/WAMP stack) with PHP ≥ 7.4 and MySQL.
Web Browser (e.g., Chrome, Firefox).
Git (optional, for cloning the repository).
MySQL Client (e.g., phpMyAdmin).

Installation

Clone or Download the Repository:
git clone https://github.com/your-username/agri-shop.git

Or download the ZIP from GitHub and extract to E:\xampp\htdocs\agri-shop.

Set Up the Database:

Open phpMyAdmin (http://localhost/phpmyadmin).
Create a database named agri_shop:CREATE DATABASE agri_shop;


Import the database.sql file (located in the project root) to set up tables and initial data:
In phpMyAdmin, select agri_shop > Import > Choose database.sql > Go.


This creates tables (admins, products, categories, cart, farmers) and inserts a default admin (admin / admin123) and sample categories.


Configure Database Connection:

Copy config.example.php to config.php:copy config.example.php config.php


Edit config.php to match your database credentials (default XAMPP settings shown):$conn = new PDO("mysql:host=localhost;dbname=agri_shop", "root", "");


Save config.php in the project root.


Set Up Images Directory:

Ensure the images/ directory exists (E:\xampp\htdocs\agri-shop\images).
Place product images (e.g., tomato.jpg, apple.jpg) in images/. The database.sql references sample images; add these or update the database to match your images.
The images/.gitkeep file ensures the folder is tracked in Git.


Start XAMPP:

Open XAMPP Control Panel.
Start Apache and MySQL.
Access the project at http://localhost/agri-shop.



Usage
For Farmers (Users)

Register:
Visit http://localhost/agri-shop/register.php (create this if missing, based on process.php).
Enter details (fullname, email, phone, farm name, location, password).


Log In:
Go to http://localhost/agri-shop/login.php (create if missing).
Use your registered email and password.


Browse and Shop:
View products on index.php or products.php (create if needed).
Add products to cart via forms handled by process.php.
View cart at http://localhost/agri-shop/cart.php.


Manage Cart:
Update quantities or remove items (handled by process.php).



For Admins

Log In:
Visit http://localhost/agri-shop/admin/login.php.
Use default credentials: admin / admin123.
Important: Change the password after first login:UPDATE agri_shop.admins SET password = '$2y$10$new_hash' WHERE username = 'admin';

Generate a new hash using:echo password_hash('new_password', PASSWORD_DEFAULT);




Add Products:
After login, access http://localhost/agri-shop/admin/add_product.php.
Enter product details (name, price, category ID, description, stock, image).
Upload images to images/.


Log Out:
Click “Logout” on add_product.php or visit http://localhost/agri-shop/admin/logout.php.



Project Structure
agri-shop/
├── admin/
│   ├── login.php          # Admin login page
│   ├── add_product.php    # Add new products
│   ├── logout.php         # Admin logout
├── images/
│   └── .gitkeep           # Placeholder to track empty folder
├── cart.php               # User cart page
├── config.example.php     # Template for database config
├── database.sql           # Database schema and initial data
├── process.php            # Handles user/admin actions
├── README.md              # This file
├── .gitignore             # Excludes sensitive files
└── ...                    # Other PHP/CSS/JS files


