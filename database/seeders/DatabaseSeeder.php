<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Project;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder {
	/**
	 * Seed the application's database.
	 */

	//  ['Erro', 'Funcionalidade', 'Refatoração', 'Dúvida', 'Melhoria']
	// ['#F8B4B4', '#A7F3D0', '#BFDBFE', '#FEF3C7', '#E9D5FF']
	public function run(): void {
		User::factory(3)
			->create()
			->each(function ($user) {
				Project::factory(5)
					->create(['user_id' => $user->id])
					->each(function ($project) {
						$tags = $project->tags()->createMany([
							['label' => 'Erro',				'color' => '#F8B4B4'],
							['label' => 'Funcionalidade',	'color' => '#A7F3D0'],
							['label' => 'Refatoração',		'color' => '#BFDBFE'],
							['label' => 'Dúvida',			'color' => '#FEF3C7'],
							['label' => 'Melhoria',			'color' => '#E9D5FF'],
						]);

						Post::factory(20)
							->create(['project_id' => $project->id])
							->each(function ($post) use ($tags) {
								$post->tags()->attach(
									$tags->random(rand(1, 3))->pluck('id')->toArray()
								);
							});
					})
				;
			})
		;

		//Cria personal access client
		Artisan::call('passport:client --personal --name="Progressa Personal Access Client" --provider="users"');
	}
}
