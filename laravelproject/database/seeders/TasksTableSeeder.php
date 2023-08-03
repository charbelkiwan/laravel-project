<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Task::create([
            'id' => 1,
            'title' => 'Task 1',
            'description' => 'Description of Task 1',
            'duedate' => '2023-12-15',
            'status' => 'in_progress',
            'project_id' => 1,
        ]);

        Task::create([
            'id' => 2,
            'title' => 'Task 2',
            'description' => 'Description of Task 2',
            'duedate' => '2023-11-30',
            'status' => 'completed',
            'project_id' => 2,
        ]);
    }
}
