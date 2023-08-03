<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class ProjectFactory extends Factory
{
    protected $model = App\Models\Project::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'duedate' => $this->faker->dateTimeBetween('now', '+1 year'),
            'user_id' => User::factory()->create()->id,
        ];
    }
}
