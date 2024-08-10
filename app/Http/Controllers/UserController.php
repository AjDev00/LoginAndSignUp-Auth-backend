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
        //validate all fields.
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

        //insert into DB.
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
        //get the password of the name or email inputted by the user.
        $user = User::where('name', $name)
                    ->orWhere('email', $name)
                    ->first('password');  // Use first() to get the first matching user.
    
        //check if user password is the same as hashed password.
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
    public function update($name, Request $request){
        $user = User::where('email', $name)
                    ->orWhere('name', $name)
                    ->find('password');

        //return error if the name inputted is not in the DB.
        if($user === null){
            return response()->json([
                'status' => false,
                'code' => 404,
                'message' => 'User Not Found!'
            ]);
        }

        //validate password field.
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:8',
        ]);

        if($validator->fails()){
            return response()->json([
                'status'=> false,
                'code'=> 400,
                'message'=> 'Please fix the errors',
                'errors'=> $validator->errors()
            ]);
        }     

        //update password in the DB.
        $user->password = Hash::make( $request->password );
        $user->update();

        return response()->json([
            'status' => true,
            'message' => 'password updated successfully',
            'data' => $user 
        ]);
    }
}
