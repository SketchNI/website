# Sketchite

A VueJS and Laravel-powered project for my personal website.

---

## Table of Contents

- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Issues](#issues)
- [Tech Stack](#tech-stack)
- [Commands](#commands)
- [License](#license)

---

## Prerequisites

Ensure you have the following installed:

- [Node.js](https://nodejs.org/)
- [npm](https://www.npmjs.com/)
- [Composer](https://getcomposer.org)
- [Docker](https://docker.com)
- [PHP8.4](https://php.net)

---

## Installation

Clone the repository and install dependencies:

```sh
$ git clone <repository-url>
$ cd <repository-folder>
$ npm install
$ composer install
$ cp .env.example .env
$ ./vendor/bin/sail up -d
```

## Issues

Issues are managed over on my [Traq](https://traq.sketchni.uk) instance.

---


Run the development server:

```sh
$ composer run dev
```

Open up `http://laravel.test`.

---

## Tech Stack

Sketchite uses the following technologies:

| Category                  | Libraries/Tools                          |
| ------------------------- |------------------------------------------|
| **Framework**             | Laravel 12.15.0, Inertia 2.0.2, Vue.js 3.5.13 |

---

## Commands

These are the npm scripts you can use to manage your application:

| Command             | Description                                 |
|---------------------| ------------------------------------------- |
| `composer run dev`  | Starts the development server.              |
| `composer run prod` | Builds the project for production.        |

---

## License

This project is licensed under the MIT License. See the [LICENSE](./LICENSE) file for details.

---

## Acknowledgments

Sketchite uses several fantastic frameworks and libraries, including:

- [Laravel](https://laravel.com)
- [Inertia](https://inertiajs.com/)
- [Livewire](https://livewire.laravel.com)
- [Vue.js](https://vuejs.org/)
- [TailwindCSS](https://tailwindcss.com/)

---

Feel free to reach out or create an issue if you encounter any problems or have questions! 😊
