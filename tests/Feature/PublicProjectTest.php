<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Passport\Passport;
use Tests\TestCase;

class PublicProjectTest extends TestCase {
	use RefreshDatabase;

	protected User $user;
	protected Project $projectPublic, $projectPrivate;

	public function setUp(): void {
		parent::setUp();
		//$this->user = User::factory()->create();
		$this->projectPublic = Project::factory()->create(['is_public' => true, 'public_slug' => 'meu-projeto']);
		$this->projectPrivate = Project::factory()->create(['is_public' => false, 'public_slug' => 'projeto-privado']);
		//Passport::actingAs($this->user);
	}

	public function test_retorna_projeto_publico_com_posts() {
		Post::factory()->for($this->projectPublic)->create([
			'is_hidden' => true,
		]);

		Post::factory(3)->for($this->projectPublic)->create([
			'is_hidden' => false,
		]);

		$response = $this->getJson(route('projects.public', ['slug' => $this->projectPublic->public_slug]));

		$response->assertOk()
			->assertJsonCount(3, 'posts.data');
	}

	public function test_retorna_posts_paginados() {

		Post::factory(30)->for($this->projectPublic)->create([
			'is_hidden' => false,
		]);
		$response = $this->getJson(route('projects.public', ['slug' => $this->projectPublic->public_slug, 'page' => 2]));

		$response->assertOk()
			->assertJsonPath('posts.current_page', 2)
			->assertJsonCount(15, 'posts.data');
	}

	public function test_nao_retorna_projeto_privado() {
		$this->getJson(route('projects.public', ['slug' => $this->projectPrivate->public_slug]))
			->assertNotFound();
	}

	public function test_retorna_somente_posts_visiveis() {

		Post::factory()->for($this->projectPublic)->create([
			'is_hidden' => false,
		]);

		Post::factory()->for($this->projectPublic)->create([
			'is_hidden' => true,
		]);

		$response = $this->getJson(route('projects.public', ['slug' => $this->projectPublic->public_slug]));

		$response->assertOk()
			->assertJsonCount(1, 'posts.data');
	}

	public function test_posts_vem_com_tags() {

		$post = Post::factory()->for($this->projectPublic)->create([
			'is_hidden' => false,
		]);

		$tag = Tag::factory()->create();
		$post->tags()->attach($tag);

		$response = $this->getJson(route('projects.public', ['slug' => $this->projectPublic->public_slug]));

		$response->assertOk()
			->assertJsonPath('posts.data.0.tags.0.label', $tag->label);
	}
}
