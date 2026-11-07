# ⏱️ Laravel Post Scheduler & Automated Mailer

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11%20%7C%2012-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-%5E8.3-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/Task-Scheduling-059669?style=for-the-badge&logo=clock&logoColor=white" alt="Scheduler">
  <img src="https://img.shields.io/badge/Mail-Responsive%20RTL-blue?style=for-the-badge&logo=gmail&logoColor=white" alt="Mail">
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="License">
</p>

---

## 📌 Overview

An automated background task scheduling and notification system built with **Laravel**. The application automatically monitors pending draft posts, transitions their status to active, updates timestamps, and dispatches a responsive HTML email report (with RTL Arabic typography) containing publication metrics to the administrator.

---

## ✨ Key Features

- ⏰ **Automated Task Scheduling:** Configured in `routes/console.php` with dedicated timezone management (`Africa/Cairo`).
- 📝 **Automated Post Publishing:** Automatically queries draft posts (`status: draft`), updates their status to `active`, and sets the `published_at` timestamp.
- 📬 **Responsive Email Notifications:** Sends a beautifully styled HTML email via `PublishPostMail` to notify administrators of the total published count.
- 🛡️ **Concurrency & Overlap Protection:** Configured with `withoutOverlapping()`, `runInBackground()`, and `onOneServer()` to avoid race conditions and redundant execution.
- 📜 **Heartbeat & Logging:** Includes a recurring logger command (`app:write-log`) running every minute for monitoring scheduler health.
- 🧪 **Built-in Factories & Seeders:** Includes pre-configured factories and seeders to populate dummy posts for immediate testing.

---

## 🔄 System Workflow

```
[ Laravel Scheduler / Cron ]
             │
             ├──▶ (Every Minute) ──▶ app:write-log     ──▶ Write timestamp to Laravel Log
             │
             └──▶ (Weekly)       ──▶ app:publish-posts
                                            │
                                            ├──▶ Query posts where status = 'draft'
                                            ├──▶ Update status to 'active' & set published_at
                                            ├──▶ Log operation details
                                            └──▶ Send email summary to Admin (PublishPostMail)
```

---

## ⚙️ Prerequisites

- **PHP**: `^8.3` or higher
- **Composer**: `^2.x`
- **Database**: MySQL, MariaDB, PostgreSQL, or SQLite
- **Node.js & NPM**: (Optional, for building frontend assets)

---

## 🚀 Installation & Quick Start

### 1. Clone the Repository
```bash
git clone https://github.com/your-username/your-repo-name.git
cd your-repo-name
```

### 2. Install Dependencies
```bash
composer install
npm install && npm run build
```

### 3. Environment Setup
Copy the environment example file and generate an application key:
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database & Mail Configuration
Open `.env` and configure your database and mail settings:

```env
# Database Settings
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=schedule
DB_USERNAME=root
DB_PASSWORD=

# Mail Settings (e.g. Mailpit, Mailtrap, or SMTP)
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="no-reply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

> 💡 **Local Mail Testing Tip:** You can set `MAIL_MAILER=log` to view outgoing emails directly inside `storage/logs/laravel.log`, or use a local SMTP viewer such as **Mailpit** or **Mailtrap**.

### 5. Run Migrations & Seeders
Execute the migrations and populate sample draft posts for testing:
```bash
php artisan migrate --seed
```

---

## 💻 Available Commands & Scheduling

### 1️⃣ Custom Artisan Commands

You can run and test the application's commands manually at any time:

| Command | Description |
| :--- | :--- |
| `php artisan app:publish-posts` | Manually publish all draft posts and send the email notification |
| `php artisan app:write-log` | Write a test heartbeat entry to `storage/logs/laravel.log` |

---

### 2️⃣ Schedule Execution Commands

#### 🔸 List All Scheduled Tasks
Inspect all scheduled jobs, intervals, and their next due execution times:
```bash
php artisan schedule:list
```

#### 🔸 Run Scheduler Locally (Development Worker)
Keeps the scheduler daemon running in your terminal, evaluating tasks every minute:
```bash
php artisan schedule:work
```
> 💡 **Recommended:** Use this command during local development and testing (e.g. Laragon, Valet, or Localhost).

#### 🔸 Run Due Tasks Once
Manually execute all tasks that are currently due for execution:
```bash
php artisan schedule:run
```

---

### 3️⃣ Production Server Setup (Cron Job)

On a Linux production server (VPS, Cloud Server, cPanel), configure a single cron entry to execute every minute:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

---

## 📧 Email Notification Preview

When draft posts are published, an email is dispatched to the administrator with publication statistics:

- **Subject:** `تم نشر المقالات بنجاح` *(Posts Published Successfully)*
- **View Template:** `resources/views/emails/post-published.blade.php`

### 🖼️ Visual Email Mockup:

```text
┌─────────────────────────────────────────────────────────────┐
│                      ✓ تم النشر بنجاح!                      │
│                      نظام إدارة المحتوى                     │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│   مبروك! 🎉                                                 │
│   تمت عملية نشر المقالات بنجاح.                             │
│   يمكنك الآن مراجعة النتائج من لوحة التحكم.                 │
│                                                             │
│   ┌─────────────────────────────────────────────────────┐   │
│   │                عدد المقالات المنشورة                │   │
│   │                         5                           │   │
│   │                 مقال تم نشره بنجاح                  │   │
│   └─────────────────────────────────────────────────────┘   │
│                                                             │
│   شكرًا لاستخدامك نظامنا. نتمنى لك تجربة مميزة!             │
│                                                             │
├─────────────────────────────────────────────────────────────┤
│          هذه رسالة تلقائية، يرجى عدم الرد عليها.            │
│                 © 2026 نظام إدارة المحتوى                   │
└─────────────────────────────────────────────────────────────┘
```

#### Email Elements Breakdown:
- **Header:** Status banner with success confirmation.
- **Body:** Success greeting and dashboard confirmation notice.
- **Metric Card:** Prominently highlights the exact count of published posts (`{{ $publishedCount }}`).
- **Footer:** Automated no-reply notice and dynamic copyright year.

---

## 📂 Project Structure

```
schedule/
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       ├── PublishPosts.php         # Core command: queries drafts, updates DB, sends mail
│   │       └── WriteLog.php             # Periodic heartbeat logger command
│   ├── Mail/
│   │   └── PublishPostMail.php          # Mailable class passing metrics to the view
│   └── Models/
│       └── Post.php                     # Post model (title, description, status, published_at)
├── database/
│   ├── factories/
│   │   └── PostFactory.php              # Factory generating mock posts
│   ├── migrations/
│   │   └── *_create_posts_table.php     # Schema definition for posts table
│   └── seeders/
│       └── PostSeeder.php               # Seeder creating sample draft/active posts
├── resources/
│   └── views/
│       └── emails/
│           └── post-published.blade.php # Clean RTL HTML email template
└── routes/
    └── console.php                      # Schedule definitions & cron frequencies
```

---

## 🛠️ Customization & Configuration

### 1. Changing the Recipient Email Address
Update the recipient email address inside `app/Console/Commands/PublishPosts.php`:
```php
Mail::to('your-email@example.com')->send(new PublishPostMail($publishedCount));
```

### 2. Adjusting Schedule Frequencies & Timezone
Modify the execution frequency and timezone inside `routes/console.php`:
```php
// Example: Run daily instead of weekly
Schedule::command('app:publish-posts')
    ->timezone('Africa/Cairo')
    ->daily() // Available options: everyMinute(), hourly(), daily(), weekly(), etc.
    ->withoutOverlapping()
    ->runInBackground()
    ->onOneServer();
```

---

## 📄 License

This project is open-sourced software licensed under the [MIT License](LICENSE).
