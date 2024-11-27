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
       git clone https://github.com/LinkNexus/Social-Network.git
    cd Social-Network
    ```

2. Create an .env file at the root of the project
   ```bash
   touch .env 
    ```

3. In the .env file, create necessary environment variables. 
   ```bash
   DATABASE_USER=
   DATABASE_PASSWORD=
   DATABASE_NAME=
   DATABASE_HOST=
   GMAIL_USER=
   GMAIL_PASSWORD=
    ```

4. Use the database.sql file to create the database and tables
   ```bash
   mysql -u username -p password < database.sql
    ```

6. Start the development server:
    ```bash
   php -S localhost:8000 -t public/
    ```

7. Open your browser and visit [http://localhost:8000](http://localhost:8000) to see the blog.

---

## Usage

- **Styling**: The styling is made by using pure css in the file called Styles/Style.css.

---

