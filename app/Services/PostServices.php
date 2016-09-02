<?php
namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;

class PostServices {
    const REDIS_POST_CACHE_TAG = 'redis_post_cache_tag';
    const REDIS_POST_CACHE     = 'redis_post_cache_';
    static $cacheMinutes = 1440;//24小时

    public static function getPosts() {
        $limit = Input::get('limit', config('blog.posts_per_page'));
        $page = Input::get('page', 1);
        $cacheName = self::REDIS_POST_CACHE . $limit . '_' . $page;
        if (empty( $posts = Cache::tags(self::REDIS_POST_CACHE_TAG)->get(self::REDIS_POST_CACHE . $cacheName) )) {
            $posts = self::with('tags')->select([
                'slug',
                'title',
                'subtitle',
                'published_at'
            ])->where('published_at', '<=', Carbon::now())->where('is_draft', 0)->orderBy('published_at', 'desc')->paginate($limit);
            Cache::tags(self::REDIS_POST_CACHE_TAG)->put(self::REDIS_POST_CACHE . $cacheName, $posts, self::$cacheMinutes);
        }
        return $posts;
    }

}