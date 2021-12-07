<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\DataBaseConnection;

class Verification
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
        $create = new DataBaseConnection();
        $DB = $create -> connect();
        $table = 'users';
        $h=(int)$request->token;
        $find = $DB -> $table -> findOne(array(
            'email' => $request->mail,
            'token' => $h
        ));
        if($find!=NULL) {
            $data = ['table' => $table,'db' => $DB, 'email' => $request->mail];
            return $next($request->merge(['data' => $data]));
        } else {
            return response()->json(['Message' => 'Link not Exists'],404);
        }
    }
}
