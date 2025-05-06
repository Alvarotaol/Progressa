<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListPostsRequest;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;

class PostController extends Controller {
	/**
	 * Display a listing of the resource.
	 */
	public function index(ListPostsRequest $request) {
		$posts = Post::where('project_id', $request->project_id)->orderBy('created_at', 'desc')->paginate();

		return PostResource::collection($posts);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(StorePostRequest $request) {
		$post = Post::create($request->all());

		$post->tags()->sync($request->tags);

		return new PostResource($post);
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Post $post) {
		return new PostResource($post);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UpdatePostRequest $request, Post $post) {
		$post->update($request->all());

		return new PostResource($post);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Post $post) {
		$post->delete();

		return response()->noContent();
	}
}
