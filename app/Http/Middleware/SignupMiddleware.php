<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\DataBaseConnection;

class SignupMiddleware
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
        app('App\Http\Requests\SignUpValidation');
        $create = new DataBaseConnection();
        $DB = $create -> connect();
        $table = 'users';
        $find = $DB -> $table -> findOne(array(
            'email'=> $request -> email
        ));
        if($find == NULL) {
            $data = ['table' => $table,'db' => $DB];
            return $next($request->merge(['data' => $data]));
        } else {
            return response()->json(['Message' => 'Account Already Exists'],403);
        }
    }
}
