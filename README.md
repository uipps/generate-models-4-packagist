# auto generate models base on laravel
  auto generate models for each table base on laravel function. 
  
## How does it work?

This package expects that you are using Laravel 5.1 or above.
You will need to import the `uipps/generate-models-4-packagist` package via composer:

### Configuration

```shell
composer require uipps/generate-models-4-packagist --dev
```

### Usage

Assuming you have already configured your database, you are now all set to go.

- Let's scaffold some of your models from your default connection.

```shell
php artisan generate:models
```

- You can scaffold a specific table like this:

```shell
php artisan generate:models --table=users
```

- You can also specify the connection:

```shell
php artisan generate:models --connection=mysql
```

- If you are using a MySQL database, you can specify which schema you want to scaffold:

```shell
php artisan generate:models --schema=shop
```

- other params
```
php artisan generate:models --type=model --class-name=role --all
means:
php artisan make:model role --all

```

- make controller
```
php artisan generate:models --type=controller uipps/Admin/CountryController --resource --model=uipps/Admin/Country
means:
php artisan make:controller uipps/Admin/CountryController --resource --model=uipps/Admin/Country

```

#### Support

For the time being, this package supports MySQL, PostgreSQL and SQLite databases. Support for other databases are encouraged to be added through pull requests.
