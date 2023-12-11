<?php declare(strict_types=1);

namespace App\Providers;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use Spatie\Dropbox\Client;
use Spatie\FlysystemDropbox\DropboxAdapter;

class DropboxServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Storage::extend('dropbox', static function ($app, $config) {
            $client = new Client(env('DROPBOX_ACCESS_TOKEN'));
            $dropboxAdapter = new DropboxAdapter($client);
            $filesystem = new Filesystem($dropboxAdapter, ['case_sensitive' => false]);

            return new FilesystemAdapter($filesystem, $dropboxAdapter);
        });
    }
}
