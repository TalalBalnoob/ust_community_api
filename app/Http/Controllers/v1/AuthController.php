<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use function PHPUnit\Framework\returnSelf;

class AuthController extends Controller {
	public function register(Request $request) {
	}

	public function login(Request $request) {
		$user = $request->validate([
			'username' => ['required', 'string'],
			'password' => ['required']
		]);

		$queuedUser = User::query()->where('username', $user['username'])->first();

		if (!$queuedUser) {
			abort(404, 'User not found');
		}

		if (!Hash::check($user['password'], $queuedUser->makeVisible('password')['password'])) {
			abort(400, 'not pass');
		};

		$token = $queuedUser->createToken($queuedUser['id']);

		return response()->json(['user' => $queuedUser, 'token' => $token->plainTextToken], 200);
	}

	public function logout(Request $request) {
	}
}
