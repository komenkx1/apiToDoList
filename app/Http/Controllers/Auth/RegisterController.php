<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LoginToken;
use Dirape\Token\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
  public function register(Request $request)
  {
    $response = [];
    $validator = Validator::make($request->all(), [
      "name" => ["required", "string"],
      "username" => ["required", "string", "unique:users"],
      "password" => ["required", "confirmed"],
      "notif_token" => ["required", "string"],
    ],
    [
        'username.unique' => 'This Username Cannot Be Use. Please Try Another Username',
    ]);

    if ($validator->fails()) {
      $response["message"] =  $validator->errors()->all();
      $response["data"] = null;
      return response()->json($response);
    } else {
      $userData = $request->all();

      $newUser = User::create([
        "name" => $userData["name"],
        "username" => $userData["username"],
        "password" => bcrypt($userData["password"]),
        "api_token" => $userData["notif_token"],
      ]);
    $userLoggedToken = LoginToken::Create(
                    [
                        'user_id' => $newUser->id,
                        'token' => (new Token())->Unique('login_tokens', 'token', 60)
                    ]
                );
    
      $newUser["loggedToken"] = $userLoggedToken->token;
      $response["message"] = ["success"];
      $response["data"] = $newUser;
    }
    return response()->json($response);
  }
}
