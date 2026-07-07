# Tigray Volleyball Federation Website

Minimal scaffold for the TVT website: frontend pages, PHP backend skeleton and SQL schema.

## Setup

1. Create a MySQL database and user matching `backend/config.php` or update the file.
2. Import `sql/schema.sql` into MySQL.
3. Serve the folder with a PHP-enabled webserver (e.g. using `php -S localhost:8000 -t .`).

## Features Implemented

- **Frontend**: Homepage, About, responsive design with Bootstrap 5
- **Admin Auth**: Login/logout with session management and password hashing
- **News Management**: Full CRUD (create, read, update, delete) with auditing
- **User Management**: Create/update/delete users, assign roles
- **Role Management**: Create/update/delete roles, seed default roles
- **Permission System**: Role-based permission checks (manage_users, manage_news, manage_roles, manage_events, etc.)
- **Activity Logging**: All admin actions logged to `activity_logs` table for auditing
- **Events Management**: Full CRUD for events with types (match, tournament, training, workshop), status tracking, results
- **Placeholder Admin Pages**: Events, Teams, Players, Fixtures, Standings, Gallery, Sponsors, Documents, Courses, Memberships, Contact Messages, Newsletter, Settings, SEO, Backup/Restore
- **Dashboard**: Admin dashboard with navigation to all management sections

## Next Steps

- Implement CRUD for Teams, Players, Fixtures/Results
- Add rich-text editor (TinyMCE/CKEditor) for news and event descriptions
- Add file uploads for gallery, sponsors logos, and documents
- Add pagination and search to list pages
- Implement permission management UI to edit role_permissions
- Add more frontend pages (Teams, Players, Gallery, etc.)
- Implement multilingual support (English + Tigrinya)

## Admin Credentials

After setup, create an admin user via CLI:
```bash
php backend/create_admin.php admin@example.com Admin@123 "Administrator"
```

Access admin panel: `http://localhost:8000/admin/login.php`

# Career