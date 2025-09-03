# Thesis Repository

Welcome to the **Thesis Repository**! This repository is designed to support thesis research and development, providing a structured environment for code, data, and documentation related to academic or technical projects.

This project is based on **CodeIgniter 4**.

---

## Features

- Organized codebase for thesis research and development
- Supports multiple languages: **PHP CodeIgniter 4** (primary), **JavaScript**, **CSS**, **HTML**, **MySQL**
- Modular and extensible structure
- Publicly accessible for submission, publication, feedback, and adviser review

---

## Technologies Used

- **PHP** (main application, CodeIgniter 4 framework)
- **JavaScript**, **CSS**, **HTML** (frontend)
- **MySQL**, **PhpMyAdmin** (backend)
- **Bootstrap** (optional components)

---

## Getting Started

### 1. Clone the Repository

```bash
git clone https://github.com/jakenapay/thesis_repository.git
cd thesis_repository
```

### 2. Install Dependencies

This repository uses **Composer** for PHP dependency management. If the `vendor/` directory and other files are missing (e.g., due to `.gitignore`), you need to install Composer dependencies.

If you don't have Composer installed, follow the official guide: https://getcomposer.org/download/

Then run:

```bash
composer install
```

If `composer.json` is missing or incomplete, you may need to re-create it or fetch a fresh CodeIgniter 4 `composer.json` as a base.

---

### 3. Setting Up Environment

- Copy the included `env` file to `.env`:

    ```bash
    cp env .env
    ```

- Edit `.env` to set your `baseURL` and database configuration as needed.

---

### 4. Set up Database in MySQL

- Get the SQL file provided and create a new database "thesis_repository" and import the SQL file.

---

### 5. Web Server Configuration

- The *public* directory is your web root. Point your web server's document root to `project-root/public`.
- Do **not** expose the project root or any folder other than `public` to the web.

---

### 6. Running the Application

With PHP installed (minimum version 8.1):

```bash
php spark serve
```

Then visit [http://localhost:8080](http://localhost:8080) in your browser.

---

## Server Requirements

- **PHP** 8.1 or higher
- PHP extensions required:
    - intl
    - mbstring
    - json (enabled by default)
    - mysqlnd (for MySQL)
    - libcurl (for HTTP client)
- Composer (to install dependencies)

---

## Running Tests

- Install dev dependencies: `composer install`
- Copy `phpunit.xml.dist` to `phpunit.xml` and edit as needed.
- Run tests:

    ```bash
    vendor/bin/phpunit
    ```

---

## Contribution

Contributions are welcome! Fork the repository and open a pull request or submit an issue.

---

## License

This project is licensed under the [MIT License](LICENSE).

---

## References

- [CodeIgniter 4 User Guide](https://codeigniter.com/user_guide/)
- [Composer Documentation](https://getcomposer.org/doc/)
