<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'       => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status'      => $this->faker->randomElement(['open', 'in_progress', 'canceled', 'done']),
            'assigned_to' => UserFactory::new(),
            'building_id' => BuildingFactory::new(),
        ];
    }
}
