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
            'name' => 'Charbel Kiwan',
            'email' => 'charbel_kiwan@outlook.com',
            'password' => 'Kiwan123',
        ]);

        User::factory()->times(9)->create();
    }
}
