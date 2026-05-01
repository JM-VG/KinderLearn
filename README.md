# KinderLearn - Child-Friendly Learning Platform

A complete Laravel + MySQL web application for early childhood education.

---

## REQUIREMENTS BEFORE STARTING

- PHP 8.1 or higher
- Composer (https://getcomposer.org)
- MySQL 5.7 or higher
- Node.js 18+ and NPM
- A web server (Apache or Nginx) or use `php artisan serve` for local

---

## STEP 1: INSTALL LARAVEL AND DEPENDENCIES

```bash
# Create a new Laravel project
composer create-project laravel/laravel kinderlearn

# Go into the project folder
cd kinderlearn

# Install Breeze for authentication
composer require laravel/breeze --dev

# Install Node dependencies and build assets
npm install
```

---

## STEP 2: COPY ALL PROJECT FILES

Copy all files from this package into your `kinderlearn` folder.
Replace any existing files when prompted.

---

## STEP 3: SET UP THE DATABASE

1. Open MySQL and create a database:

```sql
CREATE DATABASE kinderlearn;
```

2. Copy `.env.example` to `.env`:

```bash
cp .env.example .env
```

3. Open `.env` and fill in your database details:

```
DB_DATABASE=kinderlearn
DB_USERNAME=root
DB_PASSWORD=your_password_here
```

---

## STEP 4: RUN MIGRATIONS AND SEEDERS

```bash
# Generate app key
php artisan key:generate

# Run all database migrations (creates all tables)
php artisan migrate

# Seed the database with sample data
php artisan db:seed

# Link storage folder (for file uploads)
php artisan storage:link
```

---

## STEP 5: BUILD FRONTEND ASSETS

```bash
npm run build
```

---

## STEP 6: START THE LOCAL SERVER

```bash
php artisan serve
```

Open your browser and go to: http://localhost:8000

---

## DEFAULT ACCOUNTS (after seeding)

| Role    | Email                    | Password  |
|---------|--------------------------|-----------|
| Admin   | admin@kinderlearn.com    | password  |
| Teacher | teacher@kinderlearn.com  | password  |
| Student | student@kinderlearn.com  | password  |

---

## STEP 7: DEPLOYMENT TO INFINITYFREE

### 7.1 Prepare for Production

```bash
# Set APP_ENV in .env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.infinityfreeapp.com

# Optimize the app
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 7.2 Export the Database

```bash
mysqldump -u root -p kinderlearn > kinderlearn.sql
```

Upload `kinderlearn.sql` using InfinityFree's phpMyAdmin.

### 7.3 Upload Files

1. Log into InfinityFree control panel
2. Open File Manager
3. Go to `/htdocs` folder
4. Upload ALL files EXCEPT the `public` folder
5. Upload the CONTENTS of `public/` directly into `/htdocs`

### 7.4 Fix Public Folder (IMPORTANT for shared hosting)

Create a new `index.php` in `/htdocs` with this content:

```php
<?php

define('LARAVEL_START', microtime(true));
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
)->send();
$kernel->terminate($request, $response);
```

Also create/update `.htaccess` in `/htdocs`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

### 7.5 Configure .env on Server

Edit your `.env` file on the server:

```
APP_URL=https://yourdomain.infinityfreeapp.com
DB_HOST=your_infinityfree_db_host
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

### 7.6 Fix Storage and Cache

SSH in (if available) or use a setup route:

```bash
php artisan storage:link
php artisan config:clear
php artisan cache:clear
```

---

## FOLDER STRUCTURE

```
kinderlearn/
├── app/
│   ├── Http/
│   │   ├── Controllers/       # All page logic goes here
│   │   └── Middleware/        # Role-based access control
│   └── Models/                # Database models
├── database/
│   ├── migrations/            # Table definitions
│   └── seeders/               # Sample data
├── public/                    # Web root (CSS, JS, images)
├── resources/
│   └── views/                 # Blade HTML templates
│       ├── layouts/           # Main page layouts
│       ├── auth/              # Login and register pages
│       ├── student/           # Student dashboard pages
│       ├── teacher/           # Teacher dashboard pages
│       └── admin/             # Admin panel pages
└── routes/
    └── web.php                # All URL routes
```

---

## TROUBLESHOOTING

**Problem**: White screen or 500 error
**Fix**: Check `storage/logs/laravel.log` for the error message

**Problem**: CSS not loading
**Fix**: Run `npm run build` again

**Problem**: File uploads not working
**Fix**: Run `php artisan storage:link`

**Problem**: Database connection failed
**Fix**: Double-check your `.env` DB settings and restart the server

---

## FEATURES INCLUDED

- Multi-role authentication (Admin, Teacher, Student/Parent)
- Child-friendly UI with large buttons and bright colors
- 5 Learning subjects (Alphabet, Numbers, Colors, Shapes, Basic Words)
- Interactive activities with instant feedback
- Gamification (stars, badges, progress bars)
- Progress tracking for teachers and parents
- Teacher dashboard with analytics
- Class management with join codes
- Messaging system (Parent to Teacher)
- Announcements board
- Attendance tracking
- Downloadable reports
- Avatar selection system
- Mobile-first responsive design
