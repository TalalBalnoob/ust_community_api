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
        return Response()->json(User::query()->get()->all());
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
                'username' => ['string', 'min:5', 'max:12'],
                'password' => [Password::min(8)],
                'isAdmin' => ['boolean'],
                'user_type_id' => ['between:1,2']
            ]
        );

        if ($validateReq['username']) {
            $user->username = $validateReq['username'];
        }
        if ($validateReq['password']) {
            $user->password = $validateReq['password'];
        }
        if ($validateReq['isAdmin']) {
            $user->isAdmin = $validateReq['isAdmin'];
        }
        // FIXME: need to make new profile and delete the old profile before change type of profile
        if ($validateReq['user_type_id']) {
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
