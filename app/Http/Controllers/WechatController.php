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
        $tmpArr = array ("501658362", $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        Log::info($tmpStr);
        Log::info($echostr);
        if ($signature == $tmpStr) {
            return response()->json($echostr);
        } else {
            return response("fail", 500);
        }
        return view("contact-us");
    }
    
    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
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