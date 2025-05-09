<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListTagsRequest;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Http\Resources\TagResource;
use App\Models\Project;
use App\Models\Tag;
use Illuminate\Auth\Events\Validated;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TagController extends Controller {
	/**
	 * Display a listing of the resource.
	 */
	public function index(ListTagsRequest $request) {
		$tags = Tag::where('project_id', $request->project_id)->get();

		return TagResource::collection($tags);
	}

	/**
	 * Store a newly created resource in storage
	 */
	public function store(StoreTagRequest $request) {
		$project = Project::find($request->project_id);

		$tag = $project->tags()->create($request->all());

		return new TagResource($tag);
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Tag $tag) {
		return new TagResource($tag);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UpdateTagRequest $request, Tag $tag) {
		$tag->update($request->all());
		if ($tag->project->user_id != request()->user()->id) {
			throw new NotFoundHttpException('Tag not found');
		}
		return new TagResource($tag);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Tag $tag) {
		if ($tag->project->user_id != request()->user()->id) {
			throw new NotFoundHttpException('Tag not found');
		}
		$tag->delete();
		return response()->noContent();
	}
}
