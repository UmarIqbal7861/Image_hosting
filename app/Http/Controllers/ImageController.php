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
<<<<<<< HEAD
    /***
     * Base-64 Conversion Function
    */
=======
    
>>>>>>> d4589defa5e26910a5235dd17e7ffe15f4e104b6
    public $data;
    function base_64_conversion($file)
    {
        $base64_string =  $file;  
        
        $extension = explode('/', explode(':', substr($base64_string, 0, strpos($base64_string, ';')))[1])[1];

        $replace = substr($base64_string, 0, strpos($base64_string, ',')+1);

        $image = str_replace($replace, '', $base64_string);

        $image = str_replace(' ', '+', $image);

        $fileName = time().'.'.$extension;

        $url= $_SERVER['HTTP_HOST'];

        $pathurl=$url."/user/storage/app/images/".$fileName;

        $path=storage_path('app\\images').'\\'.$fileName;

        file_put_contents($path,base64_decode($image));

        $data = ['ext'=> $extension, 'path' => $pathurl];

        return $data;
    }

<<<<<<< HEAD
    /***
     * User Uploads Image / Picture / Photo Function
    */    
=======
>>>>>>> d4589defa5e26910a5235dd17e7ffe15f4e104b6
    function uploadImage(UploadImageValidation $request)
    {
        try {
            $DB = $request -> data['db'];

            $uid = $request->data['_id'];

            $name = $request -> name;
            
            $dpath = $this -> base_64_conversion($request -> photo);
            
            $document = array(
                'uid' => $uid,
                'photo' => $dpath['path'],
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'name' => $name,
                'extensions' => $dpath['ext'],
                'accessor' => "hidden"
            );
            $table='images';
            $DB->$table->insertOne($document);
            return response()->json(['Message' => 'Photo Uploaded'],200);
        } catch (\Exception $error) {
            return response()->json(['Message' => $error -> getMessage()], 500);
        }
    }

    /***
     * User Removes Image Function
    */
    function removePhoto(PhotoValidation $request)
    {
        try {
            $DB = $request -> data['db'];
            $uid = $request -> data['_id'];
            $pid=new \MongoDB\BSON\ObjectId($request -> photo);
            $table='images';
            $DB->$table->deleteOne(array('_id'=> $pid,'uid'=>$uid));
            return response()->json(['Message' => 'Photo Delete'],200);
        } catch (\Exception $error) {
            return response()->json(['Message' => $error -> getMessage()], 500);
        }
    }

    /***
     * User Shows All Images
    */
    function listAllPhoto(Request $request)
    {
        try {
            $DB = $request -> data['db'];
            $uid = $request -> data['_id'];
            $table = 'images';
            $images = $DB -> $table -> find(array('uid'=>$uid));

            return response([$images-> toArray()],200);
        } catch (\Exception $error) {
            return response()->json(['Message' => $error -> getMessage()], 500);
        }
    }

    /***
     * User Search Image Function
    */
    function searchPhoto(Request $request)
    {
        try {
            $DB = $request -> data['db'];
            $table='images';
            $uid=$request -> data['_id'];

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

    /***
     * Image Link Generate Function
    */
    function createPhotoLink(CreateLinkValidation $request)
    {
        try {
            $dpath = $this -> base_64_conversion($request -> photo);
            $path = $dpath['path'];
            return response([$path],200);
        } catch (\Exception $error) {
            return response()->json(['Message' => $error -> getMessage()], 500);
        }
    }

    /***
     * Allow Private Image Access Function
    */
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

    /***
     * Make Image Public Function
    */
    function  makePublic(PhotoValidation $request)
    {
        try {
            $DB = $request -> data['db'];
            $uid = $request -> data['_id'];
            $table='images';
            $pid=new \MongoDB\BSON\ObjectId($request -> photo);
            $DB -> $table -> updateOne(['uid' => $uid, '_id' => $pid],['$set'=>['accessor' => 'public']]);
            $DB -> $table -> updateOne(['_id' => $pid], ['$unset' => ['emails' => '']]);
        } catch (\Exception $error) {
            return response()->json(['Message' => $error -> getMessage()], 500);
        }
    }

<<<<<<< HEAD
    /***
     * Make Image Private Function
    */
=======
 
>>>>>>> d4589defa5e26910a5235dd17e7ffe15f4e104b6
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

    /***
     * Make Image Hindden Function
    */
    function makeHidden(PhotoValidation $request)
    {
        try {
            $DB = $request -> data['db'];
            $uid = $request -> data['_id'];
            $table ='images';
            $pid = new \MongoDB\BSON\ObjectId($request -> photo);
            $DB -> $table -> updateOne(['uid' => $uid, '_id' => $pid],['$set'=>['accessor' => 'hidden']]);
            $DB -> $table -> updateOne(['_id' => $pid], ['$unset' => ['emails' => '']]);
        } catch (\Exception $error) {
            return response()->json(['Message' => $error -> getMessage()], 500);
        }
    }

    /***
     * Image Access Function
    */
    function assessPhoto(EmailValidation $request)
    {
        try {
            $DB = $request -> data['db'];
            $uid = $request -> data['uid'];
            $table ='images';
            $email = $request -> email;
            $pid=new \MongoDB\BSON\ObjectId($request -> photo);
            $result = $DB->$table->findOne(['_id' => $pid]);
            if($result['accessor'] == 'private') {

                $DB->$table->updateOne(['_id' => $pid, 'uid' => $uid],['$push' => ['emails' => ['mail' => $email] ]]);
                return response()->json(['Message' => 'Assess Update'],200);
            } else if ($result['accessor'] == 'public') {
                return response()->json(['Message' => 'Photo Already Public'],500);
            } else {
                return response()->json(['Message' => 'Please Change Photo Assessor'],500);
            }
        } catch (\Exception $error) {
            return response()->json(['Message' => $error -> getMessage()], 500);
        }
    }

<<<<<<< HEAD
    /***
     * Remove Access For Private Image Function
    */    
=======

>>>>>>> d4589defa5e26910a5235dd17e7ffe15f4e104b6
    function removeMailAccess(EmailValidation $request)
    {
        try {
            $DB = $request -> data['db'];
            $uid = $request -> data['_id'];
            $table ='images';
            $email = $request -> email;
            $pid=new \MongoDB\BSON\ObjectId($request -> photo);
            $DB->$table->updateOne(['_id' => $pid, 'uid' => $uid],['$pull' => ['emails' => ['mail' => $email] ]]);
            return response()->json(['Message' => 'Assess Update'],200);
        } catch (\Exception $error) {
            return response()->json(['Message' => $error -> getMessage()], 500);
        }
    }
    
    /***
     * Access Image Link Function
    */
    function accessPhotoLink(Request $request)
    {
        try {
            $create = new DataBaseConnection();
            $DB = $create -> connect();
            $table = 'images';

            $result = $DB->$table->findOne(['photo' => $request -> photolink]);
            if($result != NULL) {

                if($result['accessor'] == 'private') {

                    $this -> checkPrivate($DB, $table, $request -> photolink, $request->data['email']);
                    return response()->json($this -> data,200);
                } else if($result['accessor'] == 'public') {

                    return response()->json($result,200);
                } else {
                    return response()->json(['Message' => 'Link Not Exists'],200);
                }
            } else {
                return response()->json(['Message' => 'Link Not Exists'],200);
            }
        } catch (\Exception $error) {
            return response()->json(['Message' => $error -> getMessage()], 500);
        }
    }

    /***
     * Check Image Access Type Function
    */
    function checkPrivate($DB, $table, $photolink, $email) 
    {
        try {
            $result = $DB -> $table -> findOne( ['photo' => $photolink,'emails.mail'=> $email]);
            if($result != NULL) {
                $this -> data = $result;
            } else {
                return response()->json(['Message' => 'Link Not Exists'],200);
            }
        } catch (\Exception $error) {
            return response()->json(['Message' => $error -> getMessage()], 500);
        }
    }
}
