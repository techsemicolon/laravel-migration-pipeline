<?php

namespace Techsemicolon\MigrationPipeline;

use Illuminate\Database\Migrations\DatabaseMigrationRepository;

class PipelineMigrationRepository extends DatabaseMigrationRepository
{

    /**
     * Log that a migration was run and mark as active
     *
     * @param  string  $file
     * @param  int  $batch
     * @return void
     */
    public function makeActive($file, $batch)
    {
        $record = ['migration' => $file, 'batch' => $batch, 'is_active' => true];

        $this->table()->insert($record);
    }

    /**
     * Check if migration is already migrated
     *
     * @param  string  $file
     * @param  int  $batch
     * @return void
     */
    public function isMigrated($file, $batch)
    {
        $record = ['migration' => $file];

        return $this->table()->where($record)->exists();
    }

    /**
     * Mark the migration that was run as not active
     *
     * @param  string  $file
     * @param  int  $batch
     * @return void
     */
    public function log($file, $batch)
    {
        $record = ['migration' => $file, 'batch' => $batch];

        $this->table()->where($record)->update(['is_active' => false]);
    }

}