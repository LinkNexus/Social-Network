# Social Network

A blog like web-app made in pure PHP and JavaScript. Users can create accounts, login,
reset their passwords, create posts, change profile pictures etc... The Website
is not yet responsive, hence needing the use of a computer with normal screen size to use.

---

## Table of Contents

- [Features](#features)
- [Demo](#demo)
- [Getting Started](#getting-started)
- [Installation](#installation)
- [Usage](#usage)

---

## Features

- **PHP Web Application** - Uses PHP for dynamic rendering of the html content.
- **Minimalist Styling** - CSS-in-JS for a simple and clean look.

## Getting Started

These instructions will guide you on how to set up and run the project locally.

### Prerequisites

- - **PHP** (version 8.x or above)

### Installation

1. Clone the repository:
    ```bash
       git clone https://github.com/LinkNexus/ReactTodoList.git
    cd ReactTodoList
    ```

2. Create the .env.local file using the .env file as model
   ```bash
   cp .env .env.local
    ```

3. In the .env.local file, override necessary environment variables. In case you have no mailing services, you can use [mailpit](https://mailpit.axllent.org/docs/install/) which is very simple to use.
   ```bash
   MAILER_DSN=your mailer configuration
    ```
    - Using MySQL
   ```bash
   DATABASE_URL="mysql://{your username}:{your password}@127.0.0.1:3306/{your database name}?serverVersion=8.0.32&charset=utf8mb4"
    ```
    - Using MariaDB
   ```bash
   DATABASE_URL="mysql://{your username}:{your password}@127.0.0.1:3306/{your database name}?serverVersion=10.11.2-MariaDB&charset=utf8mb4"
    ```
    - Using PostgreSQL
   ```bash
   DATABASE_URL="postgresql://{your username}:{your password}@127.0.0.1:5432/{your database name}?serverVersion=16&charset=utf8"
    ```

4. Create the database, migrations and modify the database
    - Using PHP
   ```bash
   mkdir migrations
   php bin/console make:migration
   php bin/console doctrine:migration:migrate
    ```
    - Using Symfony CLI
   ```bash
   mkdir migrations
   symfony console make:migration
   symfony console doctrine:migration:migrate
   ```

5. Install the dependencies:
    ```bash
    npm install
   composer install
    ```

6. Start the development server:
    ```bash
   php -S localhost:8000 -t public/
   npm run dev
    ```

7. Open your browser and visit [http://localhost:8000](http://localhost:8000) to see the blog.

---

## Usage

- **Styling**: The styling is made by tailwind. Many of the elements are made from ShadCN UI, hence just the modifying
  the global css variables may help to change the styling to your wants.
- **Build for Production**: Run `npm run build` to create an optimized production build in the `build/` directory.

---

