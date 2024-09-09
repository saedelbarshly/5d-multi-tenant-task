<?php

namespace App\Http\Controllers\Api;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Requests\TaskRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeTaskStatusRequest;
use App\Http\Resources\TaskResource;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TaskController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('permission:list task', only: ['index']),
            new Middleware('permission:create task', only: ['store']),
            new Middleware('permission:read task', only: ['show']),
            new Middleware('permission:update task', only: ['update']),
            new Middleware('permission:update task status', only: ['changeStatus']),
            new Middleware('permission:delete task', only: ['delete']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::paginate(10);
        return TaskResource::collection($tasks)->response()->getData(true);
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        try {
            $task = Task::create($request->all());
            return response()->json(['status' => true, 'message' => "Task Created Successfully ✅"]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'message' => "Someting went wrong!"]);
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        try {
            return new TaskResource($task);
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'message' => "Someting went wrong!"]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, Task $task)
    {
        try {
            $task->update($request->all());
            return response()->json(['status' => true, 'message' => "Task Updated Successfully ✅"]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'message' => "Someting went wrong!"]);
        }
    }

    public function changeStatus(ChangeTaskStatusRequest $request,Task $task)
    {
        try {
            if ($task->user_id !== auth()->id()) {
                return response()->json(['status' => false, 'message' => "This task do not belong to You."], 403);
            }
            $task->update(['status' => $request->status]);
            return response()->json(['status' => true, 'message' => "Task Status Changed Successfully ✅"]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'message' => "Someting went wrong!"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        try {
            $task->delete();
            return response()->json(['status' => true, 'message' => "Task Deleted Successfully ✅"]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'message' => "Someting went wrong!"]);
        }
    }
}
