<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\DataBaseConnection;

class OtpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        app('App\Http\Requests\OtpValidation');
        $create = new DataBaseConnection();
        $DB = $create -> connect();
        $table = 'users';
        $find = $DB -> $table -> findOne(array(
            'email'=> $request -> email,
            'token'=>(int)$request->otp
        ));
        if($find!=NULL) {
            return $next($request);
        } else {
            return response()->json(['Message' => 'Enter Valid OTP'],401);
        }
        
    }
}
