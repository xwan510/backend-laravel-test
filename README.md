# Test Laravel Project

This is a Laravel test project providing APIs for property analytics inquiry.

## Requirements

Make sure you have everything installed for Laravel. [Laravel](https://laravel.com/docs/7.x/installation)
Postgres 10 and above must be installed.  [Postgres](https://www.postgresql.org/download/)
SQLite/Mysql is not supported.

## Installation

#### Configuration

- Copy file `.env.example` to `.env`

- Edit .env for Postgres DB connection

- Install project dependencies

```
composer install
```

#### DB schema setup and seed DB
CSV files in database/csvs are imported to seed DB
```
php artisan migrate:fresh --seed
```

## Testing

#### Start web server

```
php artisan serve
```

#### Create a new property
```
curl --location --request POST 'http://127.0.0.1:8000/api/v1/properties' \
--header 'Content-Type: application/json' \
--header 'X-Requested-With: XMLHttpRequest' \
--data-raw '{"state": "NSW", "country": "Australia", "suburb": "Parramatta"}'
```

#### Add/update an analytic value for a property

Replace {uuid} with Property's guid.
Replace {analyticid} with an analytic type id. E.g 1
Replace {value} with an analytic value.
```
curl --location --request PUT 'http://127.0.0.1:8000/api/v1/properties/{uuid}/analytics/{analyticid}?value={value}' \
--header 'X-Requested-With: XMLHttpRequest' \
--header 'Content-Type: application/json'
```

#### Getting all analytics for a property
```
curl --location --request GET 'http://127.0.0.1:8000/api/v1/properties/{uuid}/analytics' \
--header 'Content-Type: application/json' \
--header 'X-Requested-With: XMLHttpRequest'
```

#### Report analytics summary
```
curl --location --request GET 'http://127.0.0.1:8000/api/v1/report/properties/analytics?filter=country&value=Australia' \
--header 'X-Requested-With: XMLHttpRequest' \
--header 'Content-Type: application/json'
```

#### Run unit tests

```
php artisan test
```
