# REST API VIA LARAVEL (SAIL DOCKER)

Description: This project is an e-commerce application developed using Laravel. It supports basic functions such as listing products, creating offers, and managing orders, and provides a RESTful API.

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
- [Design Patterns](#design-patterns)
- [Scheduled Tasks and Console Commands](#scheduled-tasks-and-console-commands)
- [Developer Notes](#developer-notes)

## Features

- User registration and login
- Product listing, adding, and updating
- Creating and updating offers
- Creating, updating, and listing orders
- Automatic data fetching and updating
- Scheduled tasks and console commands

## Installation

1. **Requirements:**

    - PHP >= 8.0
    - Composer
    - Docker & Docker Compose (for Laravel Sail usage)

2. **Clone the Project:**

   ```bash
   git clone https://github.com/fatihes1/laravel-rest-api-docker-with-sail.git
   cd laravel-rest-api-docker-with-sail
    ```
   
3. **Install Dependencies:**

    ```bash
    composer install
    ```
   
4. **Copy Environment File:**

    ```bash
    cp .env.example .env
    ```

5. **Configure Environment File:**

Open the .env file and configure the necessary settings (database, API keys, etc.)

6. **Run Migrations and Seed:**

    ```bash
    php artisan migrate --seed
    ```
   
7. **Start the Server with Laravel Sail:**

    ```bash
    ./vendor/bin/sail up
    ```


 ## Usage

- User Registration: POST /register - Creates a new user.
- User Login: POST /login - Logs in a user and returns a token.
- Products: GET /products - Lists products.
  - POST /products - Adds a new product.
  - PUT /products/{id} - Updates an existing product.
  - DELETE /products/{id} - Deletes a product.
- Offers: GET /offers - Lists offers.
  - POST /offers - Adds a new offer.
  - PUT /offers/{id} - Updates an existing offer.
  - DELETE /offers/{id} - Deletes an offer.
- Orders: GET /orders - Lists orders.
  - POST /orders - Creates a new order.
  - PUT /orders/{id} - Updates an existing order.
  - DELETE /orders/{id} - Deletes an order.


You can also check the API documentation by visiting the `./Verde Inc..postman_collection.json` file.


```bash


## Design Patterns

Design patterns used in this project:

-   **Repository Pattern:** Database operations and queries are managed in Repository classes. This pattern enhances code reusability and simplifies database operations management.
-   **Service Layer:** Business logic is handled in Service classes. This reduces the complexity of controllers and improves the testability of the code.
-   **Facade Pattern:** Laravel's own Facade classes make it easier to access various components of the application.
-   **Factory Pattern:** Used for dynamically creating database models.

## Scheduled Tasks and Console Commands

-   **Console Command:** A console command has been written to fetch data from the API. The command is located in the `app/Console/Commands` directory and can be run as follows:

```bash
php artisan app:fetch-order-command
```
or

```bash
./vendor/bin/sail artisan app:fetch-order-command
```

**Scheduled Task:** A scheduled task has been set up to run automatically on a weekly basis. The task is defined in the `app/Console/Kernel.php` file and fetches data from the API, ensuring it is processed.

## Developer Notes

-   **Rate Limiting:** Rate limiting is applied to API calls. In case of errors, the job is retried.
-   **Exception Handling:** Custom error responses and exception handling are implemented for potential issues in the API.
-   **Retry Mechanism:** If API calls fail, a retry mechanism is implemented to ensure the job is retried.



