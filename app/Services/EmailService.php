<?php
namespace App\Services;

use App\Jobs\SendEmailJob;
use Illuminate\Support\Facades\Config;


class EmailService
{    
    function sendMail($send_mail,$token)
    {
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){ $url = "https://";}

        else{$url = "http://";}

        $url.= $_SERVER['HTTP_HOST'];
        $details=[
            'title' => 'SignUp Verification',
            'body' => 'This Link use for login'.$url.'/user/Verification/'.$send_mail.'/'.$token
        ];   
        dispatch(new SendEmailJob($send_mail,$details));
        return "Mail Send";
    }
    
    function sendMailForgetPassword($mail,$otp)
    {
        $details=[
            'title' => 'Forget Password Verification',
            'body' => 'Your OTP is '. $otp . ' Please copy and paste the change Password Api'
        ]; 
        dispatch(new SendEmailJob($mail,$details));
        return "Mail send.";
    }
}