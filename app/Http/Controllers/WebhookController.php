<?php
/**
 * Created by PhpStorm.
 * User: chenyanjin
 * Date: 2017/12/3
 * Time: 00:14
 */
namespace App\Http\Controllers;

use App\Facades\SendEmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller {
    
    public function gitWebhook(Request $request) {
        try {
            $requestData = $request->header("X-Hub-Signature");
            Log::info("git push 啦  该更新代码啦~");
            $data = [];
            $v = "验证失败";
            $signature = $requestData;
            if ($signature) {
                $hash = "sha1=" . hash_hmac('sha1', $request->getContent(), "test123456");
                if (strcmp($signature, $hash) == 0) {
                    $v = "验证成功";
                    $data= "";
                    $data1= "";
                    exec('cd /home/wwwroot/chenyanjin.top/myBlog/ && /home/wwwroot/chenyanjin.top/myBlog/updateGit.sh 2>&1', $data, $data1);
                    Log::info($data);
                    Log::info($data1);
                }
            }
            $text = "执行结果<br>github 请求: $v <br> header X-Hub-Signature:" . $requestData . "<br>" . implode("<br>", $data) . "<br>";
            SendEmailService::send_mail($text);
            return response()->json([$data, $v]);
        } catch (\Exception $ex) {
            \Log::info("--------------------:" . date("H:i:s", time()) . ' ' . $ex);
            SendEmailService::send_mail('Hi, 以下是您的邮件内容 <br>  ' . $ex->getTraceAsString(), "git webhook 接发，回调发生异常， 发送异常！！！！");
            return response("fail", 500)->json();
        }
    }
}