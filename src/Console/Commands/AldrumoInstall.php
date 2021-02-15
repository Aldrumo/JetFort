<?php

namespace Aldrumo\Core\Console\Commands;

use Aldrumo\Core\Facades\Aldrumo;
use Aldrumo\Core\Models\User;
use Aldrumo\Settings\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Finder\Finder;

class AldrumoInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aldrumo:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Aldrumo';

    /** @var array */
    protected $steps = [
        'clearMigrations',
        'clearRouteFiles',
        'clearModels',
        'publishConfigs',
        'updateConfigs',
        'setupEnv',
        'migrate',
        'installTheme',
        'publishAssets',
        'createAdmin'
    ];

    /** @var Finder */
    protected $finder;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Finder $finder)
    {
        $this->finder = $finder;
        parent::__construct();
    }

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

        $this->siteName = $this->ask('What is your site name?');
        $this->siteUrl = $this->ask('What is your site url?');
        $this->dbHost = $this->ask('What is your database host?');
        $this->dbUser = $this->ask('What is your database user?');
        $this->dbPass = $this->secret('What is your database password?');
        $this->dbName = $this->ask('What is your database name?');

        $this->adminName = $this->ask('What is the Admins name?');
        $this->adminEmail = $this->ask('What is the Admins email?');
        $this->adminPass = $this->secret('What is the Admins password?');
        $passConfirm = $this->secret('Confirm Password');

        if ($this->adminPass !== $passConfirm) {
            $this->error('Admin passwords did not match.');
            return;
        }

        $bar = $this->output->createProgressBar(
            count($this->steps)
        );
        $bar->start();

        collect($this->steps)
            ->each(
                function ($func) use ($bar) {
                    $this->$func();
                    $bar->advance();
                }
            );

        $this->complete();
        $bar->finish();

        $this->newLine();
        $this->info('Aldrumo has been installed.');
        $this->info('You can now login to your admin panel at ' . $this->siteUrl . '/admin');
    }

    protected function clearMigrations()
    {
        foreach ($this->finder->in(base_path('database/migrations/')) as $file) {
            unlink($file->getPathname());
        }
    }

    protected function clearRouteFiles()
    {
        foreach ($this->finder->in(base_path('routes/')) as $file) {
            file_put_contents(
                $file->getPathname(),
                '<?php'
            );
        }
    }

    protected function clearModels()
    {
        foreach ($this->finder->in(base_path('app/models/')) as $file) {
            unlink($file->getPathname());
        }
    }

    protected function publishConfigs()
    {
        $this->callSilently('vendor:publish', ['--tag' => 'aldrumo', '--force' => true]);
    }

    protected function publishAssets()
    {
        $this->callSilently('vendor:publish', ['--tag' => 'aldrumo-public', '--force' => true]);
    }

    protected function updateConfigs()
    {
        $this->replaceInFile(
            "'model' => App\Models\User::class,",
            "'model' => Aldrumo\Core\Models\User::class,",
            config_path('auth.php')
        );

        $this->replaceInFile(
            "'paths' => [",
            "'paths' => [\nbase_path('vendor/aldrumo/admin/resources/views'),\n",
            config_path('view.php')
        );

        $this->replaceInFile(
            "'controller' => null,",
            "'controller' => \Aldrumo\Core\Http\Controllers\PageController::class,",
            config_path('routeloader.php')
        );
    }

    protected function setupEnv()
    {
        $this->replaceInFile(
            "APP_NAME=" . env('APP_NAME'),
            'APP_NAME="' . $this->siteName . '"',
            base_path('.env')
        );
        $this->replaceInFile(
            "APP_URL=" . env('APP_URL'),
            'APP_URL="' . $this->siteUrl . '"',
            base_path('.env')
        );
        $this->replaceInFile(
            "DB_HOST=" . env('DB_HOST'),
            'DB_HOST="' . $this->dbHost . '"',
            base_path('.env')
        );
        $this->replaceInFile(
            "DB_DATABASE=" . env('DB_DATABASE'),
            'DB_DATABASE="' . $this->dbName . '"',
            base_path('.env')
        );
        $this->replaceInFile(
            "DB_USERNAME=" . env('DB_USERNAME'),
            'DB_USERNAME="' . $this->dbUser . '"',
            base_path('.env')
        );
        $this->replaceInFile(
            "DB_PASSWORD=" . env('DB_PASSWORD'),
            'DB_PASSWORD="' . $this->dbPass . '"',
            base_path('.env')
        );

        $this->replaceInFile("'SESSION_DRIVER', 'file'", "'SESSION_DRIVER', 'database'", config_path('session.php'));
        $this->replaceInFile('SESSION_DRIVER=file', 'SESSION_DRIVER=database', base_path('.env'));

        $this->reloadEnv();
    }

    protected function migrate()
    {
        $this->callSilently('migrate');
    }

    protected function installTheme()
    {
        Setting::create([
            'slug'         => 'activeTheme',
            'setting_data' => 'Aldrumo21',
        ]);
    }

    protected function createAdmin()
    {
        /** @var User $user */
        $user = User::create([
            'name' => $this->adminName,
            'email' => $this->adminEmail,
            'password' => Hash::make($this->adminPass),
            'is_admin' => true,
        ]);
        $user->markEmailAsVerified();
    }

    protected function complete()
    {
        file_put_contents(
            base_path('aldrumo.installed'),
            Aldrumo::version()
        );
    }

    /**
     * Replace the given string in the given file.
     * @link https://github.com/laravel/installer/blob/master/src/NewCommand.php
     */
    protected function replaceInFile(string $search, string $replace, string $file)
    {
        file_put_contents(
            $file,
            str_replace($search, $replace, file_get_contents($file))
        );
    }

    protected function reloadEnv()
    {
        $app = app();
        (new LoadEnvironmentVariables())->bootstrap($app);
        (new LoadConfiguration())->bootstrap($app);
    }
}
