<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListTagsRequest extends FormRequest {
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
			"project_id" => ["required", Rule::exists('projects', 'id')->where('user_id', request()->user()->id)],
		];
	}

	public function messages() {
		return [
			"project_id.exists" => "Esse projeto não existe",
			"project_id.required" => "É necessário informar um projeto",
		];
	}
}
