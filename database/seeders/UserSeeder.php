<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'sakib@gmail.com',
            'mobile' => '9045269853',
            'role' => 'Admin',
            'password' => Hash::make('123456'),
            'pass' => '123456',
            
        ]);

    }
}
