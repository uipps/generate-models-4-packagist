# auto generate models and controllers base on laravel
  auto generate models and controllers for each table base on laravel function. 
  
## How does it work?

This package expects that you are using Laravel 5.1 or above.
You will need to import the `uipps/generate-models-4-packagist` package via composer:

### Configuration

```shell
composer require uipps/generate-models-4-packagist
```

### Usage

Assuming you have already configured your database, you are now all set to go.

- Let's scaffold some of your models from your default connection.

```shell
php artisan generate:models

php artisan generate:models --cast=1 --event=1 --observer=1 --scope=1
```

- You can scaffold a specific table like this:

```shell
php artisan generate:models --table=users
```

- You can also specify the connection:

```shell
php artisan generate:models --connection=mysql
php artisan generate:models -c mysql

// dsn connection
php artisan generate:models -c "mysql://root:101010@127.0.0.1:3511/laravel_dev"
```

- If you are using a MySQL database, you can specify which database you want to scaffold:

```shell
php artisan generate:models --database=shop
```

- other params
```
php artisan generate:models --table=Country --path_relative=Uipps/Admin
means:
php artisan make:controller Uipps/Admin/CountryController --model=Uipps/Admin/Country


// make cast,event,observer,scope
php artisan generate:models -p Uipps/ --cast=1 --event=1 --observer=1 --scope=1
or:
php artisan generate:models -p Uipps/ --cast=1 -e 1 -o 1 -s 1

```

- make controller
```
php artisan generate:models -t Country -p Uipps/Admin
means:
php artisan make:controller uipps/Admin/CountryController --model=uipps/Admin/Country

```

- full params
```
php artisan generate:models -c "mysql://root:101010@127.0.0.1:3511" -d laravel_dev -t project -p Uipps/Admin --cast=1 -e 1 -o 1 -s 1
```

#### Support

For the time being, this package supports MySQL only. PostgreSQL and SQLite databases will be Supported in future.
