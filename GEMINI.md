# Project Overview

This is a web application for a gym called "Ironforge Gym". It consists of a backend REST API and a frontend single-page application (SPA).

## Backend

The backend is a PHP application built using the Flight micro-framework. It provides a RESTful API for managing gym-related data such as users, classes, trainers, and memberships.

**Key Technologies:**

*   **PHP:** The core language for the backend.
*   **Flight:** A lightweight micro-framework for building REST APIs.
*   **MySQL:** The database used to store the application data.
*   **Swagger:** Used for API documentation.
*   **JSON Web Tokens (JWT):** Used for authentication.

**Project Structure:**

*   `rest/routes/`: Contains the API route definitions.
*   `rest/services/`: Contains the business logic for the application.
*   `rest/dao/`: Contains the data access objects for interacting with the database.
*   `vendor/`: Contains the Composer dependencies.

## Frontend

The frontend is a single-page application (SPA) built using vanilla JavaScript, jQuery, and Bootstrap. It provides the user interface for interacting with the gym's services.

**Key Technologies:**

*   **HTML:** The structure of the web pages.
*   **CSS:** The styling of the web pages.
*   **JavaScript:** The core language for the frontend.
*   **jQuery:** Used for DOM manipulation and AJAX requests.
*   **Bootstrap:** Used for responsive design and UI components.

**Project Structure:**

*   `index.html`: The main entry point for the frontend application.
*   `views/`: Contains the HTML templates for the different pages.
*   `js/`: Contains the main JavaScript files, including the router.
*   `services/`: Contains the JavaScript files for interacting with the backend API.
*   `css/`: Contains the custom CSS files.

# Building and Running

## Backend

To run the backend, you need a local web server (like Apache or Nginx) with PHP and a MySQL database.

1.  **Database Setup:**
    *   Create a MySQL database named `iron_forge_gym`.
    *   Import the database schema from the `Additional/dump iron_forge_gym.sql` file.
    *   Configure the database connection in `rest/Config.php`.

2.  **Web Server Configuration:**
    *   Point your web server's document root to the `backend/` directory.
    *   Ensure that the web server is configured to handle URL rewriting (e.g., using an `.htaccess` file for Apache).

## Frontend

To run the frontend, you can open the `frontend/index.html` file in your web browser. Make sure the backend is running and accessible from the frontend.

# Development Conventions

*   **Backend:** The backend follows the standard conventions for a Flight-based application.
*   **Frontend:** The frontend uses a simple, modular structure with a single router and multiple services for interacting with the backend API.
