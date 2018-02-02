<?php
/**
 * Created by PhpStorm.
 * User: chenyanjin
 * Date: 2018/1/5
 * Time: 19:07
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class WechatController extends Controller {
    protected $appId="wx8a1533aceba5ecf7";
    protected $secret="3a819b31ac0a145f6e6bcffba1c7289c";
   
    public function serve(Request $request) {
        Log::info('request arrived.'); # 注意：Log 为 Laravel 组件，所以它记的日志去 Laravel 日志看，而不是 EasyWeChat 日志
    
        $wechat = app('wechat');
        $wechat->server->setMessageHandler(function($message){
            return "来了您呐，稍安勿躁，片刻后悠比旅行将推出全套服务";
//            switch ($message->MsgType) {
//                case 'event':
//                    return '收到事件消息';
//                    break;
//                case 'text':
//                    if ($message->Content == "薛征" || $message->Content == "猫" || $message->Content == "老猫") {
//                        return "老猫是sha diao  哈哈哈哈哈";
//                    }
//                    return $message->Content;
//                    break;
//                case 'image':
//                    break;
//                case 'voice':
//                    return '收到语音消息';
//                    break;
//                case 'video':
//                    return '收到视频消息';
//                    break;
//                case 'location':
//                    return '收到坐标消息';
//                    break;
//                case 'link':
//                    return '收到链接消息';
//                    break;
//                // ... 其它消息
//                default:
//                    return '收到其它消息';
//                    break;
//            }
        });
    
        Log::info('return response.');
    
        return $wechat->server->serve();
    }
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        //
        Log::info("-------微信请求----");
        Log::info($request->all());
        $signature = $request->get("signature");
        $timestamp = $request->get("timestamp");
        $nonce = $request->get("nonce");
        $echostr = $request->get("echostr");
        $tmpArr = array ("kAptQRDXmDSciLbJeBh5k0iR6AmUiirk", $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        Log::info($tmpStr);
        Log::info($echostr);
        if ($signature == $tmpStr) {
            echo $echostr;
//            return response()->json($echostr);
        } else {
            // 回调成功 有code
            if($request->get("code")){
                $client = new \GuzzleHttp\Client();
                // 使用code 获取 token 和 openid
                $tokenData = $client->request('GET', 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->appId.'&secret='.$this->secret.'&code='.$request->get("code").'&grant_type=authorization_code');
                $tokenData = json_decode($tokenData->getBody()->getContents());
                Log::info(var_export($tokenData, true));
                // 使用 token 和 openid 获取用户信息
                $userData = $client->request('GET', "https://api.weixin.qq.com/sns/userinfo?access_token=$tokenData->access_token&openid=$tokenData->openid&lang=zh_CN");
                $userData = json_decode($userData->getBody()->getContents());
                Log::info(var_export($userData, true));
                dd($userData);
            }
            return Redirect::to('https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->appId.'&redirect_uri='.urlencode(url("wx")).'&response_type=code&scope=snsapi_userinfo&state=xczxcasdasd#wechat_redirect');
    
            return response("fail", 500);
        }
//        return view("contact-us");
    }
    
    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        //
        dd($request->cookie());
        $response = new Response();
        //第一个参数是cookie名，第二个参数是cookie值，第三个参数是有效期（分钟）
        $response->withCookie(cookie('website','LaravelAcademy.org',1));
        return $response;
        //如果想要cookie长期有效使用如下方法
        //$response->withCookie(cookie()->forever('name', 'value'));
//        Log::info("-------微信请求- login---");
//        Log::info($request->all());
//        if($request->get("code")){
//            dd($request->all());
//        }else{
//            Log::info('https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxff330b9c7987ddf4&redirect_uri='.urlencode(url("wx")).'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect');
//            return Redirect::to('https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxff330b9c7987ddf4&redirect_uri='.urlencode(url("wx")).'&response_type=code&scope=snsapi_userinfo&state=xczxcasdasd#wechat_redirect');
//        }
//
//
//        dd($request->all());
    }
    
    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
    }
    
    /**
     * Display the specified resource.
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }
    
    /**
     * Show the form for editing the specified resource.
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }
    
    /**
     * Update the specified resource in storage.
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }
    
    /**
     * Remove the specified resource from storage.
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }
}