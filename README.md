<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo"></a></p>

<p align="center">
<img src="https://img.shields.io/badge/PHP-8.3%2B-777BB4?logo=php&logoColor=white" alt="PHP Version">
<img src="https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel&logoColor=white" alt="Laravel Version">
<img src="https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql&logoColor=white" alt="MySQL">
<img src="https://img.shields.io/badge/Redis-Cache%20%26%20Queue-DC382D?logo=redis&logoColor=white" alt="Redis">
<img src="https://img.shields.io/badge/Docker-Ready-2496ED?logo=docker&logoColor=white" alt="Docker">
</p>

# CRM — Customer Relationship Management System

A full-featured CRM (Customer Relationship Management) system built with Laravel, MySQL, Redis, and Docker. The project is designed to manage companies, contacts, leads, and deals, assign tasks to team members, and track the entire sales pipeline from start to finish.

## About the Project

This isn't just a simple CRUD app — it's a portfolio project built around real backend architecture. It follows the Service Layer pattern, Form Request validation, Policy-based authorization, polymorphic relationships (for Tasks and Notes), background processing via queues, and caching with Redis.

## Core Modules

- **Authentication** — registration, login, password reset, email verification
- **Users & Roles** — Admin, Manager, and Employee roles with corresponding permissions
- **Companies** — manage companies, their contacts, and history
- **Contacts** — people linked to a company (name, position, contact details)
- **Leads** — potential customers, moving through status stages (New → Contacted → Qualified → Proposal → Negotiation → Converted/Lost)
- **Deals** — real sales opportunities, with amount, stage, and responsible user
- **Tasks** — work items assigned to team members (linked to a Company, Lead, or Deal)
- **Notes** — comments that can be attached to any entity (Company, Contact, Lead, Deal)
- **Dashboard** — overview stats: companies, contacts, active/won/lost deals
- **Search & Filter** — search and filter by status/priority across all modules
- **Notifications** — email and in-app notifications (e.g. when a new task is assigned)

## Tech Stack

| Technology | Purpose |
|---|---|
| Laravel 12 | Core backend framework |
| MySQL 8.0 | Database |
| Redis | Cache, session, and queue driver |
| Docker / Docker Compose | Containerized development environment |
| Nginx | Web server |
| Blade + Tailwind CSS | Frontend |

## Architecture

```
Browser → Route → Controller → Form Request → Service → Model → MySQL
```

Controllers only receive the request and pass it to the relevant Service class — business logic lives in the service layer. This makes the code easier to test and extend.

## Installation

### Requirements
- PHP 8.3+
- Composer
- Docker and Docker Compose
- Node.js and npm (for frontend assets)

### Steps

```bash
# Clone the repository
git clone https://github.com/USERNAME/crm.git
cd crm

# Set up the .env file
cp .env.example .env

# Start Docker containers
docker compose up -d --build

# Install Composer packages (if not installed automatically inside the container)
docker compose exec app composer install

# Generate the application key
docker compose exec app php artisan key:generate

# Run migrations
docker compose exec app php artisan migrate --seed

# Build frontend assets
npm install && npm run build
```

The project will be available at `http://localhost:8000`.

### Running the queue worker

```bash
docker compose exec app php artisan queue:work
```

## Running Tests

```bash
docker compose exec app php artisan test
```

To run tests for a specific module:

```bash
docker compose exec app php artisan test --filter=CompanyTest
```

## Roles & Permissions

| Role | Permissions |
|---|---|
| **Admin** | Full access to all modules, manages users |
| **Manager** | Full access to Companies, Contacts, Leads, Deals, Tasks |
| **Employee** | Only their own assigned Contacts, Leads, and Tasks |

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

For more information about the Laravel framework, check out the [official documentation](https://laravel.com/docs).
