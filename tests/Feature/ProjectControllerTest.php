<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ProjectControllerTest extends TestCase
{
	use RefreshDatabase;

	private User $user, $otherUser;

	protected function setUp(): void
	{
		parent::setUp();
		$this->user = User::factory()->create();
		$this->otherUser = User::factory()->create();
		Passport::actingAs($this->user);
	}

	public function test_can_list_projects()
	{
		Project::factory()->count(3)->create(['user_id' => $this->user->id]);

		$response = $this->getJson(route('projects.index'));

		$response->assertOk();
		$response->assertJsonCount(3);
	}

	public function test_can_create_project()
	{
		$data = [
			'name' => 'Meu Projeto',
			'description' => 'Teste de criação',
		];

		$response = $this->postJson(route('projects.store'), $data);

		$response->assertCreated();
		$this->assertDatabaseHas('projects', [
			'name' => 'Meu Projeto',
			'user_id' => $this->user->id
		]);
	}

	public function test_cannot_create_project_without_name()
	{
		$response = $this->postJson(route('projects.store'), [
			'description' => 'Sem nome'
		]);

		$response->assertStatus(422);
	}

	public function test_can_update_own_project()
	{
		$project = Project::factory()->create(['user_id' => $this->user->id]);

		$response = $this->putJson(route('projects.update', ['project' => $project->id]), [
			'name' => 'Atualizado'
		]);

		$response->assertOk();
		$this->assertDatabaseHas('projects', [
			'id' => $project->id,
			'name' => 'Atualizado'
		]);
	}

	public function test_cannot_update_other_user_project()
	{
		$otherProject = Project::factory()->create(['user_id' => $this->otherUser->id]);

		$response = $this->putJson(route('projects.update', ['project' => $otherProject->id]), [
			'name' => 'Hackeado'
		]);

		$response->assertStatus(403);
	}

	public function test_can_delete_own_project()
	{
		$project = Project::factory()->create(['user_id' => $this->user->id]);

		$response = $this->deleteJson(route('projects.destroy', ['project' => $project->id]));

		$response->assertNoContent();
		$this->assertDatabaseMissing('projects', ['id' => $project->id]);
	}

	public function test_cannot_delete_other_user_project()
	{
		$project = Project::factory()->create(['user_id' => $this->otherUser->id]);

		$response = $this->deleteJson(route('projects.destroy', ['project' => $project->id]));

		$response->assertStatus(403);
	}

	public function test_cannot_show_nonexistent_project()
	{
		$response = $this->getJson(route('projects.show', ['project' => 999]));

		$response->assertNotFound();
	}

	public function test_can_show_own_project()
	{
		$project = Project::factory()->create(['user_id' => $this->user->id]);

		$response = $this->getJson(route('projects.show', ['project' => $project->id]));

		$response->assertOk();
		$response->assertJsonPath('id', $project->id);
	}
}
