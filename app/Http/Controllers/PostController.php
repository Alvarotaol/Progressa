<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;

class PostController extends Controller {
	/**
	 * Display a listing of the resource.
	 */
	public function index() {
		if (request()->project_id) {
			return Post::where('project_id', request()->project_id)->orderBy('created_at', 'desc')->get();
		}
		//Retorna todos os posts de um projeto
		return Post::orderBy('created_at', 'desc')->get();
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(StorePostRequest $request) {
		$post = Post::create($request->all());

		$post->tags()->sync($request->tags);

		return $post;
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Post $post) {
		return $post;
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UpdatePostRequest $request, Post $post) {
		$post->update($request->all());

		return $post;
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Post $post) {
		$post->delete();

		return response()->noContent();
	}
}
