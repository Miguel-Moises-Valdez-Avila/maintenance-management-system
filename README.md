# 🔧 Maintenance Management System — Web & Mobile

A full-stack **Preventive & Corrective Maintenance Management System** built as a graduation project. It combines a Laravel web admin panel with a native Android app (Kotlin) and Firebase Realtime Database, enabling organizations to manage, assign, and track maintenance reports in real time.

---

## 🏗️ System Architecture

```
📱 Mobile App (Kotlin - Android)
    └── Staff creates maintenance report
            ↓
    🔥 Firebase Realtime Database
            ↓
    🌐 Laravel Web Panel
        ├── Superadmin → assigns report to Admin
        └── Admin → resolves and closes the report
```

---

## 🧩 Features

### 📱 Mobile App (Kotlin)
- ✅ **Create maintenance reports** — staff submits preventive or corrective issues
- ✅ **Real-time notifications** — instant alerts when report status changes
- ✅ **Maintenance history** — full log of past and current reports
- ✅ **Report ID tracking** — unique identifier for every report
- ✅ **Status traffic light** 🟢🟡🔴 — visual indicator of report progress
- ✅ **Report vault (baúl)** — archive of completed and closed reports

### 🌐 Web Panel (Laravel)
- ✅ **3-tier role system** — Staff / Admin / Superadmin
- ✅ **Dynamic report assignment** — Superadmin assigns reports to responsible Admin
- ✅ **Real-time status tracking** — live updates via Firebase
- ✅ **Complete report lifecycle** — Open → Assigned → In Progress → Resolved → Closed
- ✅ **Admin dashboard** — full visibility of all active and historical reports

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Mobile | Kotlin (Android Native) |
| Web Backend | Laravel 12, PHP 8.x |
| Web Frontend | Blade, Tailwind CSS |
| Database | Firebase Realtime Database |
| Auth | Firebase Authentication |
| Real-time | Firebase Cloud Messaging (Push Notifications) |
| Version Control | Git + GitHub |

---

## 👥 User Roles

| Role | Permissions |
|---|---|
| **Staff (Usuario)** | Create reports via mobile app, track status, view history |
| **Admin** | Receive assigned reports, update progress, resolve issues |
| **Superadmin** | Full system access, assign reports to admins, monitor all activity |

---

## 📊 Report Status Flow

```
🔴 Open → 🟡 Assigned → 🟠 In Progress → 🟢 Resolved → ✅ Closed
```

Each status change triggers:
- Real-time Firebase update
- Push notification to relevant users
- History log entry with timestamp

---

## 📸 Screenshots

> Add screenshots of your mobile app (report creation, status view, notifications) and web panel (dashboard, report assignment, admin view) here

---

## ⚙️ Installation & Setup

### Web Panel (Laravel)

```bash
# Clone the repository
git clone https://github.com/Miguel-Moises-Valdez-Avila/maintenance-management-system.git
cd maintenance-management-system

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Add your Firebase credentials to .env
FIREBASE_PROJECT_ID=your_project_id
FIREBASE_DATABASE_URL=your_database_url

# Run migrations
php artisan migrate --seed

# Start the server
php artisan serve
npm run dev
```

### Mobile App (Kotlin)
1. Open `/mobile` folder in **Android Studio**
2. Add your `google-services.json` from Firebase Console
3. Sync Gradle and run on emulator or physical device (API 21+)

---

## 🗺️ Roadmap

- [ ] Upload to GitHub (web + mobile repos)
- [ ] Deploy Laravel panel to AWS EC2 + RDS
- [ ] Migrate Firebase DB to Amazon RDS for production
- [ ] Add photo/evidence attachment to reports
- [ ] Add GPS location tagging to reports
- [ ] Publish Android app to Google Play Store
- [ ] Add Docker + CI/CD with GitHub Actions

---

## 🎓 Academic Context

This project was developed as a **graduation thesis** for the degree of:

> **B.Eng. in Computer Systems Engineering**
> TecNM — Campus Chetumal
> Specialization: Web & Mobile Development
> Year: 2026

---

## 👨‍💻 Author

**Miguel Moisés Valdez Ávila**
Fullstack Developer | Laravel · React · Kotlin · Firebase · AWS in progress

[![LinkedIn](https://img.shields.io/badge/LinkedIn-Connect-blue)](https://www.linkedin.com/in/miguel-moises-valdez-avila)
[![GitHub](https://img.shields.io/badge/GitHub-Follow-black)](https://github.com/Miguel-Moises-Valdez-Avila)
