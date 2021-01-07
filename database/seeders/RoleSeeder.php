<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'kelola cuti']);
        Permission::create(['name' => 'kelola lokasi']);
        Permission::create(['name' => 'kelola pegawai']);
        Permission::create(['name' => 'kelola presensi']);
        Permission::create(['name' => 'lakukan cuti']);
        Permission::create(['name' => 'lakukan presensi']);
        
        $role = Role::create([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $role->givePermissionTo(Permission::all());

        $rolePegawai =  Role::create([
            'name' => 'pegawai',
            'guard_name' => 'web',
        ]);

        $rolePegawai->givePermissionTo(['lakukan cuti', 'lakukan presensi']);
    }
}
