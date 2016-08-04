<?php
namespace App\Http\Controllers;

use App\Http\Model\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;

class TestController extends Controller {

    public function index() {
        $is = Mail::send('test.email', ['testVar' => '123'], function ($message) {
            $message->to('maziqiang@ubtour.com', '马自强')->subject('Hello wo shi chenchaopeng');
        });
        dd($is);
        return view("test.email");
    }


    public function indexMysql() {
        $con = mysqli_connect('127.0.0.1', 'root', '', 'try');
        if (!$con) {
            dd(mysqli_error($con));
        }
        mysqli_query($con, "set character set 'utf8'");//读库
        mysqli_query($con, "set names 'utf8'");//写库
        $re = mysqli_query($con, "select * from ucenter_partner");
        while ($r = mysqli_fetch_assoc($re)) {
            echo json_encode($r, JSON_UNESCAPED_UNICODE) . '<br>';
        }
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function indexRedis() {
        //        Redis::lPush('namex', 'Taylo1r');
        //        Redis::lPush('namex', 'Taylo1r2');
        //        Redis::lPush('namex', 'Taylo1r23');
        //        Redis::lPush('namex', 'Taylo1r24');
        //        Redis::lPush('namex', 'Taylo1r25');
        $arList = Redis::lrange("namex", 0, 5);
        dd(Redis::keys("*"));
        $user = new User();
        $user = $user->where('user_name', '唐僧')->get()->toArray();
        dd($user);
        Redis::set('name1', 'Taylo1r');
        //        $values = Redis::lrange('names', 5, 10);
        dd(Redis::get('name1'));
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
        return view("contact");
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $data = $request->only('name', 'email', 'phone');
        $data['messageLines'] = explode("\n", $request->get('message'));

        $is= Mail::queue('test.contact', $data, function ($message) use ($data) {
            $message->subject('Blog Contact Form: '.$data['name'])
                ->to('peng501658@163.com')
                ->replyTo($data['email']);
        });
        if($is==1){


        return back()
            ->with(["Success"=>"Thank you for your message. It has been sent."]);
        }
        dd($is);
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
