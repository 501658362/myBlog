<?php
namespace App\Services;

use Illuminate\Support\Facades\Cache;

class BaseServices {

    static $cacheMinutes = 10;// 分钟

    /**
     * 保存缓存
     * @param $tagName
     * @param $cacheName
     * @param $model
     * @param int $minutes
     */
    protected function setCache($tagName, $cacheName, $model, $minutes = 0) {
        $minutes = $minutes ? : self::$cacheMinutes;
        Cache::tags($tagName)->put($cacheName, $model, $minutes);
    }

    /**
     * 获取缓存
     * @param $tagName
     * @param $cacheName
     * @param $func
     * @param null $parameter
     * @return mixed
     */
    protected function getCache($tagName, $cacheName, $func, $parameter = null) {
        if (empty( $model = Cache::tags($tagName)->get($cacheName) )) {
            if (method_exists($this, $func)) {
                $model = call_user_func(array ($this, $func), $parameter);
                self::setCache($tagName, $cacheName, $model);
            }
        }
        return $model;
    }
}
