<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class TagControllerTest extends TestCase {
	use RefreshDatabase;

	protected User $user;
	protected Project $project;

	protected function setUp(): void {
		parent::setUp();

		$this->user = User::factory()->create();
		Passport::actingAs($this->user);

		$this->project = Project::factory()->for($this->user)->create();
	}

	public function test_user_can_create_tag_in_own_project(): void {
		$this->postJson(route('tags.store'), [
			'label' => 'Bug',
			'color' => '#ff0000',
			'project_id' => $this->project->id,
		])->assertCreated();

		$this->assertDatabaseHas('tags', [
			'label' => 'Bug',
			'color' => '#ff0000',
			'project_id' => $this->project->id,
		]);
	}

	public function test_user_can_list_tags(): void {
		$this->project->tags()->createMany([
			['label' => 'Erro',				'color' => '#F8B4B4'],
			['label' => 'Funcionalidade',	'color' => '#A7F3D0'],
			['label' => 'Refatoração',		'color' => '#BFDBFE'],
		]);

		$this->getJson(route('tags.index', ['project_id' => $this->project]))
			->assertOk()
			->assertJsonCount(3);
	}

	public function test_user_can_update_tag(): void {
		$tag = Tag::factory()->for($this->project)->create(['label' => 'Old', 'color' => '#aaaaaa']);

		$this->putJson(route('tags.update', [$tag]), ['label' => 'New', 'color' => '#123456'])->assertOk();

		$this->assertDatabaseHas('tags', [
			'id' => $tag->id,
			'label' => 'New',
			'color' => '#123456',
		]);
	}

	public function test_user_can_delete_tag(): void {
		$tag = Tag::factory()->for($this->project)->create();

		$this->deleteJson(route('tags.destroy', [$tag]))->assertNoContent();

		$this->assertDatabaseMissing('tags', ['id' => $tag->id]);
	}

	public function test_cannot_create_duplicate_label_in_same_project(): void {
		Tag::factory()->for($this->project)->create(['label' => 'Bug']);

		$this->postJson(route('tags.store'), [
			'project_id' => $this->project->id,
			'label' => 'Bug',
			'color' => '#00ff00',
		])->assertUnprocessable()
			->assertJsonValidationErrors(['label']);
	}

	public function test_user_cannot_create_tag_in_another_users_project(): void {
		$otherUser = User::factory()->create();
		$otherProject = Project::factory()->for($otherUser)->create();

		$this->postJson(route('tags.store'), [
			'project_id' => $otherProject->id,
			'label' => 'Bug',
			'color' => '#00ff00',
		])->assertUnprocessable()
			->assertJsonValidationErrors(['project_id']);
	}

	public function test_can_use_same_label_in_different_projects(): void {
		Tag::factory()->for($this->project)->create(['label' => 'Bug']);

		$anotherProject = Project::factory()->for($this->user)->create();

		$this->postJson(route('tags.store'), [
			'project_id' => $anotherProject->id,
			'label' => 'Bug',
			'color' => '#00ff00',
		])->assertCreated();
	}

	public function test_invalid_color_format_should_fail(): void {
		$this->postJson(route('tags.store'), [
			'project_id' => $this->project->id,
			'label' => 'Bug',
			'color' => 'red', // inválido
		])->assertStatus(422);
	}

	public function test_user_cannot_manage_tags_of_another_users(): void {
		$otherUser = User::factory()->create();
		$otherProject = Project::factory()->for($otherUser)->create();
		$tag = Tag::factory()->for($otherProject)->create();

		$this
			->putJson(route('tags.update', [$tag]), ['label' => 'X', 'color' => '#000000'])
			->assertNotFound();

		$this
			->deleteJson(route('tags.destroy', [$tag]))
			->assertNotFound();
	}
}
