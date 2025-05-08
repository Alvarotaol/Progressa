<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory {
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array {
		return [
			"content" => $this->faker->paragraph(2),
			"project_id" => Project::factory(),
			"is_hidden" => $this->faker->boolean(),
			"created_at" => now(),
			"updated_at" => now(),
		];
	}
}
