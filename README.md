# FoodSave — Online Food Waste Management System

FoodSave is a PHP/MySQL web application that connects **food donors** with **NGOs/food banks** and coordinates volunteers/drivers to redistribute surplus food before it goes to waste.

---

## 🚀 Overview

FoodSave supports the surplus-food workflow from donation through pickup and delivery:

1. Donor registers/logs in  
2. Donor creates a surplus-food donation  
3. NGOs browse available donations and request pickups  
4. Donors approve/reject pickup requests  
5. Approved requests are assigned for delivery  
6. Volunteers and drivers manage deliveries  
7. Delivery tracking with GPS/location updates  
8. Notifications and expiration checks keep donations moving  
9. Feedback, ratings, reporting, and food-safety guidance support the system  

---

## 👥 User Roles

- **Administrator**: Dashboard, user management, reports, food-safety guidelines  
- **Donor**: Create/manage donations, approve/reject pickup requests  
- **NGO / Food Bank**: Browse donations, request pickups, manage volunteers, track deliveries  
- **Volunteer**: Apply for opportunities, manage deliveries, update status  
- **Driver**: Accept delivery tasks, update task status, GPS tracking  

---

## ✨ Main Features

- **Food Donations**: Categories, quantity, expiration tracking, pickup address  
- **Pickup Requests**: NGO requests, donor approval, receipts, completion flow  
- **Volunteer Management**: Opportunities, applications, assignments, delivery updates  
- **Driver Management**: Task board, status updates, dashboard  
- **Delivery Tracking**: GPS support, pickup/drop-off locations, active delivery views  
- **Inventory & Reports**: Statistics, breakdowns, expiring donations, feedback reports  
- **Notifications & Email**: Alerts for pickups, expirations, HTML email templates  
- **Food Safety**: Admin-managed guidelines for donors and NGOs  

---

## 🛠 Technology Stack

| Component | Technology |
|-----------|------------|
| Backend   | PHP |
| Database  | MySQL (PDO) |
| Frontend  | HTML5, CSS3, JavaScript |
| UI        | Bootstrap 5 |
| Icons     | Font Awesome |
| Maps      | Google Maps JavaScript API |
| Web Server| Apache (XAMPP for local dev) |
| Auth      | JWT |
| Testing   | Postman |

---

## 📂 Project Structure

```text
FoodSave/
├── admin/              # Admin panel
├── donor/              # Donor workflow
├── ngo/                # NGO/food-bank workflow
├── volunteer/          # Volunteer workflow
├── driver/             # Driver/task workflow
├── api/                # REST-style API
├── database/           # Schema/migrations
├── cron/               # Scheduled expiration checker
├── assets/             # CSS/JS
├── email_templates/    # HTML email templates
└── includes/classes/   # Reusable PHP classes
```

---

## ⚙️ Installation (XAMPP)

1. Install XAMPP with Apache & MySQL  
2. Copy project into `htdocs` (e.g., `C:\xampp\htdocs\FoodSave\`)  
3. Start Apache & MySQL from XAMPP Control Panel  
4. Import `database/foodsave_db.sql` into phpMyAdmin  
5. Configure environment variables in `.env`:  

```text
DB_HOST=your_host
DB_USER=your_username
DB_PASS=your_password
GOOGLE_MAPS_API_KEY=your_api_key
JWT_SECRET=your_secret
```

6. Open the app: `http://localhost/FoodSave/`

---

## 🌍 Google Maps

Used for donation browsing, delivery tracking, and location views.  
Requires a valid restricted **Google Maps API key** set in `.env`.

---

## 🔑 API

REST-style API available at `/api`.  
Authentication via JWT (`Authorization: Bearer <token>`).  
Endpoints include:  
- **Auth**: `/api/auth/login`, `/api/auth/register`  
- **Donations**: `/api/donations`  
- **Pickup**: `/api/pickup`  
- **Volunteer**: `/api/volunteer`  
- **Inventory**: `/api/inventory`  

Postman collection: `postman_collection.json`

---

## 🔒 Security

- PDO prepared statements  
- Password hashing & session auth  
- CSRF protection  
- JWT authentication  
- Role-based access control  
- Rate limiting & logging  

**Deployment checklist:**  
- Use HTTPS  
- Strong `JWT_SECRET`  
- Restrict API keys  
- Remove test/demo data  
- Review CORS & authorization  

---

## 🛠 Troubleshooting (Quick)

- **Database error** → Check MySQL running, `.env` values, imported schema  
- **Google Maps not working** → Verify API key & restrictions  
- **JWT auth fails** → Ensure `JWT_SECRET` is set consistently  
- **Expiration notifications missing** → Confirm cron job is scheduled  

---

## 📈 Future Improvements

- Unify volunteer & driver workflows  
- Add automated tests & CI/CD  
- Donation image uploads  
- Real-time delivery updates  
- Richer analytics & accessibility improvements  

---

## 📜 License

Educational/academic use unless a separate license is added.

