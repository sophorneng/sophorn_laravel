<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{


    
    // create user
    public function signup(Request $request){

        $request->validate([
            'password' => 'required|confirmed',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);

        $user->save();

        // create token  one user have one token
        $token = $user->createToken('mytoken')->plainTextToken;

        return response()->json([
            'user' =>$user,
            'token' => $token,
        ]);
    
        
    }
    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        return response()->json(['message' => 'User logged out']);

    }

    public function login(Request $request){
        
        // check email
        $user = User::where('email', $request->email)->first();

        //check password
        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json(['message' => 'Bad login'], 401);
        }
        // create token  one user have one token
        $token = $user->createToken('mytoken')->plainTextToken;

        return response()->json([
            'user' =>$user,
            'token' => $token,
        ]);



    }
}
