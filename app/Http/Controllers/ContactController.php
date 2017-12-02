<?php
namespace App\Http\Controllers;

use App\Facades\SendEmailService;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
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
    }
    
    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
        $data = $request->only('name', 'email', 'phone', 'message');
//        $data[ 'messageLines' ] = explode("\n", $request->get('message'));
        //        try{
        //            $is = Mail::queue('test.contact', $data, function ($message) use ($data) {
        //                $message->subject('Blog Contact Form: ' . $data[ 'name' ])->to('peng501658@163.com')->replyTo($data[ 'email' ]);
        //            });
        //            if ($is == 1) {
        //                return back()->with(["Success" => "信息已发送，感谢您的来信！"]);
        //            }
        //        }catch (\Exception $e){
        //            $message = "发送邮件失败，错误代码%s,错误信息%s";
        //            return redirect()->back()->withInput($request->all())->withErrors(['email' => sprintf($message,$e->getCode(),$e->getMessage())]);
        //        }
        try {
            $content = "";
            foreach ($data as $k => $v){
                $content.= $k .":".$v .'<br>';
            }
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
}
