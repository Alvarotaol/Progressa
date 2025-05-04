<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

//Red Pastel	#F8B4B4
//Green Pastel	#A7F3D0
//Blue Pastel	#BFDBFE
//Yellow Pastel	#FEF3C7
//Purple Pastel	#E9D5FF
//Pink Pastel	#FBCFE8
//Orange Pastel	#FCD9B8

class TagSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 */
	public function run(): void {

		$projects = Project::all();

		foreach ($projects as $project) {
			Tag::create([
				'project_id' => $project->id,
				'label' => 'Bug',
				'color' => '#F8B4B4',
			]);

			Tag::create([
				'project_id' => $project->id,
				'label' => 'Feature',
				'color' => '#A7F3D0',
			]);

			Tag::create([
				'project_id' => $project->id,
				'label' => 'Task',
				'color' => '#BFDBFE',
			]);

			Tag::create([
				'project_id' => $project->id,
				'label' => 'Story',
				'color' => '#FEF3C7',
			]);
		}

		//Create random attachments
		foreach ($projects as $project) {
			foreach ($project->posts as $post) {
				$tags = Tag::where('project_id', $project->id)->get();
				$tags->random(rand(1, 3))->each(function ($tag) use ($post) {
					$post->tags()->attach($tag->id);
				});
			}
		}
	}
}
