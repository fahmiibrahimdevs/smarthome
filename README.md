![image](https://github.com/user-attachments/assets/a05a8e03-62f0-45ae-a6b1-115b3000d91b)

# Livewire Stisla

**Livewire Stisla** is a robust starter kit combining the power of **Laravel 11** and **Livewire 3** with the elegant **Stisla Admin Template**. It features a comprehensive multi-auth system using Laratrust and user account management capabilities.

Have Fun ^\_^

## Key Features

-   **Laravel 11 & Livewire 3**: Cutting-edge backend and full-stack framework.
-   **Stisla Admin Template**: Beautiful, responsive, and easy-to-customize UI.
-   **Multi-Authentication**: Integrated with **Laratrust** for Admin and User roles logic.
-   **User Management**: Admin ability to activate/deactivate user accounts.
-   **Profile Management**: Built-in simple profile editing.

## Prerequisites

Before you begin, ensure you have the following installed:

-   [PHP](https://www.php.net/) (version 8.2 or higher)
-   [Composer](https://getcomposer.org/)
-   [MySQL](https://www.mysql.com/)
-   [Node.js](https://nodejs.org/) & [npm](https://www.npmjs.com/)

## Installation

Follow these steps to setup the project:

### 1. Clone the Repository

```bash
git clone https://github.com/fahmiibrahimdevs/livewire-stisla.git
cd livewire-stisla
```

### 2. Install Dependencies

Install PHP and Node.js dependencies:

```bash
composer install
npm install
```

### 3. Environment Setup

Copy the example environment file and configure it:

```bash
cp .env.example .env
```

Update your database credentials in the `.env` file.

### 4. App Key & Database

Generate the application key and migrate the database with seeders:

```bash
php artisan key:generate
php artisan migrate:fresh --seed
```

### 5. Build Assets

Compile the frontend assets:

```bash
npm run dev
# Or for production
# npm run build
```

### 6. Run Application

Start the development server:

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## License

This project is licensed under the [MIT License](LICENSE).
