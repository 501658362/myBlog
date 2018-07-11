<?php
namespace App\Http\Controllers;

use App\Facades\SendEmailService;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller {
    
    /**
     * Display a listing of the resource.
     *  联系我们
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //
        return view("contact-us");
    }
    
    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
        $ip = $this->getRealIp();
        $key = 'contact_ip_' . $ip;
        $codeKey = 'contact_ip_wrong_' . $ip;
        Cache::forget($key);
        Cache::forget($codeKey);
        dd(1);
    }
    
    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
      
        $data = $request->only('name', 'email', 'phone', 'message', 'code', 'submitType');
        $ip = $this->getRealIp();
        $key = 'contact_ip_' . $ip;
        $codeKey = 'contact_ip_wrong_' . $ip;
       
        if ($data[ 'submitType' ] == 1) {
            try {
                if (Cache::has($key)) {
                    return redirect()->back()->withInput($request->all())->withErrors(['email' => "获取验证码失败！一天只能发一次"]);
                }
                //range 是将1到42 列成一个数组
                $numbers = range(1, 10);
                //shuffle 将数组顺序随即打乱
                shuffle($numbers);
                //array_slice 取该数组中的某一段
                $result = array_slice($numbers, 0, 4);
                $result = join("", $result);
                Cache::put($key, $result, 60 * 24);
                $content = " 
                            亲：您好！ <br/>
                            
                            您正在发布留言，请在验证码输入框输入:" . $result . "，以完成操作。 <br/>
                            
                            此为系统邮件，请勿回复
                            ";
                $request->getClientIp();
                SendEmailService::send_mail($content, "陈彦瑾的博客验证码！", $data[ 'email' ]);
                return back()->withInput($request->all())->with(["Success" => "验证码已发送，请查收！"]);
            } catch (\Exception $e) {
                $message = "发送验证码邮件失败，错误代码%s,错误信息%s";
                return redirect()->back()->withInput($request->all())->withErrors(['email' => sprintf($message, $e->getCode(), $e->getMessage())]);
            }
        } else {
            try {
                if(empty($data['code'])){
                    return redirect()->back()->withInput($request->all())->withErrors(['email' =>  "验证码错误"]);
                }
                if (!Cache::has($key)) {
                    return redirect()->back()->withInput($request->all())->withErrors(['email' => "验证码已失效，请重新获取！"]);
                }
                $code = Cache::get($key);
    
                if (Cache::has($codeKey)) {
                    $vTimes = Cache::get($codeKey);
                    if($vTimes >= 3){
                        return redirect()->back()->withInput($request->all())->withErrors(['email' =>  "验证重试太多，请24小时后重试！谢谢！"]);
                    }
                }
                if($code != $data['code']){
                    if (!Cache::has($codeKey)) {
                        Cache::put($codeKey, 1, 60 * 24);
                    }else{
                        $vTimes = Cache::get($codeKey);
    
                        Cache::put($codeKey, 1 + $vTimes, 60 * 24);
                    }
                    return redirect()->back()->withInput($request->all())->withErrors(['email' =>  "验证码错误"]);
                }
             
                $content = 'Hi, 以下是您的邮件内容 <br>  ';
                unset($data['code']);
                unset($data['submitType']);
                foreach ($data as $k => $v) {
                    $content .= $k . ":" . $v . '<br>';
                }
                SendEmailService::send_mail($content, "有人给你留言啦！");
                return back()->with(["Success" => "信息已发送，感谢您的来信！"]);
            } catch (\Exception $e) {
                $message = "发送邮件失败，错误代码%s,错误信息%s";
                return redirect()->back()->withInput($request->all())->withErrors(['email' => sprintf($message, $e->getCode(), $e->getMessage())]);
            }
            //
        }
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
    
    //方法3：
    public function getRealIp() {
        $ip = false;
        if (!empty($_SERVER[ "HTTP_CLIENT_IP" ])) {
            $ip = $_SERVER[ "HTTP_CLIENT_IP" ];
        }
        if (!empty($_SERVER[ 'HTTP_X_FORWARDED_FOR' ])) {
            $ips = explode(", ", $_SERVER[ 'HTTP_X_FORWARDED_FOR' ]);
            if ($ip) {
                array_unshift($ips, $ip);
                $ip = false;
            }
            for ($i = 0; $i < count($ips); $i++) {
                if (!eregi("^(10│172.16│192.168).", $ips[ $i ])) {
                    $ip = $ips[ $i ];
                    break;
                }
            }
        }
        return ( $ip ? $ip : $_SERVER[ 'REMOTE_ADDR' ] );
    }
}
