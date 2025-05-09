<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\Post;
use Laravel\Passport\Passport;

class PostControllerTest extends TestCase {
	use RefreshDatabase;

	private User $user, $otherUser;

	protected function setUp(): void {
		parent::setUp();
		$this->user = User::factory()->create();
		$this->otherUser = User::factory()->create();
		Passport::actingAs($this->user);
	}

	public function test_user_can_create_post() {
		$project = Project::factory()->for($this->user)->create();

		$response = $this->postJson(route('posts.store'), [
			'content' => 'Post de teste',
			'project_id' => $project->id,
		]);

		$response->assertCreated()
			->assertJsonFragment(['content' => 'Post de teste']);
	}

	//Não pode criar post com excesso de caracteres
	public function test_user_cannot_create_post_with_too_many_characters() {
		$project = Project::factory()->for($this->user)->create();

		$response = $this->postJson(route('posts.store'), [
			'content' => str_repeat('a', 1001),
			'project_id' => $project->id,
		]);

		$response->assertUnprocessable()->assertJsonValidationErrors(['content']);
	}

	public function test_user_cannot_create_post_in_another_user_project() {
		$project = Project::factory()->for($this->otherUser)->create();

		$response = $this->postJson(route('posts.store'), [
			'content' => 'Post de teste',
			'project_id' => $project->id,
		]);

		$response->assertUnprocessable()->assertJsonValidationErrors(['project_id']);
	}

	public function test_user_can_list_posts() {
		$project = Project::factory()->for($this->user)->create();
		Post::factory()->count(5)->for($project)->create();

		$response = $this->actingAs($this->user, 'api')->getJson(route('posts.index', ['project_id' => $project->id]));

		$response->assertOk()
			->assertJsonCount(5, 'data');
	}

	public function test_user_can_update_own_post() {
		$project = Project::factory()->for($this->user)->create();
		$post = Post::factory()->for($project)->create();

		$response = $this->actingAs($this->user, 'api')->putJson(route('posts.update', $post), [
			'content' => 'Atualizado',
		]);

		$response->assertOk()
			->assertJsonFragment(['content' => 'Atualizado']);
	}

	public function test_user_cannot_update_post_from_another_user() {
		$project = Project::factory()->for($this->otherUser)->create();
		$post = Post::factory()->for($project)->create();

		$response = $this->putJson(route('posts.update', $post), [
			'content' => 'Hackeando',
		]);

		$response->assertNotFound();
	}

	public function test_user_can_delete_own_post() {
		$project = Project::factory()->for($this->user)->create();
		$post = Post::factory()->for($project)->create();

		$response = $this->deleteJson(route('posts.destroy', $post));

		$response->assertNoContent();
	}

	public function test_user_cannot_delete_post_from_another_user() {
		$project = Project::factory()->for($this->otherUser)->create();
		$post = Post::factory()->for($project)->create();

		$response = $this->deleteJson(route('posts.destroy', $post));

		$response->assertNotFound();
	}

	public function test_validation_fails_when_creating_post_with_invalid_data() {
		$response = $this->postJson(route('posts.store'), [
			'content' => '',
			'project_id' => null,
		]);

		$response->assertUnprocessable()->assertJsonValidationErrors(['content', 'project_id']);
	}
}
