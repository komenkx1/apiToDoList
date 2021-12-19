<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function index()
    {
        $data = Task::all();
        $response["message"] = true;
        $response["result"] = [$data];
        return json_encode($response);
    }

    public function create(Request $request)
    {
        $task = new Task;
        $task->title = $request->title;
        $task->content = $request->content;
        $task->date = $request->date;
        $task->user_id = $request->user_id;
        $task->save();

        $response["message"] = "success";

        return json_encode($response);
    }

    public function update(Request $request, $id)
    {
        $title = $request->title;
        $content = $request->content;
        $date_end = $request->date_end;

        $task = Task::find($id);
        $task->title = $title;
        $task->content = $content;
        $task->date_end = $date_end;

        $task->save();

        $response["message"] = "success";

        return json_encode($response);
    }

    public function delete($id)
    {
        $task = Task::find($id);
        $task->delete();

        $response["message"] = "success";

        return json_encode($response);
    }

    public function last_seen($id)
    {
        $task = Task::find($id);
        $task->date_history = Carbon::now()->toDateTimeString();

        $task->save();

        $response["message"] = "success";

        return json_encode($response);
    }
}
