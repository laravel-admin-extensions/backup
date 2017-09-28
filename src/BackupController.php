<?php

namespace Encore\Admin\Backup;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class BackupController
{
    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('Backup');

            $backup = new Backup();

            $content->body(view('laravel-admin-backup::index', [
                'backups' => $backup->getExists(),
            ]));
        });
    }

    /**
     * Download a backup zip file.
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\BinaryFileResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function download(Request $request)
    {
        $disk = $request->get('disk');
        $file = $request->get('file');

        $storage = Storage::disk($disk);

        $fullPath = $storage->getDriver()->getAdapter()->applyPathPrefix($file);

        if (File::isFile($fullPath)) {
            return response()->download($fullPath);
        }

        return response('', 404);
    }

    /**
     * Run `backup:run` command.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function run()
    {
        try {
            ini_set('max_execution_time', 300);

            // start the backup process
            Artisan::call('backup:run');

            $output = Artisan::output();

            return response()->json([
                'status'  => true,
                'message' => $output,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Delete a backup file.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $disk = Storage::disk($request->get('disk'));
        $file = $request->get('file');

        if ($disk->exists($file)) {
            $disk->delete($file);

            return response()->json([
                'status'  => true,
                'message' => trans('admin.delete_succeeded'),
            ]);
        }

        return response()->json([
            'status'  => false,
            'message' => trans('admin.delete_failed'),
        ]);
    }
}
