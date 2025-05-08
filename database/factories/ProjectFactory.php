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
			'name' => $this->faker->words(3, true),
			'description' => $this->faker->sentence,
			'is_active' => true,
			'is_public' => false,
			'user_id' => \App\Models\User::factory(),
			"created_at" => now(),
			"updated_at" => now(),
		];
	}
}
