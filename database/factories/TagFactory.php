<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tag>
 */
class TagFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{

		$labels = ['Erro', 'Funcionalidade', 'Refatoração', 'Dúvida', 'Melhoria'];
		$colors = ['#F8B4B4', '#A7F3D0', '#BFDBFE', '#FEF3C7', '#E9D5FF'];

		$ids = [0, 1, 2, 3, 4];
		$random = $this->faker->randomElement($ids);
		return [
			'label' => $labels[$random],
			'color' => $colors[$random],
			'project_id' => Project::factory(),
		];
	}
}
