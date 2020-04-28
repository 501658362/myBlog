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
        //        $v = random_int(1, 10);
        //        $x = random_int(1, 10);
        //        $ip = $this->getRealIp();
        //        $key = 'contact_ip_' . $ip;
        //        Cache::put($key, $v+$x, 60 * 24);
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
        $key = $request->get("g-recaptcha-response");
        if (empty($key)) {
            return redirect()->back()->withInput($request->all())->withErrors(['email' => "验证码错误"]);
        }
        $postData = array (
            'secret'   => '6LcTzb4UAAAAADmR3Ftws-b6PpWGXXhzukVoHJjZ',
            'response' => $key   //前端传过来的响应码
        );
        $postData = http_build_query($postData);
        $options = array (
            'http' => array (
                'method'  => 'POST',
                'header'  => 'Content-type:application/x-www-form-urlencoded',
                'content' => $postData,
                'timeout' => 15 * 60 // 超时时间（单位:s）
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents('https://www.recaptcha.net/recaptcha/api/siteverify', false, $context);
        $json = json_decode($result, true);
        //在这里处理返回的值
        if (!$json[ 'success' ]) {
            return redirect()->back()->withInput($request->all())->withErrors(['email' => "验证码错误"]);
        }
        $data = $request->only(['name', 'email', 'phone', 'message', 'code', 'submitType']);
        $ip = $this->getRealIp();
        try {
            //
            $content = 'Hi, 以下是您的邮件内容 <br>  ';
            unset($data[ 'code' ]);
            unset($data[ 'submitType' ]);
            foreach ($data as $k => $v) {
                $content .= $k . ":" . $v . '<br>';
            }
            $content .= "ip:" . $ip . '<br>';
            SendEmailService::send_mail($content, "有人给你留言啦！");
            return back()->with(["Success" => "信息已发送，感谢您的来信！"]);
        } catch (\Exception $e) {
            $message = "发送邮件失败，错误代码%s,错误信息%s";
            return redirect()->back()->withInput($request->all())->withErrors(['email' => sprintf($message, $e->getCode(), $e->getMessage())]);
        }
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
                if (!preg_match("/^(10│172.16│192.168).$/", $ips[ $i ])) {
                    $ip = $ips[ $i ];
                    break;
                }
            }
        }
        return ( $ip ? $ip : $_SERVER[ 'REMOTE_ADDR' ] );
    }
}
