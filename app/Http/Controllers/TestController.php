<?php
namespace App\Http\Controllers;

use App\Http\Model\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;

class TestController extends Controller {

    public function __construct() {
//        $this->middleware('test') ;
    }

    public function index() {
        return view("layouts.Mis.master");
    }

    public function getPath() {
        $path = __DIR__ . '/../../Resources/lang/zh-CN';
        $old = $path . DIRECTORY_SEPARATOR . 'area_code.php';
        $dir = $path . DIRECTORY_SEPARATOR . 'Changed';
        $new = $dir . DIRECTORY_SEPARATOR . 'area_code_' . date("Ymd_H_i_s");
    }

    /**
     * 修改文件
     */
    public function indexChangeArray() {
        $data = [
            "中国"  => 1,
            "阿联酋" => 2,
            "韩国"  => 3,
            "日本"  => 4,
            "泰国"  => 5,
        ];
        $envPath = base_path() . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . 'zh-CN' . DIRECTORY_SEPARATOR . 'area_code.php';
        //        dd(\File::get($envPath));\
        $data = collect($data);
        $data->transform(function ($item, $key) {
            return '"' . $key . '"=>' . $item;
        });
        //        dd($data);
        $data
            = '<?php
return [
    "area_code" => [' . join(",\n\t\t", $data->toArray()) . '    ]
];';
        dd(\File::put($envPath, $data));
    }

    /**
     *修改ENV
     */
    public function indexChangeENV() {
        $data = [
            'APP_ENV'        => 'your_environment111',
            'APP_KEY'        => 'your_key',
            'APP_DEBUG'      => 'trueOrFalse',
            'DB_DATABASE'    => 'test',
            'DB_USERNAME'    => 'test',
            'DB_PASSWORD'    => 'test',
            'DB_HOST'        => 'localhost',
            'CACHE_DRIVER'   => 'file',
            'SESSION_DRIVER' => 'file',
        ];
        $envPath = base_path() . DIRECTORY_SEPARATOR . '.env.example1';
        //        dd(base_path(),$envPath,DIRECTORY_SEPARATOR);
        $contentArray = collect(file($envPath, FILE_IGNORE_NEW_LINES));
        $contentArray->transform(function ($item) use ($data) {
            foreach ($data as $key => $value) {
                if (str_contains($item, $key)) {
                    return $key . '=' . $value;
                }
            }
            return $item;
        });
        $content = implode($contentArray->toArray(), "\n");
        //        dd($contentArray,$content);
        dd(\File::put($envPath, $content));
    }

    public function indexEmail() {
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
        $data[ 'messageLines' ] = explode("\n", $request->get('message'));
        $is = Mail::queue('test.contact', $data, function ($message) use ($data) {
            $message->subject('Blog Contact Form: ' . $data[ 'name' ])->to('peng501658@163.com')->replyTo($data[ 'email' ]);
        });
        if ($is == 1) {
            return back()->with(["Success" => "Thank you for your message. It has been sent."]);
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
