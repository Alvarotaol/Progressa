<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model {
	use HasFactory;

	protected $fillable = ['color', 'label'];

	public function posts() {
		return $this->belongsToMany(Post::class);
	}

	public function project() {
		return $this->belongsTo(Project::class);
	}
}
