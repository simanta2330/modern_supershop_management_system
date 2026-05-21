 <img width="2172" height="724" alt="banner" src="https://github.com/user-attachments/assets/c2995e1e-7c8d-46d9-8861-f8d71b035e29" />




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
## Project Structure

```text
/modern_supershop_management_system
│
├── /admin
│   ├── dashboard.php
│   ├── products.php
│   ├── add_product.php
│   ├── edit_product.php
│   ├── delete_product.php
│   ├── categories.php
│   ├── customers.php
│   ├── employees.php
│   ├── inventory.php
│   ├── sales.php
│   ├── reports.php
│   ├── settings.php
│   └── logout.php
│
├── /assets
│   ├── banner.png
│   ├── logo.png
│   ├── dashboard.png
│   ├── login.png
│   └── invoice.png
│
├── /css
│   ├── style.css
│   ├── admin.css
│   ├── dashboard.css
│   └── responsive.css
│
├── /js
│   ├── app.js
│   ├── dashboard.js
│   ├── validation.js
│   └── search.js
│
├── /database
│   ├── supershop.sql
│   └── db_connect.php
│
├── /includes
│   ├── header.php
│   ├── footer.php
│   ├── sidebar.php
│   ├── navbar.php
│   ├── auth.php
│   ├── config.php
│   └── functions.php
│
├── /uploads
│   ├── products
│   ├── invoices
│   └── profiles
│
├── /screenshots
│   ├── dashboard.png
│   ├── products.png
│   ├── inventory.png
│   ├── billing.png
│   └── reports.png
│
├── index.php
├── login.php
├── register.php
├── dashboard.php
├── invoice.php
├── profile.php
├── logout.php
├── .env
├── README.md
└── LICENSE
```
```

---

## Installation & Setup (Windows)

### 1. Clone Repository

```bash
git clone https://github.com/simanta2330/modern_supershop_management_system
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
  <img width="1891" height="978" alt="Screenshot 2026-05-20 020850" src="https://github.com/user-attachments/assets/754e5669-1180-4de9-a8b9-9702bd028283" />
- Dashboard
  <img width="1900" height="952" alt="Screenshot 2026-05-20 020921" src="https://github.com/user-attachments/assets/cbe7c760-0253-4def-a423-3b75f7ec335b" />
- Product Management
  <img width="1902" height="960" alt="Screenshot 2026-05-20 020813" src="https://github.com/user-attachments/assets/9101a99d-3ebc-48d0-bf2c-c621cd966f39" />
- Inventory Management
  <img width="1906" height="963" alt="Screenshot 2026-05-20 021102" src="https://github.com/user-attachments/assets/20e32ed7-e36d-4931-88a7-0696e9e190b7" />
- Billing System
  <img width="1908" height="958" alt="Screenshot 2026-05-20 021132" src="https://github.com/user-attachments/assets/d17001a9-af3d-4747-b738-cbee18245b2d" />
- Sales Reports
  <img width="1885" height="952" alt="image" src="https://github.com/user-attachments/assets/69233a2d-264a-442a-960a-59112da3f5b3" />
- Admin Dashboard
  <img width="1883" height="956" alt="image" src="https://github.com/user-attachments/assets/4e18858b-79e4-493e-b24a-ce8bc05ed6b8" />

---

## Future Works

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
