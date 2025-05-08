<?php
// app/Models/User.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
	use HasApiTokens, Notifiable, HasFactory;

	protected $fillable = ['name', 'email', 'avatar'];

	public function projects() {
		return $this->hasMany(Project::class);
	}
}
