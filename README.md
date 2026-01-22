# 🎉 AAB EventPlanner

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-5.x-646CFF?style=for-the-badge&logo=vite&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

**A modern, full-featured web-based event management system built with Laravel 11**

[Features](#-features) • [Installation](#-installation) • [Usage](#-usage) • [Documentation](#-documentation) • [Testing](#-testing)

</div>

---

## 📋 Overview

Event Planner is a production-ready event management platform that enables organizations to create, manage, and publish events while allowing users to browse, search, and register seamlessly. Built with Laravel 11 and following modern development practices, it provides a robust foundation for event-driven applications.

### 🎯 Target Users

| Role | Access Level |
|------|--------------|
| **Admin** | Full CRUD on events, categories, users, and registrations |
| **Manager** | Read-only access to the admin panel |
| **User** | Browse events, register/unregister, manage personal profile |

---

## ✨ Features

### Core Functionality
- 📅 **Event Management** — Create, edit, archive events with images, pricing, and capacity limits
- 🏷️ **Category System** — Organize events by customizable categories
- 🎟️ **User Registration** — Simple register/unregister flow for events
- 🔍 **Search & Filters** — Find events by title, description, category, or weekday
- 👤 **Profile Management** — Update profile info, password, and avatar

### Technical Features
- 🔐 **Role-Based Access Control** — Powered by Spatie Laravel Permission
- 🖼️ **Image Uploads** — Event images and user avatars with secure storage
- 📱 **Responsive Design** — Custom CSS with mobile-first approach
- ⚡ **Vite Integration** — Fast HMR development experience
- 🔒 **CSRF Protection** — Built-in security for all forms
- 📊 **Pagination** — Efficient handling of large datasets

---

## 🖼️ Screenshots

<div align="center">

| Home Page | Event Details | Admin Panel |
|:---------:|:-------------:|:-----------:|
| ![Home](https://via.placeholder.com/300x200/FF2D20/FFFFFF?text=Home+Page) | ![Event](https://via.placeholder.com/300x200/FF2D20/FFFFFF?text=Event+Details) | ![Admin](https://via.placeholder.com/300x200/FF2D20/FFFFFF?text=Admin+Panel) |

*Replace placeholders with actual screenshots*

</div>

---

## 🛠️ Tech Stack

| Component | Technology |
|-----------|------------|
| **Framework** | Laravel 11 |
| **Language** | PHP 8.2+ |
| **Database** | MySQL / SQLite |
| **Frontend** | Blade Templates, Vanilla CSS & JS |
| **Build Tool** | Vite 5.x |
| **Authorization** | Spatie Laravel Permission ^6.24 |
| **Fonts** | Inter (Bunny Fonts) |
| **File Storage** | Laravel Storage (public disk) |

---

## 📦 Installation

### Prerequisites

- PHP 8.2 or higher
- Composer 2.x
- Node.js 18+ & npm
- MySQL 8.0+
- Git

### Step-by-Step Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/your-username/AAB_EventPlanner.git
   cd AAB_EventPlanner
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database** (edit `.env`)
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=aab_eventplanner
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run migrations and seed data**
   ```bash
   php artisan migrate --seed
   ```

7. **Create storage symlink**
   ```bash
   php artisan storage:link
   ```

8. **Build frontend assets**
   ```bash
   npm run build
   # Or for development with HMR:
   npm run dev
   ```

9. **Start the development server**
   ```bash
   php artisan serve
   ```

Visit `http://127.0.0.1:8000` to access the application.

---

## 🔑 Default Credentials

| Role | Email | Password |
|------|-------|----------|
| **Admin** | admin@eventplanner.com | admin123 |
| **Manager** | manager@eventplanner.com | manager123 |
| **User** | user@eventplanner.com | user123 |

> ⚠️ **Important:** Change these credentials in production!

---

## 📁 Project Structure

```
AAB_EventPlanner/
├── app/
│   ├── Http/
│   │   ├── Controllers/      # Application controllers
│   │   ├── Middleware/       # Custom middleware (AAB_AdminMiddleware)
│   │   └── Requests/         # Form request validation
│   ├── Models/               # Eloquent models
│   └── Providers/            # Service providers
├── config/                   # Configuration files
├── database/
│   ├── factories/            # Model factories
│   ├── migrations/           # Database migrations
│   └── seeders/              # Database seeders
├── public/                   # Public assets & entry point
├── resources/
│   ├── css/                  # Stylesheets
│   ├── js/                   # JavaScript files
│   └── views/                # Blade templates
├── routes/
│   └── web.php               # Web routes
├── storage/                  # File storage
└── tests/                    # Test suites
```

### Key Models

| Model | Purpose |
|-------|---------|
| `User` | User accounts with roles and avatars |
| `AAB_Event` | Event entities with all details |
| `AAB_Category` | Event categories |
| `AAB_Registration` | User-Event pivot table |

### Database Relationships

```
users (1) ─────────────────────── (N) aab_events
          created_by                  

users (N) ─────────────────────── (N) aab_events
          aab_registrations (pivot)

aab_categories (1) ────────────── (N) aab_events
                   category_id
```

---

## 🚀 Usage

### Public Access
- Browse events at `/home`
- View event details at `/events/{id}`
- Search and filter events by category or weekday

### Authenticated Users
- Register for events
- View registrations at `/my-registrations`
- Manage profile at `/profile`

### Admin Panel
Access `/admin/events` to manage:
- **Events** — Create, edit, archive
- **Categories** — Organize events
- **Users** — Manage user accounts
- **Registrations** — View all registrations

---

## ⚙️ Configuration

### Environment Variables

| Variable | Description | Default |
|----------|-------------|---------|
| `APP_NAME` | Application name | Laravel |
| `APP_ENV` | Environment (local/production) | local |
| `DB_CONNECTION` | Database driver | sqlite |
| `FILESYSTEM_DISK` | Default file storage | local |

### Image Upload Settings
- **Max Size:** 2MB
- **Allowed Types:** jpeg, png, jpg, gif
- **Storage Path:** `storage/app/public/events/`

---

## 🔒 Security Features

- ✅ **CSRF Protection** on all forms
- ✅ **Password Hashing** using bcrypt
- ✅ **Role-Based Access Control** with middleware
- ✅ **Input Validation** using Form Request classes
- ✅ **SQL Injection Prevention** via Eloquent ORM
- ✅ **XSS Protection** through Blade's auto-escaping
- ✅ **Session Security** with regeneration on login

---

## 📈 Performance Optimizations

- Route caching for production: `php artisan route:cache`
- Config caching: `php artisan config:cache`
- View caching: `php artisan view:cache`
- Eager loading relationships to prevent N+1 queries
- Pagination with 12 items per page

---

## 🧪 Testing

Comprehensive testing documentation is available in [TESTING.md](TESTING.md).

```bash
# Run PHPUnit tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific test suite
php artisan test --testsuite=Feature
```

---

## 🗺️ Roadmap

- [ ] Email notifications for event reminders
- [ ] Event calendar view
- [ ] Payment gateway integration
- [ ] Social login (Google, Facebook)
- [ ] Multi-language support (i18n)
- [ ] API endpoints for mobile apps
- [ ] Event QR code tickets
- [ ] Analytics dashboard

---

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 👏 Credits

- **Framework:** [Laravel](https://laravel.com/)
- **Authorization:** [Spatie Laravel Permission](https://github.com/spatie/laravel-permission)
- **Icons:** [Bunny Fonts](https://fonts.bunny.net/)
- **Build Tool:** [Vite](https://vitejs.dev/)

---

<div align="center">

**Made with ❤️ using Laravel 11**

[⬆ Back to Top](#-aab-eventplanner)

</div>
