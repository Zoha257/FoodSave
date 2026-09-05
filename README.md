# FoodSave — Online Food Waste Management System

FoodSave is a PHP/MySQL web application that connects **food donors** with **NGOs/food banks** and coordinates volunteers/drivers to help redistribute surplus food before it goes to waste.

> **Current project:** This README describes the implemented application rather than the original prototype.

## Overview

FoodSave supports the surplus-food workflow from donation through pickup and delivery:

1. Donor registers/logs in.
2. Donor creates a surplus-food donation.
3. NGOs browse available donations and request pickups.
4. Donors approve or reject pickup requests.
5. Approved requests can be assigned for delivery.
6. Volunteers can apply for opportunities and manage assigned deliveries.
7. The separate driver module supports driver/task-based delivery management.
8. Delivery tracking stores progress/location information.
9. Notifications and expiration checks help keep donations moving.
10. Feedback, ratings, reporting, inventory, and food-safety guidance support the overall system.

## User Roles

### Administrator

- Admin authentication
- Dashboard/statistics
- User management
- Donor/NGO approval and account status management
- Donation management
- Donation detail views
- Reports and report export
- Feedback reports
- Food-safety guideline management
- Password reset flow

### Donor

- Registration/login
- Profile management
- Add/edit/view donations
- Donation history/status
- Review incoming pickup requests
- Approve/reject pickup requests
- Food-safety guidance
- Notifications

### NGO / Food Bank

- Registration with admin approval
- Profile management
- Browse/filter donations
- Submit pickup requests
- Specify pickup and drop-off details
- Track requests
- Mark completed pickups
- Generate pickup receipts
- Food-safety guidance
- Donation inventory
- Volunteer management
- Volunteer opportunity creation
- Volunteer application management
- Delivery assignment
- Delivery tracking

### Volunteer

- Registration/login
- Profile management
- Vehicle/license information
- Availability information
- Browse volunteer opportunities
- Apply for opportunities
- View activities/applications
- View active deliveries
- Update delivery status

### Driver

The project also contains a separate `driver/` workflow for driver/task-based delivery management:

- Driver registration/login
- Available delivery/task list
- Accept delivery tasks
- Update task status
- Driver dashboard


---

## Main Features

### Food Donations

- Donation creation/editing
- Food categories
- Quantity tracking
- Expiration-date tracking
- Pickup address/location
- Donation status management
- Automatic expiration processing

### Pickup Requests

- NGO requests food
- Donor approval/rejection
- Pickup scheduling
- Pickup/drop-off information
- Request status tracking
- Completion flow
- Pickup receipts

### Volunteer Management

- Volunteer opportunities
- Opportunity applications
- Application status management
- Volunteer profiles
- Availability information
- Vehicle information
- Volunteer assignment to deliveries
- Volunteer delivery status updates

### Driver Management

- Driver authentication
- Available delivery tasks
- Task acceptance
- Task status updates
- Driver dashboard

### Delivery Tracking

- Delivery assignments
- Delivery status updates
- Pickup/drop-off locations
- GPS latitude/longitude support
- Delivery location endpoint
- Active delivery views

### Inventory & Reports

- Donation inventory
- Donation statistics
- Category/location breakdowns
- Expiring donations
- Admin reports
- Report export
- Feedback reporting

### Notifications & Email

- In-app notifications
- Read/unread handling
- Pickup-request notifications
- Donation-expiration notifications
- Notification helper/header
- HTML email templates

### Food Safety

Admin-managed food-safety guidelines are displayed to donors and NGOs.

---

## Technology Stack

| Component | Technology |
|---|---|
| Backend | PHP |
| Database | MySQL |
| Database access | PDO |
| Frontend | HTML5, CSS3, JavaScript |
| UI | Bootstrap 5 |
| Icons | Font Awesome |
| Maps | Google Maps JavaScript API |
| Web server | Apache |
| Local development | XAMPP |
| API authentication | JWT |
| API testing | Postman |

---

## Project Structure

```text
FoodSave/
├── admin/
├── api/
├── assets/
│   ├── css/
│   └── js/
├── cron/
├── database/
├── donor/
├── driver/
├── email_templates/
├── includes/
│   └── classes/
├── ngo/
├── volunteer/
├── feedback.php
├── index.php
├── login.php
├── postman_collection.json
├── .env.example
├── .gitignore
├── SECURITY.md
└── README.md
```

Important implementation areas:

```text
admin/                 Admin panel
donor/                 Donor workflow
ngo/                   NGO/food-bank workflow
volunteer/             Volunteer workflow
driver/                Driver/task workflow — driver acceptance, status transitions, GPS tracking, and delivery history
api/                   REST-style API
includes/classes/      Reusable PHP classes
database/              Database schema/migrations
cron/                  Scheduled expiration checker
assets/                CSS and JavaScript
email_templates/       HTML email templates
```

---

# Installation — XAMPP

## 1. Install XAMPP

Install XAMPP with:

- Apache
- MySQL

## 2. Copy FoodSave

Place the project inside XAMPP's `htdocs`.

Example:

```text
C:\xampp\htdocs\FoodSave\
```

## 3. Start Apache and MySQL

Open XAMPP Control Panel and start:

```text
Apache
MySQL
```

## 4. Import the database

Open:

```text
http://localhost/phpmyadmin
```

Import:

```text
database/foodsave_db.sql
```

The main database is:

```text
foodsave_db
```

## 5. Configure environment values

Copy:

```text
.env.example
```

to:

```text
.env
```

Then configure:

```text
DB_HOST=localhost
DB_NAME=foodsave_db
DB_USER=root
DB_PASSWORD=

GOOGLE_MAPS_API_KEY=your_restricted_key
JWT_SECRET=your_long_random_secret
API_ALLOWED_ORIGINS=http://localhost
```

The real `.env` is ignored by Git and should **never** be committed.

FoodSave includes a small environment loader in:

```text
includes/env.php
```

so XAMPP can read the local `.env` file without requiring an additional PHP package.

## Driver and Delivery Workflow

After a donor approves an NGO pickup request, FoodSave creates a pending delivery task. An NGO can assign an available driver, or an available driver can accept a pending task from the Driver Task Board. Drivers can update `picked_up`, `in_transit`, and `completed` states and optionally submit GPS coordinates; the latest coordinates are available to NGO delivery tracking. Existing volunteer delivery assignments remain supported alongside driver deliveries.

Administrators can add/edit donor, NGO, and driver accounts from `admin/user_form.php`. Driver accounts include vehicle and license details.

## 6. Open the application

If the folder is named `FoodSave`:

```text
http://localhost/FoodSave/
```

If you use a different folder name, use that name in the URL.

---

# Google Maps Configuration

Google Maps is used by the project for location/map functionality, including donation and delivery views.

The API key is now read from:

```text
GOOGLE_MAPS_API_KEY
```

and is **not stored in `includes/config.php`**.

---

# API

FoodSave includes a REST-style API under:

```text
/api
```

For a local `FoodSave` installation:

```text
http://localhost/FoodSave/api
```

## Authentication

JWT authentication is supported through:

```http
Authorization: Bearer <token>
```

JWT signing now uses the environment variable:

```text
JWT_SECRET
```

A real deployment must provide a strong random JWT secret.

## Main API areas

### Authentication

```text
POST /api/auth/login
POST /api/auth/register
POST /api/auth/refresh
```

### Donations

```text
GET  /api/donations
GET  /api/donations/{id}
POST /api/donations
PUT  /api/donations/{id}/status
```

### Pickup

```text
GET  /api/pickup
POST /api/pickup
PUT  /api/pickup/{id}/status
```

### Volunteer

```text
GET /api/volunteer
POST /api/volunteer
POST /api/volunteer/apply
PUT /api/volunteer/{id}/status
```

### Inventory

```text
GET /api/inventory
```

The exact request/response examples are also represented in:

```text
postman_collection.json
```

---

# Postman

Import:

```text
postman_collection.json
```

into Postman.

Update the collection's base URL to match your installation, for example:

```text
http://localhost/FoodSave
```

Some requests contain development-era example data, so update IDs/dates to match the records in your current database.

---

# Automatic Expiration Checking

The project includes:

```text
cron/check_expirations.php
```

It checks donations approaching expiration and handles expired available donations/notifications.

For production, schedule it periodically.

Example Linux cron:

```cron
0 * * * * php /path/to/FoodSave/cron/check_expirations.php
```

On Windows/XAMPP, use Windows Task Scheduler.

---

# Maps & Location Features

Google Maps functionality appears in areas such as:

- Donation browsing
- Donation detail pages
- User/donation location views
- Delivery tracking
- Active delivery location information

Main map JavaScript:

```text
assets/js/maps.js
```

A valid restricted Google Maps API key is required.

---

# Notifications

Relevant implementation files include:

```text
includes/classes/Notification.php
includes/check_notifications.php
includes/mark_notification_read.php
includes/mark_all_notifications_read.php
includes/notification_header.php
```

The system supports notification creation, unread/read handling, pickup-related alerts, and expiration-related notifications.

---

# Email

Email-related code/templates are located in:

```text
email_templates/
includes/classes/Email.php
```

Templates currently include:

```text
donation_expiry.html
pickup_request.html
```

Production deployment should use a properly configured SMTP/mail provider.

---

# Security

The application includes several security mechanisms:

- PDO prepared statements
- Password hashing
- Session-based authentication
- Role checks
- Input sanitization
- CSRF/security support through the `Security` class
- Login-attempt tracking
- Rate limiting support
- Security event logging
- Password-reset functionality
- JWT authentication for API access

### Additional deployment requirements

Before production:

- Use HTTPS.
- Do not use MySQL `root`.
- Store secrets in environment/server configuration.
- Rotate exposed API keys.
- Replace demo passwords.
- Restrict Google Maps API access.
- Set a strong `JWT_SECRET`.
- Review API CORS settings.
- Disable detailed error messages.
- Review authorization on all endpoints.
- Remove development/test data.

See:

```text
SECURITY.md
```

---

# Important Development Fixes Included

This version also fixes several issues found during the project review:

- Removed the hardcoded Google Maps API key.
- Added `.env` support and `.env.example`.
- Added `.gitignore` for secrets.
- Added environment-based JWT configuration.
- Removed the weak default JWT secret fallback.
- Improved PDO configuration with UTF-8 and safer PDO attributes.
- Prevented database exception details from being displayed directly to users.
- Fixed volunteer opportunity creation where `$pdo` was used before being initialized.
- Fixed volunteer application where `$pdo` was used before being initialized.
- Fixed the broken volunteer dashboard `update_status.php` link to the implemented `update_delivery_status.php`.
- Fixed the volunteer dashboard logout link.
- Fixed the NGO opportunity creation flow pointing to the nonexistent `manage_opportunities.php`.
- Made the API path less dependent on the project being named exactly `FoodSave`.
- Replaced unrestricted API CORS wildcard behavior with configurable origins.
- Added deployment security documentation.

---

# Known Architecture Notes

FoodSave currently contains **both volunteer and driver delivery workflows**.

### Volunteer workflow

```text
NGO
 ↓
Volunteer Opportunity
 ↓
Volunteer Application
 ↓
Volunteer Assignment
 ↓
Delivery Assignment
 ↓
Delivery Status / Tracking
```

### Driver workflow

```text
Delivery Task
 ↓
Driver
 ↓
Accept Task
 ↓
Update Task Status
```

Both are retained in this project because they are implemented modules. If the project is later expanded, the two workflows could be unified under one delivery/transport model to reduce duplication.

---

# Troubleshooting

## Database connection failed

Check:

- MySQL is running.
- `foodsave_db` exists.
- `.env` contains the correct database settings.
- `database/foodsave_db.sql` was imported.

## 404 / Page not found

Check that FoodSave is inside:

```text
C:\xampp\htdocs\
```

and that the URL matches the actual folder name.

Example:

```text
http://localhost/FoodSave/
```

## Google Maps not working

Check:

- `GOOGLE_MAPS_API_KEY` is set in `.env`.
- The key is valid.
- Required Google Maps APIs are enabled.
- Referrer restrictions allow your local/deployed site.
- Browser developer tools do not show Google Maps authorization errors.

## JWT API authentication fails

Check:

```text
JWT_SECRET
```

is configured and consistent for the application.

## Expiration notifications are not appearing

Check:

- Notification tables exist.
- `cron/check_expirations.php` is being executed.
- Donation dates/statuses meet the alert conditions.
- PHP can access MySQL when run by the scheduler.

---

# Future Improvements

Potential next steps:

- Unify volunteer and driver delivery architecture
- Add automated PHP tests
- Add API versioning and fuller API documentation
- Add donation image uploads
- Add richer analytics
- Add real-time delivery updates
- Improve email/SMTP integration
- Add Docker development setup
- Add CI/CD
- Improve accessibility/WCAG compliance
- Add production deployment configuration
- Add stronger automated security checks

---

# License

This project is intended for educational/academic use unless a separate license is added by the project owner.

---

## FoodSave

**Online Food Waste Management System**

A platform designed to help surplus food reach NGOs and communities through coordinated donation, pickup, volunteer, and delivery workflows.
