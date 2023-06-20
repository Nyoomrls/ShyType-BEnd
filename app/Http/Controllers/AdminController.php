<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;

use function GuzzleHttp\Promise\all;

class AdminController extends Controller
{
    use SoftDeletes;
    public function register(Request $request)
    {
        $fields = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'email' => ['required', 'string', 'unique:admins'],
            'password' => ['required', 'string'],
        ]);

        if ($fields->fails()) {
            return [
                'error' => 'Bad credentials',
                'status' => 401
            ];
        }

        $admin = Admin::create([
            "name" => $request->name,
            "email" => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $newAdmin = $admin->save();

        if ($newAdmin) {
            return [
                "message" => "Successfully registered",
                "status" => 201
            ];
        }
        return [
            'error' => 'Bad credentials',
            'status' => 401
        ];
    }

    public function login(Request $request)
    {
        $fields = Validator::make($request->all(), [
            'email' => ['required', 'string'],
            'password' => ['required', 'string']
        ]);

        if ($fields->fails()) {
            return [
                'error' => 'Bad credentials',
                'status' => 401
            ];
        }

        $user = Admin::where('email',  $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return [
                'message' => "Bad credentials",
                'status' => 401
            ];
        }

        return [
            "message" => "Successfully logged in",
            "status" => 200
        ];
    }

    public function admin_ban_user(Request $request)
    {
        $fields = Validator::make($request->all(), [
            'userId' => ['required']
        ]);

        if ($fields->fails()) {
            return [
                'error' => 'Invalid data',
                'status' => 401
            ];
        }

        $user = User::find($request->userId);
        $user->delete();
    }

    public function admin_unban_user(Request $request)
    {
        $fields = Validator::make($request->all(), [
            'userId' => ['required']
        ]);

        if ($fields->fails()) {
            return [
                'error' => 'Invalid data',
                'status' => 401
            ];
        }

        $user = User::withTrashed()->find($request->userId)->restore();
    }
    public function admin_get_users(Request $request)
    {
        $users = User::get();
        return $users;
    }

    public function admin_get_ban_users(Request $request)
    {
        $users = User::onlyTrashed()->get();
        return $users;
    }
}
