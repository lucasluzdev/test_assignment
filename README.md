# Test assignment for MailerLite

## TLDR
- Download/clone the source code
- Open a terminal in the root folder and run **docker-compose up**
- Open **db.sql** in a MySQL client, create the structure and populate with dummy data inside the file
- Open http://localhost:5173 on Google Chrome
- Give me feedback, please! :)

## Description
This project was developed as a tech assignment for MailerLite PHP Developer position

## Requirements
The following softwares and requirements are necessary to get this project up and running:
- Docker (can be either docker-engine (*nix), docker desktop (Mac) or Docker Desktop with WSL2 integration (Windows))
- Docker-compose
- At least 1GB of free disk space
- At least 2GB RAM (to get things up and running, while you access the frontend in a Google Chrome tab)

## Setup
Download/clone the project into any folder in your computer. Then, open a terminal on the project's root folder and run the following command:

```sh
docker-compose up
```

If for some reason, it fails to build, run the following command:

```sh
docker-compose build
```

Then, run **docker-compose up** again

## Database

After the environment is up and running, we need to create the database, tables and insert some data to test it properly.
We have a **db.sql** file in the root directory. Open it in any GUI client, such as MySQL Workbench or PHPMyAdmin, and create a connection with the following properties:
- **Host:** localhost
- **Port:** 5174
- **Username** root
- **Password:** lucasluz_test_assignment

Then, execute the commands inside the file to create the necessary structure for the database.

## Address and ports

The following URLs and ports are defined for the services:

- **http://localhost:5172** for PHP backend
- **http://localhost:5173** for VueJS 3 frontend
- **http://localhost:5174** for MySQL 5.7 database

these ports will be used while the **docker-compose up** command is being executed.

## Where is the interface?

After execute **docker-compose up** sucessfully, try to access **http://localhost:5173** in your browser.


## Endpoints
The following endpoints can be used to create/read/update/delete subscription information:

### GetAll endpoints

```
http://localhost:5172?pageSize=10&currentPage=1&sort=created-desc
```
or
```
http://localhost:5172/readMany?pageSize=10&currentPage=1&sort=created-desc
```

A **GET** endpoint to retrieve subscriptions, with pagination. You can use the following properties to customize your query:
- **pageSize** - The amount of data you want to retrieve. You can choose between 10, 30 and 50.
- **currentPage** - The current page of information that will be displayed.
- **sort** - Ordenation of information

### Search endpoint

```
http://localhost:5172/readOne?search=lucas&pageSize=10&currentPage=1&sort=created-desc
```

A **GET** endpoint to retrieve subscription of specific people, with pagination. You can use the following properties to customize your query:
- **search** - The first name / last name / email you are trying to find.
- **pageSize** - The amount of data you want to retrieve. You can choose between 10, 30 and 50.
- **currentPage** - The current page of information that will be displayed.
- **sort** - Ordenation of information

### Get by ID endpoint

```
http://localhost:5172/readOneByID?id=uuid
```

A **GET** endpoint to retrieve subscription of specific people, using ID as a condition:
- **id** - User's UUID.

### Create endpoint

```
http://localhost:5172/create
```

A **POST** endpoint to create new subscribers. To create a new subscriber, you need to provide the following information (yes, in JSON):

```json
{
    "first_name": "Lucas", # your first name
    "last_name": "Luz", # your last name
    "email": "lucasluzdev@gmail.com", # your email
    "status": 2 # Are you subscribed (2), or unsubscribed (1) ?
}
```

### Update endpoint

```
http://localhost:5172/create
```

A **PUT** endpoint to update a existing subscriber. To update, you need to provide the following information (yes, in JSON):

```json
{
    "id": "uuid",
    "first_name": "Lucaz", # your first name
    "last_name": "Lus", # your last name
    "email": "lucazlusdev@gmail.com", # your email
    "status": 1 # Are you subscribed (2), or unsubscribed (1) ?
}
```

### Delete endpoint

```
http://localhost:5172/delete/{uuid}
```

A **DELETE** endpoint to delete a subscriber. You need to provide a existing UUID as a param

For your convenience, there is a postman file at the root of the project with endpoints configured for you to use.

## Contact me

Feel free to contact me if I need to provide any information, or if there's any questions about the project. :)

[Email (GMail)](lucasluzdev@gmail.com)

[LinkedIn](https://linkedin.com/in/lucasluzdev)