# GUVI Assignment - User Management Web App

A responsive web application for user registration, login, and profile management using PHP REST API, JWT authentication, MySQL, MongoDB, and Redis.

## Features

- User registration and login
- JWT-based authentication
- Profile management (email, bio)
- Token blacklisting on logout
- Responsive Bootstrap UI
- Secure password hashing
- Prepared statements for SQL queries

## Tech Stack

- **Frontend**: HTML, CSS (Bootstrap 5), JavaScript (jQuery)
- **Backend**: PHP REST API
- **Database**: MySQL (users), MongoDB (profiles)
- **Cache**: Redis (token blacklist)
- **Authentication**: JWT

## Local Setup

### Prerequisites

- PHP 7.4+
- MySQL 5.7+
- Redis 5+
- MongoDB 4+
- Composer (for MongoDB PHP library)

### Installation Steps

1. **Clone or download the project**:
   ```
   cd /path/to/project
   ```

2. **Install PHP dependencies**:
   ```
   composer require mongodb/mongodb
   ```

3. **Setup MySQL**:
   - Create database and run schema:
     ```
     mysql -u root -p < sql/schema.sql
     ```

4. **Setup MongoDB**:
   - Ensure MongoDB is running
   - (Optional) Import sample profiles:
     ```
     mongoimport --db guvi_mongo --collection profiles --file mongo/seed_profiles.json --jsonArray
     ```

5. **Setup Redis**:
   - Ensure Redis server is running

6. **Configure database connections**:
   - Edit `PHP/config.php` with your database credentials

7. **Run the application**:
   - Serve the files using a PHP server (e.g., Apache, Nginx, or `php -S localhost:8000`)
   - Open `index.html` in browser

## Deployment

### Heroku

1. Create a Heroku app
2. Add add-ons: Heroku Redis, MongoDB Atlas, ClearDB MySQL
3. Set environment variables in Heroku config
4. Deploy code

### AWS

1. Launch EC2 instance
2. Install PHP, MySQL, Redis, MongoDB
3. Configure security groups
4. Upload code and configure web server
5. Use RDS for MySQL, ElastiCache for Redis, DocumentDB for MongoDB

## Demo Credentials

- **Username**: demo
- **Password**: demo123

## Submission Instructions

1. Ensure all files are included
2. Test locally before submission
3. Provide this README with setup instructions
4. Include demo credentials section
5. Submit as ZIP or repository link

## Security Notes

- Change JWT_SECRET in production
- Use HTTPS in production
- Validate all inputs
- Use strong passwords
- Keep dependencies updated