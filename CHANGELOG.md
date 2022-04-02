v5.2.1 (2022-04-02)
==============================

- add CHANGELOG.md


v5.2.0 (2022-04-01)
==============================

- add "protected $table = 'xxx'" to model file


v5.1.* (2022-03-31)
==============================

- 支持参数: --cast,--event,--observer,--scope
- 支持相对路径参数--path_relative=Uipps/Admin
- 改为 artisan::call 方式
- 支持DSN连接信息 php artisan generate:models --connection="mysql://root:101010@127.0.0.1:3511/laravel_dev"
