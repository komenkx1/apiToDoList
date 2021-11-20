<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            "name" => ["required","string"],
            "email" => ["required", "string","email", "unique:users"],
            "username" => ["required", "string", "unique:users"],
            "password" => ["required", "confirmed"],
        ]);
        $userData = $request->all();

        $newUser = User::create([
            "name" => $userData["name"],
            "email" => $userData["email"],
            "username" => $userData["username"],
            "password" => bcrypt($userData["password"]),
        ]);

        return json_encode($newUser);
    }
}
