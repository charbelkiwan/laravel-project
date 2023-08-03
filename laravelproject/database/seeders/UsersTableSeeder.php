<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'id' => 1,
            'name' => 'Charbel Kiwan',
            'email' => 'charbel_kiwan@outlook.com',
            'password' => bcrypt('password123'),
        ]);

        User::create([
            'id' => 2,
            'name' => 'Elie kiwan',
            'email' => 'eliekiwan10@gmail.com',
            'password' => bcrypt('password456'),
        ]);
    }
}
