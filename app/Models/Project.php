<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model {
	use HasFactory;

	protected $fillable = ['name', 'description', 'is_public', 'is_active', 'user_id'];

	public function user() {
		return $this->belongsTo(User::class);
	}

	public function posts() {
		return $this->hasMany(Post::class);
	}

	public function tags() {
		return $this->hasMany(Tag::class);
	}
}
