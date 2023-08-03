<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Project;

class TaskFactory extends Factory
{
    protected $model = App\Models\Task::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph,
            'duedate' => $this->faker->dateTimeBetween('now', '+1 year'),
            'status' => $this->faker->randomElement(['pending', 'in_progress', 'completed']),
            'project_id' => Project::factory()->create()->id,
        ];
    }
}
