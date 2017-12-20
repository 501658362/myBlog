<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class BaseController extends Controller {
    
    protected function view($layout, Array $data = []) {
        DB::setFetchMode(\PDO::FETCH_ASSOC);
        $results = DB::select('select id, tag, b.count from tags a join (select count(*) count, tag_id from post_tag_pivot group by tag_id order by tag_id desc) b on a.id = b.tag_id where a.deleted_at is null  order by b.count desc');
    
        $data['tagCount'] = $results;
        return view($layout, $data);
    }
    
    public function makeFile($fileContext = '新文件', $fileName = '新文件', $postfix = 'txt', $pathName = null) {
        try {
            $path = $this->makePath($pathName);
            $filename = $fileName . '.' . $postfix;//要生成名字
            $path = $path . DIRECTORY_SEPARATOR . $filename;
            $file = fopen($path, "w");//打开文件准备写入
            fwrite($file, $fileContext);//写入
            fclose($file);//关闭
            return $path;
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage(), $ex->getCode());
        }
    }

    public function makePath($pathName) {
        $bashPath = public_path();
        $path = explode(DIRECTORY_SEPARATOR, $pathName);
        foreach ($path as $p) {
            $bashPath = $bashPath . DIRECTORY_SEPARATOR . $p;
            if (!is_dir($bashPath)) {
                mkdir($bashPath);
            }
        }
        return $bashPath;
    }
    
    
}
