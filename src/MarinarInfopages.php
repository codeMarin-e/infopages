<?php
    namespace Marinar\Infopages;

    use Marinar\Infopages\Database\Seeders\MarinarInfopagesInstallSeeder;

    class MarinarInfopages {
        //just for testing

        public static function getPackageMainDir() {
            return __DIR__;
        }

        public static function injects() {
            return MarinarInfopagesInstallSeeder::class;
        }
    }
