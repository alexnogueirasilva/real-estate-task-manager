# Real Estate Task Manager API

This is a Laravel 10 RESTful API for managing tasks and comments related to buildings.

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/real-estate-task-manager.git
   ```

2. Navigate into the project directory:
   ```bash
   cd real-estate-task-manager
   ```

3. Install dependencies:
   ```bash
   composer install
   ```

4. Set up the environment:
   ```bash
   cp .env.example .env
   ```

5. Generate an application key:
   ```bash
   php artisan key:generate
   ```

6. Configure the `.env` file with your database credentials.

7. Run migrations:
   ```bash
   php artisan migrate
   ```

8. Start the local development server:
   ```bash
   php artisan serve
   ```

## Running Tests

To run the tests, use the following command:

```bash
php artisan test
