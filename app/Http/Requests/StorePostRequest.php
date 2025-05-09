<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePostRequest extends FormRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool {
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array {
		return [
			"content" => ["required", "string", "max:255"],
			"project_id" => ["required", Rule::exists('projects', 'id')->where('user_id', request()->user()->id)],
			"tags.*" => [Rule::exists('tags', 'id')->where('project_id', $this->project_id)],
		];
	}

	protected function prepareForValidation() {
		$tag_ids = collect($this->tags)->pluck('id')->toArray();

		$this->merge([
			"tags" => $tag_ids,
		]);
	}
}
