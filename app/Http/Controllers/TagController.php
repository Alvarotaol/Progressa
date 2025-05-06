<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;

class TagController extends Controller {
	/**
	 * Display a listing of the resource.
	 */
	public function index() {
		if(request()->project_id) {
			return Tag::where('project_id', request()->project_id)->get();
		}
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(StoreTagRequest $request) {
		$tag = Tag::create($request->all());

		return $tag;
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Tag $tag) {
		return $tag;
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UpdateTagRequest $request, Tag $tag) {
		$tag->update($request->all());
		return $tag;
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Tag $tag) {
		$tag->delete();
		return response()->noContent();
	}
}
