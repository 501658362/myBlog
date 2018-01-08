<?php
/**
 * Created by PhpStorm.
 * User: chenyanjin
 * Date: 2018/1/5
 * Time: 19:07
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class WechatController extends Controller {
    
    /**
     * Display a listing of the resource.
     *  联系我们
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
            return Redirect::to('https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8a1533aceba5ecf7&redirect_uri='.urlencode(url("wx")).'&response_type=code&scope=snsapi_userinfo&state=xczxcasdasd#wechat_redirect');
    
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
        Log::info("-------微信请求- login---");
        Log::info($request->all());
        if($request->get("code")){
            dd($request->all());
        }else{
            Log::info('https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxff330b9c7987ddf4&redirect_uri='.urlencode(url("wx")).'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect');
            return Redirect::to('https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxff330b9c7987ddf4&redirect_uri='.urlencode(url("wx")).'&response_type=code&scope=snsapi_userinfo&state=xczxcasdasd#wechat_redirect');
        }
        
    
        dd($request->all());
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