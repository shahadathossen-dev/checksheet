### Getting Started

It is a basic boilerplate for with event driven **microservice** principles based on `Laravel` and `RabbitMQ` along with a beautiful clean Admin Dashboard.

If you want to run this project on your local environment, please follow these steps:

Clone the repository:

```
git clone https://github.com/shahadathossen-dev/laravel-microservice.git
```

There are two parts of this project:

1. **Backend**: This is the admin panel. It is a web application that is used to manage the users, products, attributes etc. It has a REST API that is used to communicate with the frontend.

#### Setup:

To run the `laravel-microservice` application, follow these steps:

```
cd ./laravel-microservice
```

Install dependencies:

```
composer install
```

Now, copy the `.env.example` file to `.env` file and change the credientials with your own values.

```
cp .env.example .env
```

Run the database migrations

```
php artisan migrate
```

Start the development server with this command:

```
php artisan serve
```

Your application is now running on http://localhost:8000

To populate the database with dummy data, run the following command:

```
php artisan db:seed
```

To generate the model permissions, run the following command:

```
php artisan generate:permissions
```

To generate `Super Admin`, run the following command and input `Super Admin` details:

```
php artisan generate:super-admin
```

#### Connecting Frontend

To connect the your frontend update the `.env` file with your credentials.

```bash
FRONTEND_URL=http://localhost:3000
SANCTUM_STATEFUL_DOMAINS=localhost:3000
```
Now you can connect your forntend to the http://localhost:8000/api/
