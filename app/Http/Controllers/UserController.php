<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Collection;

class UserController extends Controller
{    
    //sign-up users.
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'name' => 'required|min:3|unique:users,name',
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

    
    //log-in users.
    public function getDetails($name, $password){
        $user = User::where('name', $name)
                    ->orWhere('email', $name)
                    ->first('password');  // Use first() to get the first matching user.
    
        if ($user && Hash::check($password, $user->password)) {
            return response()->json([
                'status' => true,
                'message' => 'Password match',
                'data' => $user
            ]);
        
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Password does not match'
            ]);
        }
    }




    //forgotten password.
    public function update($id, Request $request){
        $user = User::find($id);

        if($user === null){
            return response()->json([
                'status' => false,
                'message' => 'User Not Found!'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'password' => 'required|min:8',
        ]);

        if($validator->fails()){
            return response()->json([
                'status'=> false,
                'message'=> 'Please fix the errors',
                'data'=> $validator->errors()
            ]);
        }
        
        $user->password = $request->password;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'password updated successfully',
            'data' => $user 
        ]);
    }
}
