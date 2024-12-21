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

---

## Deployment Instructions

Follow these steps to deploy the application to a production environment:

### 1. Set Up the Production Server

Ensure the production server has the following installed:

- PHP (>= 8.0)
- Composer
- A web server (e.g., Nginx or Apache)
- Node.js (for building assets, optional)

### 2. Clone the Repository

```bash
git clone <repository-url>
cd <project-directory>
```

### 3. Install Dependencies

Run the following commands:

```bash
composer install --optimize-autoloader --no-dev
npm install
npm run build
```

### 4. Configure Environment Variables

Copy the `.env.example` file to `.env` and configure the settings appropriately for production.

### 5. Set Up Database

Run migrations and, if needed, seed the database:

```bash
php artisan migrate --force
php artisan db:seed --force
```

### 6. Set Permissions

Ensure correct permissions for storage and cache directories:

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 7. Optimize the Application

Run the following commands to optimize the application for production:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 8. Configure the Web Server

Set up your web server to point to the `public` directory of the Laravel application.

### 9. Queue Worker (Optional)

If your application uses queues, start the queue worker:

```bash
php artisan queue:work --daemon
```

Your application is now deployed and ready for production use!

## Additional Notes

- Ensure file permissions are properly set if you're working on a Linux system.
- Use version control best practices for `.env` and database files.

You're now ready to start developing or deploying!



