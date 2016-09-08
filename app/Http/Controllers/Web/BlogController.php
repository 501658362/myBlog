<?php
namespace App\Http\Controllers\Web;

use App\Facades\PostServices;
use App\Http\Model\Post;
use App\Http\Model\Tag;
use App\Jobs\BlogIndexData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class BlogController extends Controller {

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        Cache::tags('redis_post_cache_tag')->flush();
        $tag = $request->get('tag');
        $data = $this->dispatch(new BlogIndexData($tag));
        $layout = 'web.blog.layouts.index';
        return view($layout, $data);
        //        $posts = Post::where('published_at', '<=', Carbon::now())
        //            ->orderBy('published_at', 'desc')
        //            ->paginate(config('blog.posts_per_page'));
        //
        //        return view('Web.Blog.index', compact('posts'));
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
        return view($post->layout, compact('post', 'tag', 'slug'));
        //        $post = Post::whereSlug($slug)->firstOrFail();
        //        return view('Web.Blog.post')->withPost($post);
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
}
