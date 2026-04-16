# Business Management System (Laravel)

A Business management system built with Laravel, designed to manage products, purchases, sales, and reporting efficiently. The system supports offline/local deployment and can be extended to a hybrid (offline + cloud sync) model.

---

## 🚀 Features

* Dashboard Summary
* Product Module
* Expenses
* Sales Management
* Stock/Inventory Tracking
* Rentals
* User Authentication (Login System)

---

## 🛠️ Built With

* PHP (Laravel Framework)
* MySQL Database
* HTML, CSS, Bootstrap
* JavaScript

---

## ⚙️ Installation Guide

### 1. Clone the Repository

```bash
git clone https://github.com/Fiona-alice/bms.git
cd bms
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Setup Environment File

```bash
cp .env.example .env
```

Then update `.env` with your database details:

```env
DB_DATABASE=your_database_name
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. Start the Server

```bash
php artisan serve
```

Visit:

```
http://127.0.0.1:8000
```

---

## 🗄️ Database

* Import the provided `.sql` file into MySQL (via phpMyAdmin or CLI)
* Ensure database name matches `.env`

---

## 📦 Deployment Options

* Local deployment using XAMPP (offline use)
* Cloud deployment (e.g., Render, VPS)
* Hybrid deployment (offline + sync)

---

## 🔐 Default Login (Optional)

```
User: Clare
Password: 12345678
```

*(Change after first login for security)*

---

## 📌 Notes

* Ensure Apache and MySQL are running (XAMPP)
* Run `php artisan storage:link` if images are not displaying
* Make sure `/storage` and `/bootstrap/cache` are writable

---

## 📄 License

This project is intended for commercial use.

---

## 👩‍💻 Author

Developed by Fiona Alice 
