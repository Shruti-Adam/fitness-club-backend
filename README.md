# Fitness Club System - Backend (Laravel)

This repository contains the backend API for the Fitness Club System, developed using PHP Laravel. The application is designed to manage users, memberships, schedules, and administrative operations. The project is configured to run locally using Laravel Herd.

---

## Tech Stack

* PHP (Laravel Framework)
* MySQL Database
* Laravel Herd (Local Development)
* RESTful APIs
* Composer and NPM

---

## Project Structure

fitness-club-backend/

├── app/                Application logic (Controllers, Models)
├── bootstrap/          Framework bootstrap files
├── config/             Configuration files
├── database/           Migrations, seeders, factories
├── public/             Entry point (index.php)
├── resources/          Views and frontend assets
├── routes/             API and web routes
├── storage/            Logs, cache, uploads
├── tests/              Test cases

├── .env.example        Environment configuration sample
├── composer.json       PHP dependencies
├── package.json        Node dependencies
├── artisan             Laravel CLI
├── Dockerfile          Docker configuration
└── README.md           Documentation

---

## Features

* User authentication (admin and users)
* Membership management
* Workout and schedule APIs
* Payment handling
* Administrative controls
* Secure REST API development
* CORS enabled for frontend integration

---

## Installation

### 1. Clone the Repository

git clone https://github.com/your-username/fitness-club-backend.git
cd fitness-club-backend

---

### 2. Install Dependencies

composer install
npm install

---

### 3. Configure Environment

cp .env.example .env

Update the database configuration in the `.env` file:

DB_DATABASE=fitness_club
DB_USERNAME=root
DB_PASSWORD=

---

### 4. Generate Application Key

php artisan key:generate

---

### 5. Run Database Migrations

php artisan migrate

Optional:

php artisan db:seed

---

## Running the Application (Laravel Herd)

1. Place the project inside the Laravel Herd directory (for example: `C:\Users\HP\Herd`)
2. Open the Laravel Herd application
3. Ensure PHP and MySQL services are running
4. Access the application in the browser:

http://fitness-club-backend.test

Alternatively, you can run:

php artisan serve

Then access:

http://127.0.0.1:8000

---

## API Routes

All API endpoints are defined in:

routes/api.php

Example endpoints:

* /api/register
* /api/login
* /api/memberships
* /api/schedule

---

## Authentication

The application supports secure authentication mechanisms. Token-based authentication can be implemented using Laravel Sanctum or Passport.

---

## Testing

Run the test suite using:

php artisan test

---

## Docker Support (Optional)

docker build -t fitness-backend .
docker run -p 8000:8000 fitness-backend

---

## Deployment

This project can be deployed on platforms such as:

* Hostinger
* AWS / VPS
* cPanel hosting
* Docker environments

Ensure the following during deployment:

* Proper environment configuration
* Database migrations executed
* Storage and cache permissions set correctly

---

---

## Project Screenshots

Authentication: 
<img width="1110" height="842" alt="image" src="https://github.com/user-attachments/assets/adc2a97a-35b5-42dd-b21b-79a78d2505e7" />
<img width="1089" height="786" alt="image" src="https://github.com/user-attachments/assets/8bfc2d94-3500-412c-b282-47e0cc37a04e" />

Admin Dashboard:
<img width="1909" height="881" alt="image" src="https://github.com/user-attachments/assets/08a22f4b-f0a6-403d-aceb-143b0247310a" />

Trainer Dashboard
<img width="1910" height="893" alt="image" src="https://github.com/user-attachments/assets/e65fc83d-8047-4d07-993e-87c60a2fa59f" />

Member Dashboard
<img width="1919" height="898" alt="image" src="https://github.com/user-attachments/assets/67c15566-dbf3-4df7-91d8-66da8d97f527" />

---

## Developer

Shruti Adam
GitHub: https://github.com/Shruti-Adam

---

## License

This project is open-source and available under the MIT License.
