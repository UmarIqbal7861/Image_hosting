<?php

namespace App\Http\Controllers;


use App\Http\Requests\UploadImageValidation;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    function uploadImage(UploadImageValidation $request)
    {
        $create = new DataBaseConnection();
        
        
        $uid
        $data = $request -> file('photo');
        $array = (array)$data;
        $name = $array["\x00Symfony\Component\HttpFoundation\File\UploadedFile\x00originalName"];
        $imagesdata = explode('.',$name);

        $photo = $request -> file('photo') -> store('images');
        $path = $_SERVER['HTTP_HOST']."/user/storage/".$photo;

        $document = array(
            'uid' => $uid,
            'photo' => $path,
            'date' => date('Y-m-d'),
            'time' => date('H:i:s'),
            'name' => $imagesss[0],
            'extensions' => $imagesss[1],
            'accessor' => "hidden"
        );

        $create -> connect('images')->insertOne($document);

        $DB->$table->updateMany(array("email"=>$email), array('$set'=> $data));
            return response()->json(['Message' => 'Profile Update'],200);



        //date, time, name, extensions, private, public, hidden
        dd($path);
        dd($request->data['email']);
        dd("sd");
    }
}
