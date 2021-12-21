<?php

namespace App\Http\Controllers;

use App\Models\LoginToken;
use Illuminate\Http\Request;
use App\Models\Task;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $id = LoginToken::find($request->header("Token-Login"))->first();
        $data = Task::where("user_id", $id);
        return response()->json([
            'message' => true,
            'result' => $data
        ], 200);
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
