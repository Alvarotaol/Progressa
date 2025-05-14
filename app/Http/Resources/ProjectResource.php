<?php

namespace App\Http\Resources;

use App\Models\Post;
use Database\Seeders\PostSeeder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource {
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array {
		if ($request->routeIs('projects.index')) {
			return $this->toArrayCollection($request);
		} elseif ($request->routeIs('projects.public')) {
			return $this->toPublicProjectArray($request);
		} else {
			return $this->toArraySingle($request);
		}
	}

	protected function toPublicProjectArray(Request $request): array {
		$posts = Post::where('is_hidden', false)->where('project_id', $this->id)->orderBy('created_at', 'desc')->paginate();
		return [
			'project' => [
				'id' => $this->id,
				'name' => $this->name,
				'description' => $this->description,
			],
			'posts' => [
				'data' => PostResource::collection($posts),
				'current_page' => $posts->currentPage(),
				'last_page' => $posts->lastPage(),
			],
		];
	}

	protected function toArrayCollection(Request $request): array {
		return [
			'id' => $this->id,
			'name' => $this->name,
			'is_public' => $this->is_public,
			'description' => $this->description,
			'public_slug' => $this->public_slug,
		];
	}

	protected function toArraySingle(Request $request): array {
		return [
			'id' => $this->id,
			'name' => $this->name,
			'user_id' => $this->user_id,
			'is_public' => $this->is_public,
			'description' => $this->description,
			'tags' => TagResource::collection($this->tags),
			'public_slug' => $this->public_slug,
		];
	}
}
