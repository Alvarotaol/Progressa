<?php

// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable {
	use HasApiTokens;
	use Notifiable;
	use HasFactory;

	protected $fillable = ['name', 'email', 'avatar'];

	public function projects() {
		return $this->hasMany(Project::class);
	}
}
