<?php

namespace Techsemicolon\MigrationPipeline;

use Illuminate\Database\MigrationServiceProvider;
use Techsemicolon\MigrationPipeline\PipelineMigrator;
use Techsemicolon\MigrationPipeline\MigratePipelineCommand;
use Techsemicolon\MigrationPipeline\PipelineMigrationRepository;
// use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends MigrationServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__.'/migrations');

        parent::register();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {}

    /**
     * Register the migrator service.
     *
     * @return void
     */
    protected function registerMigrator()
    {
        // The migrator is responsible for actually running and rollback the migration
        // files in the application. We'll pass in our database connection resolver
        // so the migrator can resolve any of these connections when it needs to.
        $this->app->singleton('migrator', function ($app) {
            $repository = $app['migration.repository'];

            return new PipelineMigrator($repository, $app['db'], $app['files']);
        });
    }

    /**
     * Register the migration repository service.
     *
     * @return void
     */
    protected function registerRepository()
    {
        $this->app->singleton('migration.repository', function ($app) {
            $table = $app['config']['database.migrations'];

            return new PipelineMigrationRepository($app['db'], $table);
        });
    }
}
