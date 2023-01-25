<?php
    namespace Marinar\Infopages\Database\Seeders;

    use Illuminate\Database\Seeder;
    use Marinar\Infopages\MarinarInfopages;

    class MarinarInfopagesInstallSeeder extends Seeder {

        use \Marinar\Marinar\Traits\MarinarSeedersTrait;

        public static function configure() {
            static::$packageName = 'marinar_infopages';
            static::$packageDir = MarinarInfopages::getPackageMainDir();
        }

        public function run() {
            if(!in_array(env('APP_ENV'), ['dev', 'local'])) return;

            $this->autoInstall();

            $this->refComponents->info("Done!");
        }

    }
