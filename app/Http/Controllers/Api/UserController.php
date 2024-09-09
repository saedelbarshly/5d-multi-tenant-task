<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:list user', only: ['index']),
            new Middleware('permission:create user', only: ['store']),
            new Middleware('permission:read user', only: ['show']),
            new Middleware('permission:update user', only: ['update']),
            new Middleware('permission:delete user', only: ['delete']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::paginate(10);
        return UserResource::collection($users)->response()->getData(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        try {
            $data = $request->except(['password','password_confirmation','role']);
            $data['password'] = Hash::make($request->password);
            $user = User::create($data);
            $user->assignRole($request->role);
            return response()->json([
                'status' => true,
                'message' => "User Update Successfully ✅",
                'user' => new UserResource($user),
            ]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'message' => "Someting went wrong!"]);
        }
      
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        try {
            return new UserResource($user);
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'message' => "Someting went wrong!"]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        try {
            $data = $request->except(['password','password_confirmation','role']);
            if ($request->has('password')){
                $data['password'] = Hash::make($request->password);
            }
            $user->update($data);
            if($request->has('role')){
                $user->syncRoles($user->role);
                $user->assignRole($request->role);
            }
            return response()->json([
                'status' => true,
                'message' => "User Updated Successfully ✅",
                'user' => new UserResource($user),
            ]);
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(['status' => false,'message' => "Someting went wrong!"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return response()->json(['status' => true, 'message' => "User Deleted Successfully ✅"]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'message' => "Someting went wrong!"]);
        }
    }
}
