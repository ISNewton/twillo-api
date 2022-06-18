<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
class SocialController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {

        try {
            $user = Socialite::driver('google')->user();
            $user = User::where('google_id', $user->id)->first();

            if ($user) {
                Auth::login($user);
                return response([
                    'messsage' => 'Logged in with google'
                ],201);
            } else {
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id' => $user->id,
                    'password' => encrypt('123456dummy')
                ]);
                Auth::login($newUser);
                return response([
                    'messsage' => 'Logged in with google'
                ],201);
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
