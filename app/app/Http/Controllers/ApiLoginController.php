<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ApiLoginController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->json()->all();
        $validator = Validator::make($data, [
            'email' => ['required', 'email', 'string'],
            'password'=> ['required', 'string', 'min:3', 'max:64'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['error' => $validator->errors()], 401
            );
        }

        $user = User::where('email', $data['email'])->first();
        
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'error' => 'Auth failed.'
            ], 401);
        }

        return response()->json([
            'token' => $user->createToken('api')->plainTextToken
        ]);
    }
}