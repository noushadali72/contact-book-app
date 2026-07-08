# Contact Book App

A simple Contact Book application built with **Laravel**, **Tailwind CSS**, **jQuery**, and **AJAX**. This project demonstrates CRUD operations, form validation, AJAX interactions, pagination, live search, and sorting.

## Features

- Contact Management
- Full CRUD operations
- Bootstrap responsive UI
- Client-side validation using jQuery
- Server-side validation with Laravel Form Requests
- AJAX Create, Update & Delete
- Confirmation modal and toast notifications
- AJAX Pagination
- Live Search with debounce
- AJAX Sorting
- Seeded database with 300+ sample contacts

## Database

### Tables

- **groups**
  - id
  - name

- **contacts**
  - id
  - group_id (Foreign Key)
  - name
  - email
  - phone
  - address
  - notes
  - timestamps

## Tech Stack

- Laravel
- MySQL
- Blade
- Bootstrap (CDN)
- jQuery
- AJAX
- Bootstrap Modal

## Learning Objectives

- Laravel project structure
- Eloquent relationships
- Resource controllers
- Form Requests & Validation
- Blade templating
- jQuery DOM manipulation
- AJAX CRUD operations
- Pagination, Search & Sorting
- Database migrations, factories & seeders

## Setup

```bash
git clone https://github.com/noushadali72/contact-book-app/
cd contact-book-app

composer install
cp .env.example .env
php artisan key:generate

# Configure database

php artisan migrate --seed

php artisan serve
```
