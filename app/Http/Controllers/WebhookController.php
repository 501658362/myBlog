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
        $requestData = $request->header("X-Hub-Signature");
        Log::info("git push 啦  该更新代码啦~");
        $data = [];
        exec('cd /home/wwwroot/chenyanjin.tk/myBlog/ && /home/wwwroot/chenyanjin.tk/myBlog/updateGit.sh 2>&1', $data, $data1);
        Log::info($data);
        Log::info($data1);
        $text = "执行结果<br>" . implode("<br>", $data) ."<br>github 请求<>".$requestData;
        SendEmailService::send_mail($text);
        return response()->json($data);
    }
}