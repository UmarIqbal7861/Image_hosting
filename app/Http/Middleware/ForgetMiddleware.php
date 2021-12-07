<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\DataBaseConnection;

class ForgetMiddleware
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
        app('App\Http\Requests\ForgetValidation');
        $create = new DataBaseConnection();
        $DB = $create -> connect();
        $table = 'users';
        $find = $DB -> $table -> findOne(array(
            'email'=> $request -> email
        ));
        if($find!=NULL) {
            $verfy =$find['email_verified_at'];

            if($verfy!=NULL) {
                $data = ['table' => $table,'db' => $DB];
                return $next($request->merge(['data' => $data]));
            } else {
                return response()->json(['Message' => 'Invalid credential'],404);
            }
        } else {
            return response()->json(['Message' => 'Invalid credential'],404);
        }
    }
}
