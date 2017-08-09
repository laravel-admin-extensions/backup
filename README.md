laravel-admin-ext/backup
========================

[![Packagist](https://img.shields.io/packagist/l/laravel-admin-ext/backup.svg?maxAge=2592000)](https://packagist.org/packages/laravel-admin-ext/backup)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-admin-ext/backup.svg?style=flat-square)](https://packagist.org/packages/laravel-admin-ext/backup)

An admin interface for managing backups, inspired by https://github.com/laravel-backpack/backupmanager.

## Screenshot

![wx20170809-165225](https://user-images.githubusercontent.com/1479100/29113257-25a9904e-7d23-11e7-95e0-e85d37f79fdd.png)

## Installation

> Before installing this package, you must install [laravel-backup](https://github.com/spatie/laravel-backup) and complete the configuration.

```
$ composer require laravel-admin-ext/backup -vvv

$ php artisan admin:import backup
```

Open `http://your-host/admin/backup`.

License
------------
Licensed under [The MIT License (MIT)](LICENSE).
