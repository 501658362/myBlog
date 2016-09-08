<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class BaseController extends Controller {

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
