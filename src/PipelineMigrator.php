<?php

namespace Techsemicolon\MigrationPipeline;

use Illuminate\Database\Migrations\Migrator;

class PipelineMigrator extends Migrator
{
    /**
     * Run "up" a migration instance.
     *
     * @param  string  $file
     * @param  int     $batch
     * @param  bool    $pretend
     * @return void
     */
    protected function runUp($file, $batch, $pretend)
    {
        // First we will resolve a "real" instance of the migration class from this
        // migration file name. Once we have the instances we can run the actual
        // command such as "up" or "down", or we can just simulate the action.
        $migration = $this->resolve(
            $name = $this->getMigrationName($file)
        );

        if ($pretend) {
            return $this->pretendToRun($migration, 'up');
        }

        if($this->repository->isMigrated($name, $batch)){
            return;
        }

        $this->note("<comment>Migrating:</comment> {$name}");

        // First add an entry into migrations table marking
        // is_active = true indicating that the migration is 
        // now active and running
        $this->repository->makeActive($name, $batch);

        $this->runMigration($migration, 'up');

        // Once we have run a migrations class, we will log that it was run in this
        // repository so that we don't try to run it next time we do a migration
        // in the application. A migration repository keeps the migrate order.
        // We will update the earlier inserted record itself by making
        // is_active = false
        $this->repository->log($name, $batch);

        $this->note("<info>Migrated:</info>  {$name}");
    }
}
