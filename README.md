# laravel auto generate models
  依据数据库，自动生成Laravle所有表的Model. 
  
## How does it work?

This package expects that you are using Laravel 5.1 or above.
You will need to import the `uipps/generate-models-4-packagist` package via composer:

### Configuration

It is recommended that this package should only be used on a local environment for security reasons. You should install it via composer using the --dev option like this:

```shell
composer require uipps/generate-models-4-packagist --dev
```

Add the `models.php` configuration file to your `config` directory and clear the config cache:

```shell
php artisan vendor:publish --tag=uipps-models

# Let's refresh our config cache just in case
php artisan config:clear
```

### Usage

Assuming you have already configured your database, you are now all set to go.

- Let's scaffold some of your models from your default connection.

```shell
php artisan code:models
```

- You can scaffold a specific table like this:

```shell
php artisan code:models --table=users
```

- You can also specify the connection:

```shell
php artisan code:models --connection=mysql
```

- If you are using a MySQL database, you can specify which schema you want to scaffold:

```shell
php artisan code:models --schema=shop
```

### Customizing Model Scaffolding

To change the scaffolding behaviour you can make `config/models.php` configuration file
fit your database needs. [Check it out](https://github.com/uipps/generate-models-4-packagist/blob/laravel_auto_model/config/models.php) ;-)

### Tips

#### 1. Keeping model changes

You may want to generate your models as often as you change your database. In order
not to lose your own model changes, you should set `base_files` to `true` in your `config/models.php`.

When you enable this feature your models will inherit their base configurations from
base models. You should avoid adding code to your base models, since you
will lose all changes when they are generated again.

> Note: You will end up with two models for the same table and you may think it is a horrible idea 
to have two classes for the same thing. However, it is up to you
to decide whether this approach gives value to your project :-)

#### Support

For the time being, this package supports MySQL, PostgreSQL and SQLite databases. Support for other databases are encouraged to be added through pull requests.
