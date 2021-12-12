<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\DataBaseConnection;

class AuthenticateMiddleware
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
        $jwt = $request->bearerToken();
        $create = new DataBaseConnection();
        $DB = $create -> connect();
        $table = 'users';
        $find = $DB -> $table -> findOne(array(
            'remember_token'=> $jwt
        ));
        if($find!=NULL) {
            $find['table'] = $table;
            $find['db'] = $DB;

            return $next($request->merge(['data' => $find]));
        } else {
            return response()->json(['Message' => 'Invalid credential'],404);
        }
    }
}
