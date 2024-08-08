<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'name' => 'required|min:3',
            'password'=> 'required|min:8',
        ]);

        if ($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Please fix errors',
                'errors' => $validator->errors()
            ]);
        }

        $user = new User();
        $user->email = $request->email;
        $user->name = $request->name;
        $user->password = Hash::make( $request->password ); //password hashing.
        $user->save();
        
        return response()->json([
            'status' => true,
            'message' => 'SignUp successfull!',
            'data' => $user
        ]);
    }
}
