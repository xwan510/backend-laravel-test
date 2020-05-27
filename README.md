# Test Laravel Project

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/xwan510/backend-laravel-test/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/xwan510/backend-laravel-test/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/xwan510/backend-laravel-test/badges/build.png?b=master)](https://scrutinizer-ci.com/g/xwan510/backend-laravel-test/build-status/master)

This is a Laravel test project providing APIs for property analytics inquiry.

## Design considerations

The `properties` table is many-to-many mapped to `analytic_types` table with intermediate table `property_analytics` and additional field `value`.

Adding/update property analytic is simply about creating/updating relations between those 2 tables.

The nesting of API endpoints `properties/{uuid}/analytics/{analyticid}` makes the "belonging" relationship obvious. CRUD operations of analytics against a property should be performed on this endpont. Deleting a property means deleting the analytics with it.

Properties might have sensitive info, with `{uuid}` here, it stops user iterating all properties with this API. But it might not be neccesary for protected API.

The reporting API endpoint resides in `report/properties/analytics`. Reporting on large scale is an intensive job. Imaging millions of properties with hundreds of types of analytics are processed in 1 API call, it has to be fast.

The PropertyAnalyticsReport Controller uses optimised DB query to generate the result, avoiding extensive traversing with PHP code. DB aggr function such as percentile_cont() and casting is used to make sure result is produced fast and accurate, this is also the reason for dropped support of SQLite/Mysql.

Ideally, the report results should be cached in various layers for performance benifits and resource consideration.


## Requirements

Make sure you have server requirements checked for [Laravel](https://laravel.com/docs/7.x/installation).

[Postgres 9.5](https://www.postgresql.org/download/) and above must be installed(for percentile_cont func). I have CI tests running on pgsql 9.5 and local machine running Unit tests on Postgres 12.5.

Since report summary using percentile function, SQLite and lower version of pgsql is not supported.

## Installation

#### Configuration

- Copy file `.env.example` to `.env`

- Edit .env for Postgres DB connection

- Install project dependencies

```
composer install
```

#### DB setup

Create Database with name defined in .env. For example,

```
createdb laravel
```

Run below command to create DB schema and seed DB with sample data.

CSV files in database/csvs are imported to seed DB.

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

Replace {uuid} with a Property's guid. {analyticid} is a integer. E.g 1.
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
