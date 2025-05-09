<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest {
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
			"is_hidden" => ["boolean"],
			"content" => ["string"],
			"tags.*" => [Rule::exists('tags', 'id')->where('project_id', request()->project_id)],
		];
	}

	protected function prepareForValidation() {
		if (!isset($this->tags)) {
			return;
		}
		$tag_ids = collect($this->tags)->pluck('id')->toArray();

		$this->merge([
			"tags" => $tag_ids,
		]);
	}
}
