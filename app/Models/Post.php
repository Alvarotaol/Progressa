<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model {
	use HasFactory;

	protected $fillable = [
		"content",
		"is_hidden",
	];

	public function project() {
		return $this->belongsTo(Project::class);
	}

	public function tags() {
		return $this->belongsToMany(Tag::class);
	}
}
