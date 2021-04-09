<?php

namespace Aldrumo\Core\Console\Commands;

use Aldrumo\Core\Facades\Aldrumo;
use Composer\Semver\Comparator;
use Illuminate\Console\Command;

class AldrumoUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aldrumo:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Aldrumo';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->output->write(PHP_EOL . "<fg=cyan>    ___    __    __
   /   |  / /___/ /______  ______ ___  ____
  / /| | / / __  / ___/ / / / __ `__ \/ __ \
 / ___ |/ / /_/ / /  / /_/ / / / / / / /_/ /
/_/  |_/_/\__,_/_/   \__,_/_/ /_/ /_/\____/ </>" . PHP_EOL . PHP_EOL);
        $currentVersion = Aldrumo::currentVersion();

        $updates = new \DirectoryIterator(realpath(__DIR__ . '/../../../updates'));
        foreach ($updates as $update) {
            if ($update->isDot()) {
                continue;
            }

            $version = $update->getBasename('.php');

            if (Comparator::greaterThan($version, $currentVersion)) {
                require_once $update->getRealPath();

                $migrationName = 'Update_' . str_replace('.', '', $version);
                $migration = new $migrationName();
                $migration->handle();
            }
        }

        //$this->complete();

        //$this->newLine();
        //$this->info('Aldrumo has been updated to v' . Aldrumo::version());
    }

    protected function complete()
    {
        file_put_contents(
            base_path('aldrumo.installed'),
            Aldrumo::version()
        );
    }
}
