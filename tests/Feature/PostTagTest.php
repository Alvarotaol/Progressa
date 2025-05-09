<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Laravel\Passport\Passport;
use Tests\TestCase;

class PostTagTest extends TestCase {
	use RefreshDatabase;

	protected User $user;
	protected Project $project;
	protected Collection $tags;

	protected function setUp(): void {
		parent::setUp();

		$this->user = User::factory()->create();
		$this->project = Project::factory()->for($this->user)->create();
		$this->tags = $this->project->tags()->createMany([
			['label' => 'Erro',				'color' => '#F8B4B4'],
			['label' => 'Funcionalidade',	'color' => '#A7F3D0'],
			['label' => 'Refatoração',		'color' => '#BFDBFE'],
		]);

		Passport::actingAs($this->user);
	}

	public function test_user_can_create_post_with_tags(): void {
		$response = $this->postJson(route('posts.store'), [
			'project_id' => $this->project->id,
			'content' => 'Primeiro post',
			'tags' => [['id' => $this->tags[0]->id,], ['id' => $this->tags[1]->id,]]
		]);

		$response->assertCreated()
			->assertJsonFragment(['content' => 'Primeiro post'])
			->assertJsonCount(2, 'tags')
			->assertJsonFragment(['id' => $this->tags[0]->id])
			->assertJsonFragment(['id' => $this->tags[1]->id]);
		$this->assertDatabaseHas('posts', ['content' => 'Primeiro post']);
		$this->assertDatabaseCount('post_tag', 2);
	}

	public function test_user_can_update_post_and_tags(): void {
		$post = Post::factory()->for($this->project)->create();

		$response = $this->putJson(route('posts.update', [$post]), [
			'content' => 'Conteúdo atualizado',
			'tags' => [['id' => $this->tags[2]->id]]
		]);

		$response
			->assertOk()
			->assertJsonFragment(['content' => 'Conteúdo atualizado'])
			->assertJsonCount(1, 'tags')
			->assertJsonFragment(['id' => $this->tags[2]->id]);

		$this->assertDatabaseHas('posts', ['id' => $post->id, 'content' => 'Conteúdo atualizado']);
		$this->assertDatabaseCount('post_tag', 1);
	}

	public function test_user_can_remove_all_tags_from_post(): void {
		$post = Post::factory()->for($this->project)->create();

		$post->tags()->attach($this->tags->pluck('id')->toArray());

		$this->assertDatabaseCount('post_tag', 3);

		$this->getJson(route('posts.show', [$post]))->assertJsonCount(3, 'tags');

		$response = $this->putJson(route('posts.update', [$post]), [
			'tags' => [],
		]);

		$response
			->assertOk()
			->assertJsonCount(0, 'tags');

		$this->assertDatabaseHas('posts', ['id' => $post->id]);
		$this->assertDatabaseCount('post_tag', 0);
	}

	public function test_user_can_let_tags_unchanged(): void {
		$post = Post::factory()->for($this->project)->create();

		$post->tags()->attach($this->tags->pluck('id')->toArray());

		$this->assertDatabaseCount('post_tag', 3);

		$response = $this->putJson(route('posts.update', [$post]), ["content" => "Post atualizado"]);

		$response
			->assertOk()
			->assertJsonCount(3, 'tags');

		$this->assertDatabaseHas('posts', ['id' => $post->id, 'content' => 'Post atualizado']);
		$this->assertDatabaseCount('post_tag', 3);
	}

	public function test_user_can_delete_post(): void {
		$post = Post::factory()->for($this->project)->create();

		$response = $this->deleteJson(route('posts.destroy', [$this->project, $post]));

		$response->assertNoContent();
		$this->assertDatabaseMissing('posts', ['id' => $post->id]);
		$this->assertDatabaseMissing('post_tag', ['post_id' => $post->id]);
	}

	public function test_tags_must_belong_to_project(): void {
		$anotherProject = Project::factory()->for($this->user)->create();
		$tagFromAnotherProject = $anotherProject->tags()->create(['label' => 'Tag alheia', 'color' => '#000000']);

		$response = $this->postJson(route('posts.store'), [
			'project_id' => $this->project->id,
			'content' => 'Post com tag inválida',
			'tags' => [['id' => $tagFromAnotherProject->id]],
		]);

		$response->assertUnprocessable()->assertJsonValidationErrors(['tags.0']);
	}

	public function test_post_can_be_created_without_tags(): void {
		$response = $this->postJson(route('posts.store'), [
			'project_id' => $this->project->id,
			'content' => 'Post sem tag',
			'tags' => [],
		]);

		$response->assertCreated();
		$this->assertDatabaseHas('posts', ['content' => 'Post sem tag']);
		$this->assertDatabaseCount('post_tag', 0);
	}
}
