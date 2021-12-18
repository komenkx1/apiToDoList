<?php

use Illuminate\Support\Facades\Route;
use App\Models\Task;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
       $expDate = Carbon::now();
        $dataTask = Task::whereDate('date_history', '<',$expDate)->get(); 
        foreach($dataTask as $task){

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
    "to" : "'.$task->user->api_token.'",
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
});
