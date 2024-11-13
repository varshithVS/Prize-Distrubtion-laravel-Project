Here’s a `README.md` file based on the instructions you provided:

```markdown
# Project Title

A Laravel-based prize distribution application with a simulation engine for random prize selection, reporting, and graphical prize distribution visualization.

## Table of Contents

1. [Installation](#installation)
2. [Configuration](#configuration)
3. [Running the Application](#running-the-application)
4. [Usage](#usage)
5. [Project Structure](#project-structure)
6. [Technical Details](#technical-details)
7. [Troubleshooting](#troubleshooting)

## Installation

### 1. Install PHP Dependencies
Use Composer to install all necessary PHP dependencies.

```bash
composer install
```

### 2. Install Node Modules
Use NPM to install all frontend dependencies.

```bash
npm install
```

### 3. Compile Frontend Assets
This step compiles the CSS and JavaScript files needed for the project.

```bash
npm run dev
```

## Configuration

### 1. Set Up Environment File
Copy the `.env.example` file to create your environment file.

```bash
copy .env.example .env
```

### 2. Generate Application Key
This key is used for session encryption and must be unique.

```bash
php artisan key:generate
```

### 3. Configure Database
Update the `.env` file with your database credentials:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 4. Run Database Migrations
Set up the database structure by running migrations.

```bash
php artisan migrate
```

### 5. Seed the Database (Optional)
Populate your database with sample data for testing purposes.

```bash
php artisan db:seed
```

## Running the Application

### 1. Start the Development Server
Run the following command to start the Laravel development server:

```bash
php artisan serve
```

By default, the application will be accessible at [http://localhost:8000](http://localhost:8000).

### 2. Running the Frontend Watcher
To keep assets up-to-date, run the following in a separate terminal:

```bash
npm run watch
```

## Usage

1. **Prize Configuration**: Add prizes with titles, descriptions, and probabilities in the system's backend.
2. **Simulation Engine**: Run the simulation to randomly select winners based on configured probabilities.
3. **Reporting**: View and download reports of distributed prizes and winner details.

The system provides a simple user interface where administrators can:
- Set up new prizes
- Configure prize-winning probabilities
- View winners and prize distributions in a graphical chart format

## Project Structure

A brief overview of the main folders in this Laravel project:

- `app/` - Contains core application files, including controllers, models, and services.
- `resources/` - Contains Blade templates, JavaScript, and Sass files.
- `routes/` - Contains route definitions.
- `database/` - Contains migration files for setting up database structure.
- `public/` - Contains publicly accessible files, such as compiled assets.

## Technical Details

### 1. Prize Distribution Algorithm
The simulation engine uses configured probabilities to distribute prizes randomly and fairly. Each prize can be set up with a different probability to control its distribution.

### 2. Chart Visualization
The project uses Chart.js to display a doughnut chart representing prize distribution. The chart updates automatically based on the prize data in the system.

### 3. Reports
Winners and prize distribution data are available in reports, making it easy to review or export.

## Troubleshooting

- **Database Connection Errors**: Double-check that your `.env` database credentials match your local database setup.
- **Missing Application Key**: Run `php artisan key: generate` if you receive an "Application key not set" error.
- **Permission Issues**: Ensure the `storage/` and `bootstrap/cache/` directories are writable:

  ```bash
  chmod -R 775 storage
  chmod -R 775 bootstrap/cache
  ```

- **Compilation Errors with Frontend Assets**: Ensure you’ve installed Node modules with `npm install` and compiled assets with `npm run dev`.

## License

This project is licensed under the MIT License. See the `LICENSE` file for details.
```

This `README.md` provides a structured guide for installing, configuring, running, and troubleshooting the application. Let me know if there are any other details you'd like to include!
