# Student Management System

A PHP and MySQL based Student Management System for managing student information through a simple web-based interface.

## Technologies Used

* PHP
* MySQL
* HTML
* CSS
* Bootstrap
* JavaScript

## Features

* User Registration
* User Login
* Dashboard
* Student List
* Student Search
* View Student Details
* Edit Student Details
* Delete Student
* Student Profile Picture Upload
* Password Reset
* Session-based Login
* Logout
* Responsive User Interface

## Project Structure

* `login.php` - User login
* `register.php` - New user registration
* `dashboard.php` - Main dashboard
* `std_list.php` - Display and manage student records
* `std_view.php` - View student details
* `std_edit.php` - Edit student details
* `reset_password.php` - Reset user password
* `header.php` - Dashboard header
* `sidebar.php` - Dashboard sidebar
* `logout.php` - Logout from the system
* `includes/connection.php` - Database connection
* `includes/chk_login.php` - Login session checking
* `css/` - Stylesheets and Bootstrap files
* `uploads/` - Uploaded student profile pictures

## Database

This project uses **MySQL/MariaDB** as the database.

Database name:

`student_db`

The database contains the tables required to store user and student information.

## How to Run

1. Install **WAMP Server**.
2. Clone or download this repository.
3. Place the project inside:

`C:\wamp64\www\php\`

4. Start **Apache** and **MySQL** from WAMP.
5. Create the required `student_db` database in phpMyAdmin.
6. Import or create the required database tables.
7. Update the database connection details in:

`includes/connection.php`

8. Open the project in your browser:

`http://localhost/php/webpage/`

## Author

**Aleena Sabu**
