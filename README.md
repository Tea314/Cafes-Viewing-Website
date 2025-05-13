# Cafes‑Viewing‑Website

A web application that lets users discover, save, and propose cafés, while giving admins full control via a dashboard.

## Overview

**Cafes‑Viewing‑Website** (a.k.a. “What is Your Taste?”) is a responsive, secure platform where:

- **Registered Users** can search for approved cafés by name or district, save favorites, report errors or suggest edits, and propose new cafés.  
- **Guests** can browse approved cafés without logging in.  
- **Administrators** manage pending cafés, user reports, approved cafés, and user accounts through an admin dashboard.

## Features

- **User Registration & Authentication**  
  Sign up or log in via email/password or social media (Google, Facebook); passwords are securely hashed. 

- **Cafe Browsing & Search**  
  Dynamic search by name or district, paginated results, and detailed café modals (images, description, tags).

- **Favorites**  
  Save and manage a personal list of favorite cafés.

- **Error Reporting & Proposals**  
  Submit error reports or new‑café proposals via modals; admins review and approve or reject. 

- **Admin Dashboard**  
  • View/approve/reject pending cafés and reports  
  • Add/edit/delete approved cafés  
  • Manage user accounts (view, suspend, delete)  
  • Configure system settings (districts, tags) 

## Technologies

- **Frontend:** HTML, CSS, JavaScript, Bootstrap   
- **Backend:** PHP (procedural scripts for authentication, café management, reporting, and favorites)
- **Database:** MySQL (InnoDB tables for users, cafés, districts, tags, favorites, reports)  
- **Tools:** VS Code, XAMPP (local PHP/MySQL), Git

## Installation

1. **Clone the repository**  
   ```bash
   git clone https://github.com/yourusername/Cafes‑Viewing‑Website.git
   cd Cafes‑Viewing‑Website
   ```

2. **Set up the database**  
   - Create a MySQL database (e.g., `cafe_directory`).  
   - Import the provided SQL schema and seed data.

3. **Configure**  
   - Copy `config.php.example` to `config.php`.  
   - Edit `config.php` with your database host, name, username, and password.

## Default Accounts

Upon first run, an **admin** account is automatically created. Please use the credentials defined in `config.php` (or update them before deployment).  
- **Username:** `admin`  
- **Password:** `Admin1234#`

> **Tip:** Change these immediately in your configuration for production.

## Usage

- **End Users:**  
  1. Open the site in your browser.  
  2. Click **Register** to create an account—only registered users can save favorites, report errors, or propose cafés.  
- **Administrators:**  
  1. Log in at `/admin.php` using the admin credentials above.  
  2. Access the dashboard to manage cafés, reports, and users.

## Project Structure

```
/css        ── Stylesheets (Bootstrap overrides)
/js         ── Client‑side scripts (search, modals, form validation)
/images     ── Café and UI images
/config.php ── Database connection settings
/index.php  ── Home/Search page
/favorites.php
/propose.php
/admin.php  ── Admin dashboard entry
/controllers/
/models/
```

## Future Plans

- Deploy to a live hosting platform with a custom domain.  
- Integrate a chatbot for user support.  
- Add personalized café recommendations and loyalty programs.  
- Migrate to cloud hosting for greater scalability.

---

Enjoy building and managing your Café Review website! If you run into any issues, check your database settings in `config.php` and ensure your tables are correctly imported.
