<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@email.com',
            'password' => bcrypt('123456789')
        ]);

        $admin->assignRole('admin');

        $pegawai = User::create([
            'name' => 'pegawai',
            'email' => 'pegawai@email.com',
            'password' => bcrypt('1234567890')
        ]);

        $pegawai->assignRole('pegawai');
    }
}
