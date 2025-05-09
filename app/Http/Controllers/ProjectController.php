<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProjectController extends Controller {
	/**
	 * Display a listing of the resource.
	 */
	public function index() {
		$projects = Project::where('user_id', request()->user()->id)->with('tags')->get();

		return ProjectResource::collection($projects);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(StoreProjectRequest $request) {
		$project = $request->user()->projects()->create($request->all());

		return new ProjectResource($project);
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Project $project) {
		if (request()->user()->id != $project->user_id) {
			throw new NotFoundHttpException('Project not found');
		}
		return new ProjectResource($project);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UpdateProjectRequest $request, Project $project) {
		$project->update($request->all());

		return new ProjectResource($project);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Project $project) {
		if (request()->user()->id != $project->user_id) {
			throw new NotFoundHttpException('Project not found');
		}
		$project->delete();
		return response()->noContent();
	}
}
