<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\ListingResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request) {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('app-token')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ],201);
    }

    public function login(LoginRequest $request) {
        $user = User::where('email',$request->email)->first();

        if(!$user || !Hash::check($request->password,$user->password)) {
            return response([
                'message' => 'Theses credentials do not match our records'
            ],422);
        }

        $token = $user->createToken('app-token')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ],200);
    }

    public function logout() {
        auth()->user()->tokens()->delete();
        
        return response([
            'message' => 'logged out'
        ],200);
    }

    public function profile() {
        $user = User::with('listings.images')->find(auth()->id());
        return response([
            'user' => $user,
            'listings' => ListingResource::collection($user->listings)
        ],200);
    }

    public function updateProfile(Request $request) {
        $request->validate([
            'name' => 'required|string|max:50'
        ]);
        $user = User::find(auth()->id());
        $user->update([
            'name' => $request->name
        ]);
        return response([
            'user' => $user
        ],201);
        
    }
}
