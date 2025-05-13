<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

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
		$token = $user->createToken($user->name . '-AuthToken')->accessToken;


		return response()->view('auth_callback', [
			'token' => $token,
			'user' => $user,
		]);
	}

	public function logout(Request $request) {
		//$request->session()->invalidate();
		//$request->session()->regenerateToken();

		return response()->noContent();
	}

	public function fakeRedirect() {
		return view('fake_google', [
			'users' => User::all(),
		]);
	}

	public function fakeCallback() {
		if (request('user_id') == "new") {
			$user = User::create([
				'name' => request('name'),
				'email' => request('email'),
				'avatar' => "/favicon.svg",
			]);
		} else {
			$user = User::find(request('user_id'));
			$user->avatar = "/favicon.svg";
			$user->save();
		}

		$token = $user->createToken($user->name . '-AuthToken')->accessToken;
		return response()->view('auth_callback', [
			'token' => $token,
			'user' => $user,
		]);
	}

	public function login() {
		$user = User::find(request('user_id'));
		$token = $user->createToken($user->name . '-AuthToken')->accessToken;
		return response()->json([
			'token' => $token,
			'user' => $user,
		]);
	}
}
