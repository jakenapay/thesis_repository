# LPU Thesis Repository System

A centralized digital archive system designed to store, manage, and showcase academic research outputs from Lyceum of the Philippines University (LPU) students.

---

## 🎯 Project Purpose

This system serves as a comprehensive platform for:

- **Preservation** of student research for future reference
- **Accessibility** for students, faculty, and researchers to review completed theses
- **Recognition** of outstanding academic work produced by LPU students
- **Support** for ongoing research by providing examples and references

---

## 🛠️ Tech Stack

- **Backend:** CodeIgniter 4 (PHP Framework)
- **Database:** MySQL
- **Frontend:** HTML5, CSS3, JavaScript, Bootstrap
- **Server:** Apache (via XAMPP)
- **Architecture:** MVC Pattern

---

## 📋 Prerequisites

- PHP 8.1+ (minimum requirement)
- XAMPP (includes Apache, MySQL, PHP)
- Composer (for dependency management)
- Git (optional, for cloning)

---

## 🚀 Installation Instructions

1. **Install XAMPP**
   - Download and install XAMPP.
   - Start Apache and MySQL services from XAMPP Control Panel.

2. **Clone/Download the Project**
   - Use Git or download as ZIP and extract to `htdocs` folder.

3. **Install Dependencies**
   - Run `composer install` inside the project directory.

4. **Database Setup**
   - Open phpMyAdmin (`http://localhost/phpmyadmin`)
   - Create a new database:
     - Click "New" in the left sidebar
     - Enter database name: `lpu_thesis_repository`
     - Click "Create"
   - Import the SQL file:
     - Select your newly created database
     - Click the "Import" tab
     - Click "Choose file" and select the provided `.sql` file
     - Click "Import" to execute

5. **Environment Configuration**
   - Update database settings in `app/Config/Database.php`

6. **Configure Base URL**
   - Update `app/Config/App.php` with your application's base URL

7. **Set Folder Permissions**
   - Create upload directories, e.g. `writable/uploads`, and ensure proper permissions

---

## 🌐 Access the Application

- **Main URL:** [http://localhost/thesis_repository/](http://localhost/thesis_repository/)
- **Login Page:** [http://localhost/thesis_repository/login](http://localhost/thesis_repository/login)
- **Register Page:** [http://localhost/thesis_repository/register](http://localhost/thesis_repository/register)

---

## 🧪 Testing Setup (Optional)

- Install testing dependencies (`composer install --dev`)
- Configure test database
- Update test database settings in `app/Config/Database.php`
- Run tests via CodeIgniter's testing tools

---

## 🔧 Additional Configuration

### File Upload Settings

- Ensure PHP allows file uploads in `php.ini` (`file_uploads = On`)
- Set appropriate file size limits (`upload_max_filesize`, `post_max_size`)

### Security Settings

- Ensure `app/Config/Filters.php` has proper security filters
- Configure CSRF protection if needed
- Set proper file permissions for upload directories

---

## 📁 Project Structure

```
thesis_repository/
├── app/
├── public/
├── writable/
│   └── uploads/
├── vendor/
├── composer.json
└── ...
```

---

## ✨ Features

- **Document Management:** Upload, view, download academic papers
- **User Authentication:** Role-based access (students, faculty, advisers, librarians)
- **Review System:** Feedback and approval workflow for submitted documents
- **Search & Browse:** Easy discovery of research works
- **Analytics:** Track document views and downloads
- **Department-based Organization:** Documents organized by academic departments

---

## 🎯 Document Types

- **Graduate Thesis:** Undergraduate research papers
- **Dissertations:** Graduate-level research papers
- **Faculty Research:** Research works by faculty members

---

## 👥 Target Users

- **LPU Students and Faculty:** Full access with valid credentials
- **Guests:** Limited access to abstracts or public information
- **Advisers:** Review and provide feedback
- **Librarians:** Manage and publish documents

---

## 🚨 Troubleshooting

**Common Issues:**

- **Database Connection Error:** Check MySQL service and credentials in `Database.php`
- **SQL Import Failed:** Ensure the database is created first, then import
- **File Upload Errors:** Verify upload directory permissions and PHP settings
- **Base URL Issues:** Ensure correct path in `App.php` matches your folder structure
- **Missing Dependencies:** Run `composer install`

**Default Admin Account:**

- Check the imported SQL file for default user credentials, or create an admin user through the registration page.

---

## ✅ Verification

- Access the homepage successfully
- Register a new user account
- Login with credentials
- Upload a test document
- View analytics dashboard

---