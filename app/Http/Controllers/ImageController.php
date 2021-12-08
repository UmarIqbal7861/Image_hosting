<?php

namespace App\Http\Controllers;


use App\Http\Requests\UploadImageValidation;
use App\Http\Requests\PhotoValidation;
use App\Http\Requests\CreateLinkValidation;
use App\Http\Requests\EmailValidation;
use Illuminate\Http\Request;
use App\Services\DataBaseConnection;

class ImageController extends Controller
{
    function uploadImage(UploadImageValidation $request)
    {
        try {
            //$create = new DataBaseConnection();
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
            //$create -> connect();
            $DB->$table->insertOne($document);
            return response()->json(['Message' => 'Photo Uploaded'],200);
        } catch (\Exception $error) {
            return response()->json(['Message' => $error -> getMessage()], 500);
        }
    }


    function removePhoto(PhotoValidation $request)
    {
        try {
            //$create = new DataBaseConnection();
            $DB = $request -> data['db'];
            $uid = $request -> data['_id'];
            $pid=new \MongoDB\BSON\ObjectId($request -> photo);
            $table='images';
            //$create -> connect();
            $DB->$table->deleteOne(array('_id'=> $pid,'uid'=>$uid));
            return response()->json(['Message' => 'Photo Delete'],200);
        } catch (\Exception $error) {
            return response()->json(['Message' => $error -> getMessage()], 500);
        }
    }


    function listAllPhoto(Request $request)
    {
        try {
            //$create = new DataBaseConnection();
            $DB = $request -> data['db'];
            $uid = $request -> data['_id'];
            $table = 'images';
            //$create -> connect();
            $images = $DB -> $table -> find(array('uid'=>$uid));

            return response([$images-> toArray()],200);
        } catch (\Exception $error) {
            return response()->json(['Message' => $error -> getMessage()], 500);
        }
    }


    function searchPhoto(Request $request)
    {
        try {
            //$create = new DataBaseConnection();
            $DB = $request -> data['db'];
            $table='images';
            $uid=$request -> data['_id'];
            //$create -> connect();
    
            $data=[];
                $data['uid'] = $uid;
                if($request->date != NULL) { $data['date'] = $request -> date; }
                if($request->time != NULL) { $data['time'] = $request -> time; }
                if($request->name != NULL) { $data['name'] = $request -> name; }
                if($request->extensions != NULL) { $data['extensions'] = $request -> extensions; }
                if($request->accessor != NULL) { $data['accessor'] = $request -> accessor; }
                
            $result=$DB->$table->find($data);
            return response([$result->toArray()]);
        } catch (\Exception $error) {
            return response()->json(['Message' => $error -> getMessage()], 500);
        }
    }


    function createPhotoLink(CreateLinkValidation $request)
    {
        try {
            $photo = $request -> file('photo') -> store('images');
            $path = $_SERVER['HTTP_HOST']."/user/storage/".$photo;
            return response([$path],200);
        } catch (\Exception $error) {
            return response()->json(['Message' => $error -> getMessage()], 500);
        }
    }


    function makeAccessor(PhotoValidation $request)
    {
        try {
            $access=$request->accessor;
            if($access=='public') {
                $this->makePublic($request);
                return response()->json(['Message' => 'Update Success'],200);
            }
            if($access=='private') {
                $this->makePrivate($request);
                return response()->json(['Message' => 'Update Success'],200);
            }
            if($access=='hidden') {
                $this->makeHidden($request);
                return response()->json(['Message' => 'Update Success'],200);
            }
        } catch (\Exception $error) {
            return response()->json(['Message' => $error -> getMessage()], 500);

        }
    }


    function  makePublic(PhotoValidation $request)
    {
        try {
            $DB = $request -> data['db'];
            $uid = $request -> data['_id'];
            $table='images';
            $pid=new \MongoDB\BSON\ObjectId($request -> photo);
            $DB -> $table -> updateOne(['uid' => $uid, '_id' => $pid],['$set'=>['accessor' => 'public']]);
        } catch (\Exception $error) {
            return response()->json(['Message' => $error -> getMessage()], 500);
        }
    }


    function makePrivate(PhotoValidation $request)
    {
        try {
            $DB = $request -> data['db'];
            $uid = $request -> data['_id'];
            $table='images';
            $pid=new \MongoDB\BSON\ObjectId($request -> photo);
            $DB -> $table -> updateOne(['uid' => $uid, '_id' => $pid],['$set'=>['accessor' => 'private']]);
        } catch (\Exception $error) {
            return response()->json(['Message' => $error -> getMessage()], 500);
        }
    }


    function makeHidden(PhotoValidation $request)
    {
        try {
            $DB = $request -> data['db'];
            $uid = $request -> data['_id'];
            $table ='images';
            $pid = new \MongoDB\BSON\ObjectId($request -> photo);
            $DB -> $table -> updateOne(['uid' => $uid, '_id' => $pid],['$set'=>['accessor' => 'hidden']]);
        } catch (\Exception $error) {
            return response()->json(['Message' => $error -> getMessage()], 500);
        }
    }


    function assessPhoto(EmailValidation $request)
    {
        try {
            $DB = $request -> data['db'];
            $uid = $request -> data['_id'];
            $table ='images';
            $emails=explode(',',$email);
            $pid=new \MongoDB\BSON\ObjectId($request -> photo);
            if($request -> data['_id'] == 'private')
            {

            }
            
            $DB -> $table -> updateOne(['uid' => $uid, '_id' => $pid],['$set'=>['accessor' => 'hidden']]);
        } catch (\Exception $error) {
            return response()->json(['Message' => $error -> getMessage()], 500);
        }
    }
    
}
