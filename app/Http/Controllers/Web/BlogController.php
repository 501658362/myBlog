<?php
namespace App\Http\Controllers\Web;

use App\Facades\PostServices;
use App\Http\Controllers\BaseController;
use App\Http\Model\Post;
use App\Http\Model\Tag;
use App\Jobs\BlogIndexData;
use App\Services\RssFeed;
use App\Services\SiteMap;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class BlogController extends BaseController {

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $tag = $request->get('tag');
        $data = $this->dispatch(new BlogIndexData($tag));
        $layout = 'web.blog.layouts.index';
        return parent::view($layout, $data);
        //        $posts = Post::where('published_at', '<=', Carbon::now())
        //            ->orderBy('published_at', 'desc')
        //            ->paginate(config('blog.posts_per_page'));
        //
        //        return parent::view('Web.Blog.index', compact('posts'));
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
    }

    /**
     * Display the specified resource.
     * @param $slug
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function show($slug, Request $request) {
        $post = PostServices::getPost($slug);
        $tag = $request->get('tag');
        if ($tag) {
            $tag = Tag::whereTag($tag)->firstOrFail();
        }
        return parent::view($post->layout, compact('post', 'tag', 'slug'));
        //        $post = Post::whereSlug($slug)->firstOrFail();
        //        return parent::view('Web.Blog.post')->withPost($post);
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
        Cache::tags('redis_post_cache_tag')->flush();
    }

    public function siteMap(SiteMap $siteMap) {
        $map = $siteMap->getSiteMap();
        return response($map)
            ->header('Content-type', 'text/xml');
//        if(! $this->makeFile($map,'SiteMap', 'xml')){
//            dd("生成站点地图失败");
//        }
//        dd("生成站点地图成功");
    }

    public function rss(RssFeed $feed) {
        $rss = $feed->getRSS();
        dd("O__O  额 好像有问题。。");
        return response()->download($this->makeFile($rss, config("blog.title") . 'rss' . date("Ymd", time()), 'xml', 'files/rss'));
    }
}
