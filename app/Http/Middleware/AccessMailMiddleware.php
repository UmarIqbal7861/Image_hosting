<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\DataBaseConnection;

class AccessMailMiddleware
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
        $uid = $request -> data['_id'];
        $find = $DB -> $table -> findOne(array(
            'email'=> $request -> email,
        ));
        if(!empty($find['email_verified_at'])) {

            if($find!=NULL) {
                $table = 'images';
                $pid=new \MongoDB\BSON\ObjectId($request -> photo);
                $result = $DB -> $table -> findOne( ['_id' => $pid,'emails.mail'=> $request -> email]);
                if($result == NULL) {
                    $result['uid']=$uid;
                    $result['db'] = $DB;
                    return $next($request->merge(['data' => $result]));
                } else {
                    return response()->json(['Message' => 'Already add this user'],409);
                }
            } else{
                return response()->json(['Message' => 'You are not add this mail'],409);
            }
        } else {
            return response()->json(['Message' => 'You are not add this mail'],409);
        }
    }
}
