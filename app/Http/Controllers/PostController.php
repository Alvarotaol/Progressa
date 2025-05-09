<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListPostsRequest;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostController extends Controller {
	/**
	 * Display a listing of the resource.
	 */
	public function index(ListPostsRequest $request) {
		if ($request->user()->projects()->where('id', $request->project_id)->doesntExist()) {
			return response()->json(['message' => 'Unauthorized'], 403);
		}
		$posts = Post::where('project_id', $request->project_id)
			->with('tags', function ($query) {
				$query->orderBy('tags.id');
			})
			->orderBy('created_at', 'desc')->paginate();

		return PostResource::collection($posts);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(StorePostRequest $request) {
		$project = Project::find($request->project_id);
		$post = $project->posts()->create($request->all());

		$post->tags()->sync($request->tags);

		return new PostResource($post);
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Post $post) {
		if (request()->user()->id != $post->project->user_id) {
			throw new NotFoundHttpException('Post not found');
		}
		return new PostResource($post);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UpdatePostRequest $request, Post $post) {
		if ($request->user()->id != $post->project->user_id) {
			throw new NotFoundHttpException('Post not found');
		}
		$post->update($request->all());
		if (isset($request->tags))
			$post->tags()->sync($request->tags);

		return new PostResource($post);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Post $post) {
		if (request()->user()->id != $post->project->user_id) {
			throw new NotFoundHttpException('Post not found');
		}
		$post->delete();

		return response()->noContent();
	}
}
