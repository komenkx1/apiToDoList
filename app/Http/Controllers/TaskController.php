<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\LoginToken;
use Illuminate\Http\Request;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\PseudoTypes\True_;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $token_login = $request->header("Token-Login");
        $id = DB::select("SELECT user_id FROM `login_tokens` WHERE token=" . "'" . $token_login . "'");
        if (is_null($id)) {
            return response()->json([
                'message' => false
            ], 403);
        } else {
            $data = Task::where("user_id", $id);
            // return response()->json([
            //     'message' => true,
            //     'result' => $data,
            //     'id' => $id,
            //     'token' => $token_login
            // ], 200);
            // $response["result"] = $data["data"];
            // $response["message"] = True;
            // return json_encode($response);
            return Task::where("user_id", $id);
        }
    }

    public function create(Request $request)
    {
        $id = LoginToken::find($request->header("Token-Login"))->first();
        $task = new Task;
        $task->title = $request->title;
        $task->content = $request->content;
        $task->date = $request->date;
        $task->complated = 0;
        $task->user_id = $id;
        $task->updated_at = Carbon::now()->toDateTimeString();
        $task->save();

        return response()->json([
            'message' => true,
            'result' => "OK"
        ], 200);
    }

    public function update(Request $request)
    {
        $title = $request->title;
        $content = $request->content;
        $date_end = $request->date_end;

        $task = Task::find($request->id);
        $task->title = $title;
        $task->content = $content;
        $task->date_end = $date_end;

        $task->save();

        return response()->json([
            'message' => true,
            'result' => "OK"
        ], 200);
    }

    public function delete(Request $request)
    {
        $task = Task::find($request->id);
        $task->delete();

        return response()->json([
            'message' => true,
            'result' => "OK"
        ], 200);
    }

    public function last_seen(Request $request)
    {
        $task = Task::find($request->id);
        $task->updated_at = Carbon::now()->toDateTimeString();
        $task->save();

        return response()->json([
            'message' => true,
            'result' => "OK"
        ], 200);
    }

    public function complete_task(Request $request)
    {
        $task = Task::find($request->id);
        $task->completed = 1;
        $task->save();

        return response()->json([
            'message' => true,
            'result' => "OK"
        ], 200);
    }

    public function uncomplete_task(Request $request)
    {
        $task = Task::find($request->id);
        $task->completed = 0;
        $task->save();

        return response()->json([
            'message' => true,
            'result' => "OK"
        ], 200);
    }
}
