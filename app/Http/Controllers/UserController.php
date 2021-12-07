<?php

namespace App\Http\Controllers;

use App\Services\DataBaseConnection;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\SignUpValidation;
use App\Http\Requests\LoginValidation;
use App\Http\Requests\ForgetValidation;
use App\Http\Requests\NewPasswordValidation;
use App\Http\Requests\profileValidation;
use Illuminate\Http\Request;
use App\Services\jwtService;
use App\Services\EmailService;

class UserController extends Controller
{
    function signUp(Request $request)
    {
        $DB = $request -> data['db'];
        $table = $request -> data['table'];

        $profile_picture = $request -> file('profile') -> store('images');
        $path = $_SERVER['HTTP_HOST']."/user/storage/".$profile_picture;

        $document = array(
            'name' => $request -> input('name'),
            'email' => $mail = $request -> input('email'),
            'age' => $request -> input('age'),
            'profile' => $path,
            'password' => Hash::make($request -> input('password')),
            'status' => 0,
            'token' => $token = rand(100,1000),
            'email_verified_at' => 0,
        );
        try {
            $insert = $DB -> $table -> insertOne($document);
            $serviceObject = new EmailService();
            $serviceObject -> sendMail($mail, $token);
            return response()->json(['Message' => 'Registration Successful'],200);

        } catch (\Exception $error) {
            return response()->json(['Message' => $error -> getMessage()], 500);
        }
    }

    
    function verification(Request $request)
    {
        $DB = $request -> data['db'];
        $table = $request -> data['table'];
        $mail = $request -> data['email'];
        try {
            $DB -> $table -> updateMany(array("email"=>$mail), 
            array('$set'=>array('email_verified_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'))));

            return response(['Message' => 'Your Account has been Verified.'],200);

        } catch (\Exception $error) {
            return response()->json(['Message' => $error -> getMessage()], 500);
        }
    }
    
    
    function login(Request $request)
    {
        $DB = $request -> data['db'];
        $table = $request -> data['table'];
        $password = $request -> data['password'];
        $status = $request -> data['status'];
        $user_password = $request -> password;
        $user_email = $request -> email;

        try {
            if(Hash::check($user_password,$password)) {

                $jwt_conn = new jwtService();
                $jwt = $jwt_conn->get_jwt();

                if($status == 1) {
                    $DB->$table->updateMany(array("email"=>$user_email), 
                        array('$set'=>array('remember_token'=> $jwt)));
                    return response(['Message'=>'You are already logged in..!','Access_Token'=>$jwt],200);                    
                } else {
                    $update=$DB->$table->updateMany(array("email"=>$user_email), 
                        array('$set'=>array('remember_token'=> $jwt,'status' => 1)));
                    return response(['Message'=>'log In','Access_Token'=>$jwt],200); 
                }
            }
            else{
                return response()->json(['Message'=>'Email and Password Is InCorrect'],401);                
            }
        } catch (\Exception $error) {
            return response()->json(['Message' => $error -> getMessage()], 500);
        }
    }

    function forgetPassword(Request $request)
    {
        try {

            $DB = $request -> data['db'];
            $table = $request -> data['table'];
            $otp=rand(1000,9999);
            $DB->$table->updateMany(array('email'=> $request->email), 
            array('$set'=>array('token'=> $otp)));
            $serviceObject = new EmailService();
            $serviceObject -> sendMailForgetPassword($request->email, $otp);
            return response()->json(['Message' => 'OTP send in your Email'],200);

        } catch (\Exception $error) {
            return response()->json(['Message' => $error -> getMessage()], 500);
        }
    }


    function otpmatch(Request $request)
    {
        return response()->json(['Message' => 'OTP Match'],200);
    }

    function changePassword(NewPasswordValidation $request)
    {
        try {
            $create = new DataBaseConnection();
            $DB = $create -> connect();
            $table = 'users';
            $DB->$table->updateMany(array('email'=> $request->email), 
            array('$set'=>array('password'=> Hash::make($request->new_password))));
            return response()->json(['Message' => 'Password Update'],200);

        } catch (\Exception $error) {
            return response()->json(['Message' => $error -> getMessage()], 500);
        }
    }

    
    function profileUpdate(profileValidation $request)
    {
        try{
            $DB = $request -> data['db'];
            $table = $request -> data['table'];
            $email = $request -> data['email'];
            dd($table);
            dd($request -> data['_id']);
            
            $data=[];
            if($request->name != NULL) { $data['name'] = $request -> name; }
            if($request->email != NULL) { $data['email'] = $request -> email; }
            if($request->password != NULL) { $data['password'] = Hash::make($request -> password); }
            if($request->age != NULL) { $data['age'] = $request -> age; }
            if($request->profile != NULL) { 

                $profile_picture = $request -> file('profile') -> store('images');
                $path = $_SERVER['HTTP_HOST']."/user/storage/".$profile_picture;
                $data['profile'] = $path; 
            }

            $DB->$table->updateMany(array("email"=>$email), array('$set'=> $data));
            return response()->json(['Message' => 'Profile Update'],200);

        } catch (\Exception $error) {
            return response()->json(['Message' => $error -> getMessage()], 500);
        }
    }
}