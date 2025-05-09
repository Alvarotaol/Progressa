<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateTagRequest extends FormRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool {
		return $this->user()->id == $this->tag->project->user_id;
	}

	public function failedAuthorization() {
		throw new NotFoundHttpException('Tag not found');
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array {
		$tag = $this->tag;
		return [
			"label" => ["required", "string", "max:31", Rule::unique('tags', 'label')->where('project_id', $tag->project_id)->ignore($tag->id)],
			"color" => ["required", "string", "max:7", "regex:/^#([A-Fa-f0-9]{6})$/"],
		];
	}
}
