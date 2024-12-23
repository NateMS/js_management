# Cobra-Coach

J&S Kurs-Management Web-applikation

Dieses Tool erlaubt es, eine einfache Übersicht über anstehende und besuchte J&S Kurse der Leiter deiner Riege zu haben.

## Laravel Development Environment Setup

---

## Prerequisites

Before starting, ensure you have the following installed:

1. **PHP** (>= 8.0):
   - Check version:
     ```bash
     php -v
     ```
2. **Composer**:
   - Check version:
     ```bash
     composer -v
     ```
3. **SQLite**:
   - Confirm SQLite is available:
     ```bash
     php -m | grep sqlite
     ```
   - If missing, install SQLite or enable it in your `php.ini` file.

---

## Steps to Set Up

### 1. Clone the Repository
Clone your Laravel project repository:
```bash
git clone <repository-url>
cd <repository-folder>
```

### 2. Install Dependencies
Run the following command to install project dependencies:
```bash
composer install
```

### 3. Set Up the `.env` File
Copy the example `.env` file:
```bash
cp .env.example .env
```

Update the `.env` file for SQLite:
```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite
```

> **Note:** Replace `/absolute/path/to/database.sqlite` with the actual path to your SQLite file.

### 4. Create the SQLite Database File
Create the SQLite database file:
```bash
touch database/database.sqlite
```

### 5. Generate Application Key
Generate the application key:
```bash
php artisan key:generate
```

### 6. Run Database Migrations
Run migrations to set up the database structure:
```bash
php artisan migrate
```

### 7. Start the Development Server
Start Laravel’s built-in development server:
```bash
php artisan serve
```
By default, the application will be available at:
```
http://127.0.0.1:8000
```

### 8. Start NPM run
Generate the application key:
```bash
npm run dev
```
---

## Additional Commands

### Clear Cache
If changes to `.env` are not applied:
```bash
php artisan config:clear
```

---

## Common Issues

### 1. Missing Dependencies
Ensure you have installed all required dependencies using `composer install`.

### 2. Writable SQLite File
If you encounter permission issues, ensure `database/database.sqlite` is writable:
```bash
chmod 664 database/database.sqlite
```

### 3. Migrations Fail
Double-check the `DB_DATABASE` path in `.env`. It should point to the SQLite file.