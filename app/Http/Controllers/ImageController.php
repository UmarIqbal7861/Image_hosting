<?php

namespace App\Http\Controllers;


use App\Http\Requests\UploadImageValidation;
use Illuminate\Http\Request;
use App\Services\DataBaseConnection;

class ImageController extends Controller
{
    function uploadImage(UploadImageValidation $request)
    {
        try {
            $create = new DataBaseConnection();
            $DB = $request -> data['db'];

            $uid = $request->data['_id'];
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
                'name' => $imagesdata[0],
                'extensions' => $imagesdata[1],
                'accessor' => "hidden"
            );
            $table='images';
            $create -> connect();
            $DB->$table->insertOne($document);
            return response()->json(['Message' => 'Profile Update'],200);
        } catch (\Exception $error) {
            return response()->json(['Message' => $error -> getMessage()], 500);
        }
    }
}
