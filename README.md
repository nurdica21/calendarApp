Calendar App

Description:

This project is a web application for managing absences.


Features:

-Add new absences with start and end dates.
-View a calendar display of absences for the current month.


Technologies Used:

-Frontend: HTML, CSS, JavaScript (Vue.js)
-Backend: PHP (Laravel)
-Database: MySQL


Installation:
To run the project locally, follow these steps:

-Clone the repository to your local machine.
-Navigate to the project directory.
-Run composer install.
-Install dependencies by running *npm install* for the frontend and *composer install* for the backend.
-Set up the database and configure the database connection in the Laravel .env file (copy example env file, update it).
-Run migrations to create the necessary tables in the database: php artisan migrate.
-Seed the database with sample data (optional): php artisan db:seed --class=TypeOfAbsenceSeeder.
-Start the Laravel server: php artisan serve.
-Start the Vue server: npm run dev.
-Access the application in your web browser at http://localhost:8000.


Usage:
-Upon accessing the application, users can view the calendar display of absences for the current month.
-To add a new absence, click the "Add Event" button and fill in the required information in the modal that appears.
