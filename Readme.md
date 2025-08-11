# 📞 Contact List Management System

A modern, feature-rich contact management web application built with PHP, MySQL, and Bootstrap. This application allows users to manage their contacts with advanced features like image uploads, multiple phone numbers, social media links, CSV import/export, and secure user authentication.

## ✨ Features

### 🔐 User Authentication
- **Secure Signup System**: User registration with email validation
- **Password Strength Indicator**: Real-time password strength checker with animated visual feedback
- **Password Requirements**: Multiple strength levels (weak, normal, strong, too strong)
- **Form Validation**: Client-side and server-side validation

### 👥 Contact Management
- **Add/Edit Contacts**: Full CRUD operations for contact management
- **Multiple Phone Numbers**: Add unlimited phone numbers per contact
- **Profile Pictures**: Upload and manage contact profile images
- **Social Media Links**: Store social media profiles for each contact
- **Search Functionality**: Search contacts by first or last name
- **Responsive Design**: Mobile-friendly interface using Bootstrap 5

### 📊 Data Operations
- **CSV Import**: Bulk import contacts from CSV files
- **CSV Export**: Export all contacts to CSV format
- **Data Validation**: Input sanitization and security measures
- **Image Upload**: Secure file upload with validation

### 🎨 User Interface
- **Modern UI**: Clean, responsive design with Bootstrap 5
- **Animated Elements**: Smooth animations and transitions
- **Interactive Feedback**: Real-time password strength indicators
- **Toast Notifications**: User-friendly success/error messages
- **Multi-language Support**: Seamless switching between English and Persian (Farsi) languages
- **Dynamic Navbar**: Enhanced navigation bar with improved functionality and display.

## 🛠️ Technology Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **UI Framework**: Bootstrap 5.3.7
- **JavaScript Libraries**: 
  - jQuery 3.7.1
  - Animate.css 4.1.1
  - Tippy.js 6.3.7
- **Server**: Apache/Nginx (XAMPP recommended)

## 📋 Prerequisites

Before running this application, make sure you have:

- **XAMPP** or similar local server environment
- **PHP 7.4** or higher
- **MySQL 5.7** or higher
- **Web browser** with JavaScript enabled
- **Timezone API**: Integration for dynamic timezone updates.

## 🚀 Installation

### Step 1: Clone the Repository
```bash
git clone <repository-url>
cd contact_list
```

### Step 2: Database Setup
1. Start your XAMPP server (Apache & MySQL)
2. Open phpMyAdmin: `http://localhost/phpmyadmin`
3. Create a new database named `contacts_db`
4. Import the database schema (if provided) or the application will create tables automatically
5. **Important**: Ensure your MySQL server's `time_zone` setting is correctly configured, or handle timezones within the application.

### Step 3: Configuration
1. Navigate to `connection/config.php`
2. Update database credentials if needed:
```php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "contacts_db";
```

### Step 4: Install Dependencies
```bash
npm install
```

### Step 5: Access the Application
1. Place the project in your XAMPP `htdocs` folder
2. Open your browser and navigate to: `http://localhost/contact_list/src/`
3. For signup page: `http://localhost/contact_list/src/users/signup.php`

## 📁 Project Structure

```
contact_list/
├── common/
│   └── passwordstrange.php          # Password strength utilities
├── components/
│   ├── navbar.php                   # Navigation component (English)
│   └── navbarfa.php                 # Navigation component (Persian)
├── connection/
│   └── config.php                   # Database configuration
├── database/
│   └── contacts_db (1).sql          # Initial database schema
├── lang/
│   ├── en.php                       # English language translations
│   └── fa.php                       # Persian (Farsi) language translations
├── modules/
│   ├── delete.php                   # Contact deletion
│   ├── export.php                   # CSV export functionality
│   ├── function.php                 # Core functions
│   ├── get_timezone.php             # Server-side timezone retrieval
│   ├── get_timezone.py              # Python script for timezone handling (if used)
│   ├── import.php                   # CSV import functionality
│   ├── load.php                     # Data loading
│   ├── login/
│   │   ├── LoginUser.php           # User login logic
│   │   └── LogoutUser.php          # User logout logic
│   ├── save.php                     # Contact saving
│   ├── search.php                   # Search functionality
│   ├── security.php                 # Security utilities
│   └── signup/
│       ├── CheckPassword.php        # Password validation
│       └── CreatUser.php           # User creation
├── package-lock.json                # Node.js dependency lock file
├── package.json                     # Node.js dependencies
├── Readme.md                        # This file
├── src/
│   ├── css/
│   │   ├── login.css               # Login page styles
│   │   ├── signup.css              # Signup page styles
│   │   └── styles.css              # Main application styles
│   ├── index.php                   # Main application page
│   ├── js/
│   │   ├── app.js                  # Main application logic
│   │   ├── login.js                # Login page logic
│   │   ├── signup.js               # Signup page logic
│   │   └── timezone_updater.js     # JavaScript for timezone updates
│   ├── users/
│   │   ├── login.php               # User login page
│   │   └── signup.php              # User registration page
│   └── index.php                   # Main application page
├── uploads/                         # Contact image uploads
```

## 🎯 Usage Guide

### User Registration
1. Navigate to the signup page
2. Fill in username, email, and password
3. Watch the real-time password strength indicator
4. Confirm your password
5. Submit the form

### Managing Contacts
1. **Adding a Contact**:
   - Click "Add Contact" button
   - Fill in first name, last name
   - Upload a profile picture (optional)
   - Add phone numbers (click "Add Number" for multiple)
   - Add social media links
   - Submit the form

2. **Searching Contacts**:
   - Use the search bar to find contacts by name
   - Results update in real-time

3. **Editing Contacts**:
   - Click the edit button on any contact
   - Modify the information
   - Save changes

4. **Deleting Contacts**:
   - Click the delete button on any contact
   - Confirm the deletion

### Import/Export
1. **Export Contacts**:
   - Click "Export CSV" to download all contacts
   - File will be saved as CSV format

2. **Import Contacts**:
   - Click "Import" button
   - Select a CSV file with contact data
   - Upload and import contacts

## 🔒 Security Features

- **Input Sanitization**: All user inputs are sanitized
- **SQL Injection Prevention**: Prepared statements used
- **File Upload Security**: Image upload validation
- **Password Hashing**: Secure password storage
- **XSS Protection**: Output escaping implemented

## 🎨 Customization

### Styling
- Modify `src/css/styles.css` for main application styling
- Modify `src/css/signup.css` for signup page styling
- Update Bootstrap classes for layout changes

### Functionality
- Edit `src/js/app.js` for main application logic
- Edit `src/js/signup.js` for signup page functionality
- Modify PHP modules in `modules/` directory for backend changes

## 🐛 Troubleshooting

### Common Issues

1. **Database Connection Error**:
   - Ensure XAMPP is running
   - Check database credentials in `connection/config.php`
   - Verify database `contacts_db` exists

2. **Image Upload Issues**:
   - Check `uploads/` directory permissions
   - Ensure file size limits in PHP configuration
   - Verify supported image formats

3. **Password Strength Not Working**:
   - Check browser console for JavaScript errors
   - Ensure jQuery is loaded properly
   - Verify AJAX endpoints are accessible

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## 📄 License

This project is licensed under the ISC License.

## 👨‍💻 Author

Created with ❤️ for efficient contact management.

---

**Note**: This application is designed for local development and learning purposes. For production use, ensure proper security measures and hosting environment configuration.
