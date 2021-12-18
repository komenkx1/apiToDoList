<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function update(Request $request, User $user)
    {
      $response = [];
      $validator = Validator::make($request->all(), [
        "name" => ["required", "string"],
        "email" => ["required", "string", "email", "unique:users"],
        "username" => ["required", "string", "unique:users"],
      ]);
  
      if ($validator->fails()) {
        $response["message"] =  $validator->errors()->all();
        $response["data"] = null;
        return json_encode($response);
      } else {
        $userData = $request->all();
  
        $user->update([
          "name" => $userData["name"],
          "email" => $userData["email"],
          "username" => $userData["username"],
        ]);
        $this->sendNotif($userData["notif_token"]);
        $response["message"] = ["success"];
        $response["data"] = $user;
      }
      return json_encode($response);
    }
  
    public function updateNotifToken(Request $request, User $user)
    {
      $response = [];
      $validator = Validator::make($request->all(), [
        "notif_token" => ["required", "string"],
      ]);
  
      if ($validator->fails()) {
        $response["message"] =  $validator->errors()->all();
        $response["data"] = null;
        return json_encode($response);
      } else {
        $userData = $request->all();
  
        $user->update([
          "api_token" => $userData["notif_token"],
        ]);
  
        $response["message"] = ["success"];
        $response["data"] = $user;
      }
      return json_encode($response);
    }
}
