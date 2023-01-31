<?php
namespace Database\Seeders\Packages\Infopages;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class MarinarInfopagesSeeder extends Seeder {

    public function run() {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Permission::upsert([
            ['guard_name' => 'admin', 'name' => 'infopages.view'],
            ['guard_name' => 'admin', 'name' => 'infopage.create'],
            ['guard_name' => 'admin', 'name' => 'infopage.update'],
            ['guard_name' => 'admin', 'name' => 'infopage.delete'],
        ], ['guard_name','name']);
    }
}
