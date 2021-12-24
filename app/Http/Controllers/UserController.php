<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\LoginToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
  public function update(Request $request)
  {
    $userData = $request->all();
    $loginData =  LoginToken::with('user')->where('token', $request->token)->first();
    if(isset($loginData)){
    $user = $loginData->user;
    $response = [];
    $validator = Validator::make($request->all(), [
      "name" => ["required", "string"],
      "username" => ["required", "string", 'unique:users,username,' . $user->id],
      "old_password" => ["required_with:new_password"],
      "new_password" => ["required_with:old_password"],
      "avatar_url" => [ "image|mimes:jpeg,png,jpg|max:2048"]
    ], [
        'username.unique' => 'This Username Cannot Be Use. Please Try Another Username',
    ]);

    if ($validator->fails()) {
      $response["message"] =  $validator->errors()->all();
      $response["data"] = null;
      return json_encode($response);
    } else {

        if ($request->file('avatarUrl')) {
          $filname = time() . $request->file('avatarUrl')->getClientOriginalName();
           Storage::delete($user->avatar_url); // menghapus gambar atau file
          $path = $request->file('avatarUrl')->storeAs('upload/avatar', $filname);
        }

      if (isset($userData["old_password"])) {
        if (Hash::check($userData["old_password"], $user->password)) {
          $user->update([
            "name" => $userData["name"],
            "username" => $userData["username"],
            "avatar_url" => $path ?? $user->avatar_url,
            "password" => bcrypt($userData["new_password"])
          ]);

          $response["message"] = ["success update with password"];
          $user["loggedToken"] = $request->token;
          $response["data"] = $user;
        } else {
          $response["message"] = ["Your Old Password Is Wrong!"];
          $response["data"] = null;
        }
      } else {

        $user->update([
          "name" => $userData["name"],
          "username" => $userData["username"],
          "avatar_url" => $path ?? $user->avatar_url
        ]);
        $user["loggedToken"] = $request->token;
        $response["message"] = ["success update"];
        $response["data"] = $user;
      }
    }
}else{
      $response["message"] = ["You Are Not Logged In"];
        $response["data"] = null;
}
    return json_encode($response);
  }

  public function updateNotifToken(Request $request)
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
      $loginData =  LoginToken::with('user')->where('token', $request->token)->first();
      $user = $loginData->user;

      $user->update([
        "api_token" => $userData["notif_token"],
      ]);

      $response["message"] = ["success"];
      $response["data"] = $user;
    }
    return json_encode($response);
  }
}
