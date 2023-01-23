<?php
    namespace Marinar\Infopages;

    use Marinar\Infopages\Database\Seeders\MarinarInfopagesInstallSeeder;

    class MarinarInfopages {

        public static function getPackageMainDir() {
            return __DIR__;
        }

        public static function injects() {
            return MarinarInfopagesInstallSeeder::class;
        }
    }
