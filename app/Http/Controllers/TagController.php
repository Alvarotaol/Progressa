<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListTagsRequest;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;

class TagController extends Controller {
	/**
	 * Display a listing of the resource.
	 */
	public function index(ListTagsRequest $request) {
		$tags = Tag::where('project_id', $request->project_id)->get();

		return TagResource::collection($tags);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(StoreTagRequest $request) {
		$tag = Tag::create($request->all());

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
		return new TagResource($tag);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Tag $tag) {
		$tag->delete();
		return response()->noContent();
	}
}
