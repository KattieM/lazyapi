<?php

namespace App\Http\Controllers;

use App\Mail\SendPassword;
use App\Repositories\UserRepository;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function showDetails()
    {
        return response()->json(['users' => User::all()]);
    }

    public function showProfile($id)
    {
        $user = User::findOrFail($id);
        return response()->json(['user' => $user]);
    }

    public function saveUser(Request $request)
    {
//        $this->userRepository->validateUser($request);
        if ($request->has('id')) {
            $user = User::findOrFail($request['id']);
        } else {
            $user = new User();
        }

        $user->name = $request['firstName'];
        $user->surname = $request['lastName'];
        $user->username = $request['username'];
        $user->email = $request['email'];
        $user->sector = $request['user_sector'];
        $user->position = $request['user_position'];
        $user->photo_link = 'img/user_icon.png';
        $password = Str::random(8);
        $user->password = Hash::make($password);
        $user->join_date = Carbon::today();
        $user->status = 'active';
        $user->save();
        Mail::to($user->email)->send(new SendPassword($user->name, $user->username, $password));
        return response()->json((['user' => $user, 'msg' => 'Email was sent.']));

    }

    public function deleteUser(Request $request)
    {
        $user = User::findOrFail($request['id']);
        $user->delete();
        return User::count();
    }

    public function uploadProfileImage(Request $request)
    {
        $user = Auth::user();
        if ($request->hasFile('image')) {
            $this->validate($request, [
                'image' => 'required|mimes:png,jpg,jpeg|max:2048',
            ],
                ['image.mimes' => 'Not a valid image format',
                    'image.max' => 'Image is too big'
                ]);

            $extension = $request->file('image')->getClientOriginalExtension();
            $dir = 'img/' . $user->id . '/profile/';
            $filename = time() . '.' . $extension;
            $request->file('image')->move($dir, $filename);
            $user->photo_link = $dir . $filename;
            $user->save();
            $msg = "Saved";
            return response()->json(['msg' => $msg, 'photo' => $dir . $filename]);
        } else {
            $msg = "Not an image";
            return response()->json(['msg' => $msg]);
        }
    }
}
