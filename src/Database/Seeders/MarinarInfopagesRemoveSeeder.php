<?php
    namespace Marinar\Infopages\Database\Seeders;

    use App\Models\Infopage;
    use Illuminate\Database\Seeder;
    use Marinar\Infopages\MarinarInfopages;
    use Spatie\Permission\Models\Permission;

    class MarinarInfopagesRemoveSeeder extends Seeder {

        use \Marinar\Marinar\Traits\MarinarSeedersTrait;

        public static function configure() {
            static::$packageName = 'marinar_infopages';
            static::$packageDir = MarinarInfopages::getPackageMainDir();
        }

        public function run() {
            if(!in_array(env('APP_ENV'), ['dev', 'local'])) return;

            $this->autoRemove();

            $this->refComponents->info("Done!");
        }

        public function clearMe() {
            $this->refComponents->task("Clear DB", function() {
                foreach(Infopage::get() as $infopage) {
                    $infopage->delete();
                }
                Permission::whereIn('name', [
                    'infopages.view',
                    'infopage.create',
                    'infopage.update',
                    'infopage.delete',
                ])
                ->where('guard_name', 'admin')
                ->delete();
                app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
                return true;
            });
        }
    }
