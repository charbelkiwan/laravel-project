<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;

class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::create([
            'id' => 1,
            'title' => 'Project 1',
            'description' => 'Description of Project A',
            'duedate' => '2023-12-31',
            'user_id' => 1,
        ]);

        Project::create([
            'id' => 2,
            'title' => 'Project 2',
            'description' => 'Description of Project B',
            'duedate' => '2023-11-15',
            'user_id' => 2,
        ]);
    }
}
