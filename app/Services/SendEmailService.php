<?php
/**
 * Created by PhpStorm.
 * User: chenyanjin
 * Date: 2017/12/3
 * Time: 00:10
 */
namespace App\Services;

use Illuminate\Support\Facades\Mail;

class SendEmailService {
    
    function send_mail($data = "", $subject = '亲，有新的 webhook 触发哦! 请查看执行结果 ！', $to = 'peng501658@gmail.com') {
        $is = Mail::raw($data, function ($message) use ($to,$subject) {
            $message->to($to)->subject($subject);
        });
        return;
//
//        $url = 'http://api.sendcloud.net/apiv2/mail/send';
//        $API_USER = 'chenyanjin.top';
//        $API_KEY = 'ibbHiX2PBvb0XF7E';
//        $param = array (
//            'apiUser'     => $API_USER, # 使用api_user和api_key进行验证
//            'apiKey'      => $API_KEY,
//            'from'        => 'admin@chenyanjin.top', # 发信人，用正确邮件地址替代
//            'fromName'    => '陈彦瑾的博客',
//            'to'          => $to,# 收件人地址, 用正确邮件地址替代, 多个地址用';'分隔
//            'subject'     => $subject,
//            'html'        => $data,
//            'respEmailId' => 'true'
//        );
//        $data = http_build_query($param);
//
//        $options = array (
//            'http' => array (
//                'method'  => 'POST',
//                'header'  => 'Content-Type: application/x-www-form-urlencoded',
//                'content' => $data
//            )
//        );
//        $context = stream_context_create($options);
//        $result = file_get_contents($url, FILE_TEXT, $context);
//        return $result;
    }
    
}