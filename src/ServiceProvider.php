<?php

namespace October\Rain\Config;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use October\Rain\Config\DataWriter\FileWriter;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        // Bind it only once so we can reuse in IoC
        $this->app->singleton($this->repository(), function ($app, $items) {
            $writer = new FileWriter($this->getFiles(), $this->getConfigPath());

            return new Repository($writer, $items);
        });

        $this->app->extend('config', fn ($config, $app) =>  $app->make($this->repository(), $config->all()));
    }

    public function repository()
    {
        return Repository::class;
    }

    protected function getFiles(): Filesystem
    {
        return $this->app['files'];
    }

    protected function getConfigPath(): string
    {
        return $this->app['path.config'];
    }
}
