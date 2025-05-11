<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller
{
    public function getUserList(Request $request)
    {
        $userList = User::query()->get()->all();

        foreach ($userList as $user) {
            $userProfail = User::addUserProfileInfo($user->id);
            $user['display_name'] = $userProfail['profile'];
        }

        return Response()->json($userList);
    }

    public function searchUsers(Request $request)
    {
        $searchText = $request['text'];

        $users = Student::query()
            ->where('displayName', 'LIKE', "%{$searchText}%")
            ->get();

        $users->merge(
            Staff::query()
                ->where('displayName', 'LIKE', "%{$searchText}%")
                ->get()
        );

        return Response()->json($users);
    }

    public function showUser(Request $request, string $user_id)
    {
        $user = User::query()->get()->where('id', $user_id)->first();

        if ($user) {
            abort('404', 'user not found');
        }

        return Response()->json($user);
    }

    // TODO: Review the function
    public function updateUser(Request $request, string $user_id)
    {
        $user = User::query()->find($user_id);
        if (!$user) {
            abort(404, 'User Not found');
        }


        $validateReq = $request->validate(
            [
                'username' => ['string', 'min:5', 'max:12', 'optional'],
                'password' => [Password::min(8), 'optional'],
                'isAdmin' => ['boolean', 'optional'],
                'user_type_id' => ['between:1,2', 'optional'],
            ]
        );

        if ($validateReq['username'] ?? false) {
            $user->username = $validateReq['username'];
        }
        if ($validateReq['password'] ?? false) {
            $user->password = $validateReq['password'];
        }
        if ($validateReq['isAdmin'] ?? false) {
            $user->isAdmin = $validateReq['isAdmin'];
        }
        // FIXME: need to make new profile and delete the old profile before change type of profile
        if ($validateReq['user_type_id'] ?? false) {
            $user->user_type_id = $validateReq['user_type_id'];
        }

        $user->save();

        return response()->json($user);
    }

    public function deleteUser(Request $request)
    {
        $targetUserId = $request['user_id'];

        $targetUser = User::query()->find($targetUserId);

        $targetUser ? $targetUser->delete() : abort(404, 'user not found');

        return response(['message' => 'user has been deleted']);
    }
}
