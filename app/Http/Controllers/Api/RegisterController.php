<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(RegisterRequest $request)
    {

        DB::beginTransaction();
        try {
            $tenant = Tenant::create([
                'subdomain' => $request->subdomain,
                'url' => str_replace('://', '://' . $request->subdomain . '.', config('app.url')),
            ]);
    
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'tenant_id' => $tenant->id,
            ]);
    
            $user->assignRole('Admin');
            DB::commit();
            return response()->json([
                'message' => 'User registered successfully,login form this url',
                'subdomain-url' => $tenant->url . '/api',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => 'Something went wrong!'], 400);
        }
    }
}
