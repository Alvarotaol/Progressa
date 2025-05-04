<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory {
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array {
		return [
			"user_id" => 1,
			"name" => "Projeto " . $this->faker->randomNumber(2),
			"public_slug" => "/projeto-" . $this->faker->randomNumber(2) . "u-1-" . $this->faker->randomNumber(2),
			"is_public" => $this->faker->boolean(),
			"is_active" => $this->faker->boolean(),
			"description" => $this->faker->text(),
			"created_at" => now(),
			"updated_at" => now(),
		];
	}
}
