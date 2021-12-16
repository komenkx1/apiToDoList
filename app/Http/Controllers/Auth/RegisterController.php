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
            "name" => ["required","string"],
            "email" => ["required", "string","email", "unique:users"],
            "username" => ["required", "string", "unique:users"],
            "password" => ["required", "confirmed"],
      ]);
      
      if ($validator->fails()) {
        $response["message"] =  $validator->errors()->all();
        $response["data"] = null;
        return json_encode($response);
      }else{
        $userData = $request->all();

        $newUser = User::create([
            "name" => $userData["name"],
            "email" => $userData["email"],
            "username" => $userData["username"],
            "password" => bcrypt($userData["password"]),
        ]);
        $this->sendNotif($userData["notif_token"]);
        $response["message"] = ["success"];
        $response["data"] = $userData;
      }
      return json_encode($response);  
      
    }
    
    public function sendNotif($token){
                $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://fcm.googleapis.com/fcm/send',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
    "to" : "'.$token.'",
    "notification" :  {
        "title" : "Congratulation!",
        "body" : "Your Account Has Registered!",
        "content_available" : true,
        "priority" : "high"
    }

}',
  CURLOPT_HTTPHEADER => array(
    'Authorization: key=AAAAoiFYj0A:APA91bE0hclV8uY5igrD-n1wK4gXM0NlCdy8cHi45SX5k1mifHkz6aZnDTVeb_6wLbgtbSNdjlPzQR1XMsAhXLGxLz68W52XDtk1vxRYs-7-z88ho33IDZoaSUrsOJZbFdFlAwNPb7O_',
    'Content-Type: application/json'
  ),
));

curl_exec($curl);

curl_close($curl);
    }
}
