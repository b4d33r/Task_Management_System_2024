# Task Management System

A comprehensive web-based task management system built with PHP and MySQL, designed to help users organize and track their tasks efficiently.

## Features

- 👤 User Authentication (Register/Login)
- 📋 Task Management with Progress Tracking
- 🌟 Premium Subscription System
- 🔔 Real-time Notification System
- 📱 Responsive Design
- 📞 Contact System with Phone Support
- 👤 User Profile Management

## Database Structure

- `users` - User accounts with premium status
- `taches` - Task management with progress tracking
- `premium_subscriptions` - Secure payment and subscription handling
- `notifications` - System notifications for task updates
- `contact_messages` - Contact form submissions with phone support

## Requirements

- PHP 8.2 or higher
- MySQL 10.4 or higher (MariaDB)
- Web server (Apache/Nginx)
- Modern web browser

## Installation

1. Clone the repository:
```bash
git clone https://github.com/b4d33r/Task_Management_System_2024.git
cd Task_Management_System_2024
```

2. Set up the database:
   - Create a MySQL database
   - Import the database schema from `website.sql`
   - Update database credentials in `db.php`

3. Configure your web server:
   - Point your web server's document root to the project directory
   - Ensure PHP has write permissions for uploads and temporary directories

4. Access the application:
   - Open your web browser
   - Navigate to `http://localhost/Task_Management_System_2024`
   - Register a new account to start using the system

## Directory Structure

```
Task_Management_System_2024/
├── css/              # Stylesheet files
├── database/         # Database schema files
├── images/          # Image assets
├── js/              # JavaScript files
├── .gitignore       # Git ignore rules
├── LICENSE          # MIT License
├── README.md        # Project documentation
├── contact.php      # Contact form
├── dashboard.php    # Main dashboard
├── db.php           # Database configuration
├── index.html       # Landing page
├── login.php        # User authentication
├── notif.php        # Notifications
├── premium.php      # Premium features
├── profil.php       # User profile
├── register.php     # User registration
└── website.sql      # Database dump
```

## Security Features

- Password hashing with bcrypt
- SQL injection prevention
- XSS protection
- Secure payment information storage
- Premium subscription management

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For support, please use the GitHub issues system or contact us through the application's contact form.
