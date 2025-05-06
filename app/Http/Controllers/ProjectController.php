<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;

class ProjectController extends Controller {
	/**
	 * Display a listing of the resource.
	 */
	public function index() {
		//dd(auth()->user());
		$projects = Project::where('user_id', request()->user()->id)->get();

		return $projects;
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(StoreProjectRequest $request) {
		$project = Project::create($request->all());

		return $project;
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Project $project) {
		return $project->load('tags');
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UpdateProjectRequest $request, Project $project) {
		$project->update($request->all());

		return $project;
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Project $project) {
		$project->delete();

		return response()->noContent();
	}
}
