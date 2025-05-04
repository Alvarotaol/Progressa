<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleController extends Controller {
	public function redirect() {
		return Socialite::driver('google')->stateless()->redirect();
	}

	public function callback() {
		try {
			$userGoogle = Socialite::driver('google')->stateless()->user();
		} catch (\Exception $e) {
			return redirect("/")->with('error', 'Login failed, please try again.');
		}

		$user = User::firstOrCreate([
			'email' => $userGoogle->email,
		], [
			'name' => $userGoogle->name,
			'avatar' => $userGoogle->avatar,
		]);

		if ($user->avatar == null) {
			$user->avatar = $userGoogle->avatar ?? 'https://cdn-icons-png.flaticon.com/512/149/149071.png';
			$user->save();
		}

		//Criar token se necessário
		if ($user->tokens()->count() == 0) {
			$user->createToken($user->name . '-AuthToken')->plainTextToken;
		}

		return response()->view('auth_callback', [
			'token' => $user->tokens()->first()->plainTextToken,
			'user' => $user,
		]);
	}

	public function logout(Request $request) {
		$request->session()->invalidate();
		$request->session()->regenerateToken();

		return redirect('/');
	}
}
