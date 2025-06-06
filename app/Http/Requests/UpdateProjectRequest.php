<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateProjectRequest extends FormRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool {
		return $this->user()->id == $this->project->user_id;
	}

	public function failedAuthorization() {
		throw new NotFoundHttpException('Project not found');
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array {
		return [
			"name" => ["required", "string", "max:63"],
			"description" => ["nullable", "string", "max:255"],
			"is_public" => ["boolean"],
			"is_active" => ["boolean"],
		];
	}
}
