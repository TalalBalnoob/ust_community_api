<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Major;
use App\Models\Role;
use App\Models\Staff;
use App\Models\Student;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function checkToken(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            abort(401, 'user is not loged in');
        }

        return Response()->json('user is loged in');
    }

    public function register_student(Request $request)
    {
        $user = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', Password::min(8)],
            'displayName' => ['required', 'string'],
            'major_id' => ['required', 'numeric'],
            'level' => ['required', 'numeric'],
            'branch' => ['required', 'string']
        ]);

        $isUserExist = User::query()->where('username', $user['username'])->first();
        if ($isUserExist) {
            abort(409, 'student already exists');
        }

        if (Major::query()->where('id', $user['major_id'])->get()->count() != 1) {
            abort(422, 'undefine major id');
        }

        $newUser = User::create([
            'password' => $user['password'],
            'username' => $user['username'],
            'user_type_id' => 1,
            'isAdmin' => false
        ]);


        $newStudent = new Student([
            'displayName' => $user['displayName'],
            'level' => $user['level'],
            'major_id' => $user['major_id'],
            'branch' => 'Hadhramaut'
        ]);

        $newUser->student()->save($newStudent);
        $user['profile'] = $newStudent;

        $token = $newUser->createToken($newUser['id']);

        return response(['user' => $newUser, 'token' => $token->plainTextToken]);
    }

    public function register_staff(Request $request)
    {
        $user = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', Password::min(8)],
            'displayName' => ['required', 'string'],
            'role_id' => ['required', 'numeric'],
            'branch' => ['required', 'string']
        ]);

        $isUserExist = User::query()->where('username', $user['username'])->first();
        if ($isUserExist) {
            abort(409, 'staff already exists');
        }

        if (Role::query()->where('id', $user['role_id'])->get()->count() != 1) {
            abort(422, 'undefine role id');
        }

        $newUser = User::create([
            'password' => $user['password'],
            'username' => $user['username'],
            'user_type_id' => 2,
            'isAdmin' => false
        ]);


        $newStaff = new Staff([
            'displayName' => $user['displayName'],
            'role_id' => $user['role_id'],
            'branch' => $user['branch']
        ]);

        $newUser->staff()->save($newStaff);
        $newUser['profile'] = $newStaff;

        $token = $newUser->createToken($newUser['id']);

        return response(['user' => $newUser, 'token' => $token->plainTextToken]);
    }

    // TODO: add try catch for catch login errors as {error_name: ex 'username', error_msg: msg}
    public function login(Request $request)
    {
        $user = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required']
        ]);

        $queuedUser = User::query()->where('username', $user['username'])->first();

        if (!$queuedUser) {
            abort(404, 'User not found');
        }

        if (!Hash::check($user['password'], $queuedUser->makeVisible('password')['password'])) {
            abort(400, 'invalid conditionals');
        };

        $token = $queuedUser->createToken($queuedUser['id']);

        return response(['user' => $queuedUser, 'token' => $token->plainTextToken]);
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
        } catch (Exception $e) {
            return abort(404, 'user is not loged in');
        }
        return response(['message' => 'user logout']);
    }
}
