# Laravel Task Management App

A **full-stack Laravel 12 Task Management application** with web interface and API (using Sanctum). Includes **task CRUD**, **validation**, **scheduled reminders**, and **queued email notifications**.

---

## Features

### Web
- Task CRUD (Create, Read, Update, Delete)  
- Paginated task list  
- Client-side validation with **jQuery Validate**  
- Server-side validation using `TaskRequest`  
- Flash messages for success/error with dismiss button  
- Due date validation (cannot be in the past; allows existing past dates on edit)

### API
- User registration, login, logout (`/api/register`, `/api/login`, `/api/logout`)  
- Protected API routes using **Sanctum token authentication**  
- Task CRUD API with proper validation and authorization  
- JSON-only responses  

### Scheduled Reminders
- Queue-based job checks tasks **due tomorrow** and sends email reminders  
- Avoids N+1 queries by chunking users and eager loading tasks  
- Email content can be tested using **log mail driver**

---

## Installation

1. Clone the repository:

```bash
git clone https://github.com/yourusername/laravel-task-management.git
cd laravel-task-management
```
2. Install dependencies:
    ```bash
    composer install
    npm install
    npm run dev
    ```
3. Create .env file:
    ```bash
        cp .env.example .env
    ```
    For real emails, configure Mailtrap or SMTP settings.
   
4. Generate app key:
    ```bash
        php artisan key:generate
    ```
5. Run migrations:
    ```bash
        php artisan migrate
    ```
6. Serve the application:
    ```bash
        php artisan serve
    ```
7. The job checks for tasks due tomorrow and sends reminder emails.
    ```bash
        php artisan tasks:send-reminders
    ```
8. run the queue worker:
    ```bash
        php artisan queue work
    ```
9. Test emails:
    ```bash 
        With MAIL_MAILER=log, emails are logged to storage/logs/laravel.log.
    ```
10. For the APIs, postman collection URL:
      ```bash
          https://asadmansuri6797-4418108.postman.co/workspace/Asad-Mansuri's-Workspace~fb9c98e4-8504-49ee-837b-45e79797deb7/collection/50277539-e70a68da-dd2d-45e6-a529-aba1c4b44135?action=share&creator=50277539&active-environment=50277539-445b718c-48bf-42d1-abd2-9d05e00f5f2c
      ```
      
