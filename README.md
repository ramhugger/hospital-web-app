# Hospital

A simple hospital management system 🏥.

## Table of Contents:

- [Introduction](#introduction)
    - [Database](#database)
    - [Backend](#backend)
    - [Frontend](#frontend)
- [Installation](#installation)
    - [Dependencies](#dependencies)
- [License](#license)

## Introduction

Hospital is a simple hospital management system.
It is a web application that allows users sign up and reserve medical appointments.
It also features a simple and intuitive user interface that was made
mainly for the purpose of exposing the power of the backend in action.

### Database

The database used in this project is a MariaDB database.
It includes a single schema called `hospital`.
Inside this schema, there are four tables layed out as follows:

![UML Representation of the `hospital` schema](internal/sql/migrations/v1.png)

### Backend

The backend is responsible for handling data management and internal business logic.
It is written in the (terrible) PHP language to accomodate deployment needs.
It uses modern techniques such as ORM and REST controllers to handle requests.

### Frontend

The frontend is responsible for handling user interaction and displaying the output
of the backend. It is built using vanilla async JavaScript and no framework whatsoever.
It makes heavy use of `fetch` to make AJAX requests to the backend and leverages
modern DOM manipulation techniques to display the results.

## Installation

To use Hospital, you first need to install the required dependencies.
Afterwards, you can run [the migration script](internal/sql/migrations/v1.sql)
to create the database schema and populate it with some sample data.
Finally, copy the source code under the serving folder of your web server.

You should be able to reach the web app using a web browser of your choice
(the latest version of Chrome/Safari is recommended).

### Dependencies

- Apache 2.4+
- PHP 7.1+
- MariaDB 10.4+

## License

This project is licensed under the [MIT license](LICENSE).
