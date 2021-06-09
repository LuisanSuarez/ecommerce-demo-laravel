<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateInfoRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([

            'name' => $request->input(key: 'name'),
            // 'name' => $request->name, #tip
            'email' => $request->input(key: 'email'),
            'password' => Hash::make($request->input(key: 'password')),
            'role_id' => $request->input(key: 'role_id') ? '1' : $request->input(key: 'role_id')
            ]);

        return response(new UserResource($user), status: Response::HTTP_CREATED);
    }

    public function login (Request $request) {
        if (!Auth::attempt($request->only('email', 'password'))){
            return \response([
                'error' => "Invalid credentials"
            ], Response::HTTP_UNAUTHORIZED);
        }

        /** @var User $user */
        $user = Auth::user();

        $jwt = $user->createToken('token')->plainTextToken;

        $cookie = cookie('jwt', $jwt, minutes: 60 * 24);

        return \response([
            'jwt' => $jwt
        ])->withCookie(($cookie));
    }

    public function user(Request $request)
    {
        $user = $request->user();
        return new UserResource($user->load('role'));
    }

    public function logout(Request $request)
    {
        $cookie = Cookie::forget('jwt');

        return \response([
            'message' => 'success, you logged out Holmes'
        ])->withCookie($cookie);

    }

    public function updateInfo(UpdateInfoRequest $request) {

        $user = $request->user();

        $user->update($request->only('name', 'email'));

        return \response(new UserResource($user), Response::HTTP_ACCEPTED);
    }

    public function updatePassword(UpdatePasswordRequest $request) {

        $user = $request->user();

        $user->update([
            'password' => Hash::make($request->input('password'))
        ]);

        return \response([new UserResource($user), "message" => "password changed succesfully"], Response::HTTP_ACCEPTED);
    }
}
