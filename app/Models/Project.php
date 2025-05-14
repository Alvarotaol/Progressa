<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Project extends Model {
	use HasFactory;

	protected $fillable = ['name', 'description', 'is_public', 'is_active'];

	public function user() {
		return $this->belongsTo(User::class);
	}

	public function posts() {
		return $this->hasMany(Post::class);
	}

	public function tags() {
		return $this->hasMany(Tag::class);
	}

	public function generatePublicSlug() {
		if (!$this->is_public || $this->public_slug) {
			return;
		}
		$this->public_slug = str($this->name)->slug() . '-' . Str::random(6);
		$this->save();
	}
}
