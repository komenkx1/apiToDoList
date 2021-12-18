<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
  public function register(Request $request)
  {
    $response = [];
    $validator = Validator::make($request->all(), [
      "name" => ["required", "string"],
      "email" => ["required", "string", "email", "unique:users"],
      "username" => ["required", "string", "unique:users"],
      "password" => ["required", "confirmed"],
      "notif_token" => ["required", "string"],
    ]);

    if ($validator->fails()) {
      $response["message"] =  $validator->errors()->all();
      $response["data"] = null;
      return json_encode($response);
    } else {
      $userData = $request->all();

      $newUser = User::create([
        "name" => $userData["name"],
        "email" => $userData["email"],
        "username" => $userData["username"],
        "password" => bcrypt($userData["password"]),
        "api_token" => $userData["notif_token"],
      ]);

      $response["message"] = ["success"];
      $response["data"] = $newUser;
    }
    return json_encode($response);
  }
}
