![Banner](assets/<img width="3000" height="1971" alt="banner" src="https://github.com/user-attachments/assets/6f7346ae-4aaa-4d4c-83d3-dd39aa059dd6" />
)

# Modern Supershop Management System

A scalable, secure, and role-based Supershop Management System built to digitize product management, inventory tracking, billing, customer handling, and sales operations. This web application includes a structured backend, responsive frontend, and multi-role access system for seamless supermarket management.

---

## Repository

GitHub: https://github.com/simanta2330/modern_supershop_management_system

---

## Core Features

### Admin

- Create and manage products
- Manage categories and stock
- Add and manage employees
- Configure inventory system
- View dashboard and analytics
- Manage customer records
- Generate invoices
- Configure sales settings
- Generate sales reports
- Track transactions

### Employee

- Process customer sales
- Generate invoices and bills
- Update stock information
- Search products quickly
- Manage customer purchases
- View assigned tasks

### Customer

- View purchase history
- Receive invoices
- Track purchased products
- Access order information

---

## Tech Stack

### Frontend

- HTML
- CSS
- JavaScript
- Bootstrap

### Backend

- PHP

### Database

- MySQL

### Development Tools

- XAMPP
- VS Code
- GitHub

---

## Project Structure

```text
/modern_supershop_management_system
│── /admin
│── /assets
│── /css
│── /js
│── /database
│── /includes
│── /uploads
│── index.php
│── login.php
│── dashboard.php
│── README.md
```

---

## Installation & Setup (Windows)

### 1. Clone Repository

```bash
git clone https://github.com/simanta2330/modern_supershop_management_system.git
cd modern_supershop_management_system
```

### 2. Install XAMPP

Download and install XAMPP on your computer.

### 3. Move Project Folder

Move the project folder into the `htdocs` directory.

Example:

```text
C:\xampp\htdocs\
```

### 4. Start Apache and MySQL

Open XAMPP Control Panel and start:

- Apache
- MySQL

### 5. Configure Database

- Open phpMyAdmin
- Create a new database
- Import the provided `.sql` file

### 6. Run the Project

Open browser and visit:

```text
http://localhost/modern_supershop_management_system
```

---

## Default Login Information

### Admin Login

```text
Username: abul Kasem
Password: 123456
```

You can change credentials from the database or admin panel.

---

## System Modules

### Authentication Module

Provides secure login and user authentication system.

### Product Management Module

Handles product creation, updates, deletion, and category management.

### Inventory Management Module

Tracks stock levels and automatically updates inventory after sales.

### Sales Management Module

Handles billing, invoices, customer purchases, and transaction processing.

### Customer Management Module

Stores and manages customer information and purchase records.

### Report Module

Generates sales reports and transaction summaries for business analysis.

---

## API Overview

### Authentication

| Method | Endpoint | Description |
|--------|-----------|-------------|
| POST | /login | Login for admin and employees |
| POST | /register | Create user account |

### Products

| Method | Endpoint | Description |
|--------|-----------|-------------|
| POST | /products/add | Add new product |
| GET | /products | View all products |
| PUT | /products/update | Update product |

### Sales

| Method | Endpoint | Description |
|--------|-----------|-------------|
| POST | /sales/create | Create sales invoice |
| GET | /sales/history | View sales history |

---

## Screenshots

- Login Page
- Dashboard
- Product Management
- Inventory Management
- Billing System
- Sales Reports

---

## Roadmap

- Barcode Scanner Integration
- Online Payment Gateway
- Mobile Application Support
- Advanced Sales Analytics
- Multi-Branch Management
- SMS and Email Notifications
- Cloud Backup System

---

## Contribution Guide

1. Fork the repository  
2. Create a new branch  
3. Commit and push changes  
4. Open a pull request  

---

## Security Notes

- Never expose database credentials
- Use strong admin passwords
- Sanitize all user inputs
- Configure proper session security
- Restrict unauthorized access

---

## License

MIT License

---

## Team Members

- Simanta Mondal (Team Leader)
- Rejoan Ahmed
- Maruf Billah Sourav
- Mahmuda Ety

---

## Contact

Email: cyber.simanta53@gmail.com

---

## About

Modern Supershop Management System

A complete web-based supermarket management solution developed using PHP and MySQL for efficient inventory, billing, and sales management.ers a better management experience through automation and modern web technologies.
