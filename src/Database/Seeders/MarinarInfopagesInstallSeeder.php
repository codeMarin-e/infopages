<?php
    namespace Marinar\Infopages\Database\Seeders;

    use Illuminate\Database\Seeder;
    use Marinar\Infopages\MarinarInfopages;

    class MarinarInfopagesInstallSeeder extends Seeder {

        use \Marinar\Marinar\Traits\MarinarSeedersTrait;

        public function run() {
            if(!in_array(env('APP_ENV'), ['dev', 'local'])) return;
            static::$packageName = 'marinar_infopages';
            static::$packageDir = MarinarInfopages::getPackageMainDir();

            $this->autoInstall();

            $this->refComponents->info("Done!");
        }

    }
