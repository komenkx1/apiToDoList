<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginToken;
use App\Models\User;
use Dirape\Token\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
  public function login(Request $request)
  {
    $response = [];
    $validator = Validator::make($request->all(), [
      "username" => ["required", "string"],
      "password" => ["required"],
    ]);

    if ($validator->fails()) {
      $response["message"] =  $validator->errors()->all();
      $response["data"] = null;
      return response()->json($response);
    } else {
      $credentials = $request->only('username', 'password');

      if (Auth::attempt($credentials)) {
        $userData = User::where('username', $request->username)->first();

        $userLoggedToken = LoginToken::updateOrCreate(
          ['user_id' => $userData->id],
          ['token' => (new Token())->Unique('login_tokens', 'token', 60)]
        );

        $userData["loggedToken"] = $userLoggedToken->token;
        $response["message"] = [true];
        $response["data"] = $userData;
      } else {
        $response["message"] = ["password or username is false"];
        $response["data"] = null;
      }
    }
    return response()->json($response);
  }
}
