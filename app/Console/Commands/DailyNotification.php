<?php

namespace App\Console\Commands;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DailyNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:dailyNotification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reach user tash where not open more than 1 day';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $expDate = Carbon::now();
        $dataTask = Task::whereDate('updated_at', '<', Carbon::today())
                    ->where('completed',0)->get();
        foreach ($dataTask as $task) {
            $this->sendNotif($task->user->api_token, $task->user->username, $task->title, $task->id, $task->date, $task->content);
        }
    }

    public function sendNotif($token, $username, $title, $id, $date, $content)
    {
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
            CURLOPT_POSTFIELDS => '{
    "to" : "' . $token . '",
    "notification" :  {
        "click_action" : ".activity.MainActivity", 
        "title" : "DoIt",
        "body" :  "hey ' . $username . ', 
your task with title ' . $title . ' has not opened for 1 day.",
        "content_available" : true,
        "priority" : "high"
    },
    "data":{
        "id" : "'.$id.'",
        "title" : "'.$title.'",
        "date" : "'.$date.'",
        "content" : "'.$content.'",
         
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
