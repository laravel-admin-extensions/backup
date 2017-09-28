<?php

namespace Encore\Admin\Backup;

use Encore\Admin\Admin;
use Encore\Admin\Extension;
use Spatie\Backup\Commands\ListCommand;
use Spatie\Backup\Tasks\Monitor\BackupDestinationStatus;
use Spatie\Backup\Tasks\Monitor\BackupDestinationStatusFactory;

class Backup extends Extension
{
    public function getExists()
    {
        $statuses = BackupDestinationStatusFactory::createForMonitorConfig(config('backup.monitorBackups'));

        $listCommand = new ListCommand();

        $rows = $statuses->map(function (BackupDestinationStatus $backupDestinationStatus) use ($listCommand) {
            return $listCommand->convertToRow($backupDestinationStatus);
        })->all();

        foreach ($statuses as $index => $status) {
            $name = $status->backupDestination()->backupName();

            $files = array_map('basename', $status->backupDestination()->disk()->allFiles($name));

            $rows[$index]['files'] = array_slice(array_reverse($files), 0, 30);
        }

        return $rows;
    }

    /**
     * Bootstrap this package.
     *
     * @return void
     */
    public static function boot()
    {
        static::registerRoutes();

        Admin::extend('backup', __CLASS__);
    }

    /**
     * Register routes for laravel-admin.
     *
     * @return void
     */
    protected static function registerRoutes()
    {
        parent::routes(function ($router) {
            /* @var \Illuminate\Routing\Router $router */
            $router->get('backup', 'Encore\Admin\Backup\BackupController@index')->name('backup-list');
            $router->get('backup/download', 'Encore\Admin\Backup\BackupController@download')->name('backup-download');
            $router->post('backup/run', 'Encore\Admin\Backup\BackupController@run')->name('backup-run');
            $router->delete('backup/delete', 'Encore\Admin\Backup\BackupController@delete')->name('backup-delete');
        });
    }

    /**
     * {@inheritdoc}
     */
    public static function import()
    {
        parent::createMenu('Backup', 'backup', 'fa-copy');

        parent::createPermission('Backup', 'ext.backup', 'backup*');
    }
}
