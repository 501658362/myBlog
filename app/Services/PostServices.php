<?php
namespace App\Services;

use App\Http\Model\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;

class PostServices extends BaseServices {

    const REDIS_POST_CACHE_TAG = 'redis_post_cache_tag';
    const REDIS_POST_CACHE     = 'redis_post_cache_';

    /**
     * 根据slug获取单个文章
     * @param $slug
     * @return mixed
     */
    public function getPost($slug) {
        $cacheName = self::REDIS_POST_CACHE . '_slug_' . $slug;
        return self::getCache(self::REDIS_POST_CACHE_TAG, $cacheName, "getPostModel",$slug);

    }

    protected function getPostModel($slug){
        $post = Post::with('tags')->whereSlug($slug)->firstOrFail();
        $post->views = $post->views + 1;
        $post->save();
        return $post;
    }

    /**
     * 获取文章list
     * @return mixed
     */
    public function getPosts() {
        $limit = Input::get('limit', config('blog.posts_per_page'));
        $page = Input::get('page', 1);
        $cacheName = self::REDIS_POST_CACHE . $limit . '_' . $page;
        return self::getCache(self::REDIS_POST_CACHE_TAG, $cacheName, "getPostsModel");
    }

    /**
     * 获取文章的Model
     * @return mixed
     */
    protected function getPostsModel() {
        $limit = Input::get('limit', config('blog.posts_per_page'));
        $posts = Post::with('tags')->select([
            'slug',
            'title',
            'subtitle',
            'published_at',
            'views'
        ])->where('published_at', '<=', Carbon::now())->where('is_draft', 0)->orderBy('published_at', 'desc')->paginate($limit);
        $posts->addQuery('limit', $limit);
        return $posts;
    }

    /**
     * 根据tag获取文章
     * @param $tag
     * @return mixed
     */
    protected function getPostsByTagModal($tag) {
        $limit = Input::get('limit', config('blog.posts_per_page'));
        $posts = Post::where('published_at', '<=', Carbon::now())->whereHas('tags', function ($q) use ($tag) {
            $q->where('tag', '=', $tag->tag);
        })->where('is_draft', 0)->orderBy('published_at', (bool)$tag->reverse_direction ? 'asc' :
            'desc')->paginate($limit);
        $posts->addQuery('tag', $tag->tag);
        $posts->addQuery('limit', $limit);
        return $posts;
    }

    /**
     * 根据tag获取文章model
     * @param $tag
     * @return mixed
     */
    public function getPostsByTag($tag) {
        $limit = Input::get('limit', config('blog.posts_per_page'));
        $page = Input::get('page', 1);
        $cacheName = self::REDIS_POST_CACHE . $limit . '_' . $page . '_' . $tag->title;
        return self::getCache(self::REDIS_POST_CACHE_TAG, $cacheName, "getPostsByTagModal", $tag);
    }
}