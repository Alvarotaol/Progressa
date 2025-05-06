<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		if($this->isCollection($request)) {
			return $this->toArrayCollection($request);
		} else {
			return $this->toArraySingle($request);
		}
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

	protected function isCollection(Request $request): bool {
		return $request->routeIs('projects.index');
	}

}
