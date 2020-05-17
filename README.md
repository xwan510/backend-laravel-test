# Sample Laravel Project

## Requirements
Make sure you have Laravel server requirements checked [here](https://laravel.com/docs/7.x/installation).

## Installation
- Copy file `.env.example` to `.env`

- Setup SQLite and DB connection
Creating a new SQLite database using a command such as
```
touch /path/to/project/database/database.sqlite
```
Edit .env for DB connection
```
DB_CONNECTION=sqlite
DB_DATABASE=/path/to/project/database/database.sqlite
DB_FOREIGN_KEYS=true
```

- Use another type of DB
If you use another type of DB. Please follow Laravel manual [here](https://laravel.com/docs/7.x/database#configuration) to setup DB connection.

- Install project dependencies
```
composer install
```

- Run migration and seed DB
```
php artisan migrate:fresh --seed
```

- Start web server
```
php artisan serve
```

- Check the service online at http://127.0.0.1:8000


- Run tests
```
php artisan test
```