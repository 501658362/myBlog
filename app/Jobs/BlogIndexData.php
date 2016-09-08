<?php
namespace App\Jobs;

use App\Facades\PostServices;
use App\Http\Model\Post;
use App\Http\Model\Tag;
use App\Jobs\Job;
use Carbon\Carbon;
use Illuminate\Contracts\Bus\SelfHandling;

class BlogIndexData extends Job implements SelfHandling {

    protected $tag;

    /**
     * 控制器
     * @param string|null $tag
     */
    public function __construct($tag) {
        $this->tag = $tag;
    }

    /**
     * Execute the job.
     * @return void
     */
    public function handle() {
        if ($this->tag) {
            return $this->tagIndexData($this->tag);
        }
        return $this->normalIndexData();
    }

    /**
     * Return data for normal index page
     * @return array
     */
    protected function normalIndexData() {
        $posts = PostServices::getPosts();
        return [
            'title'             => config('blog.title'),
            'subtitle'          => config('blog.subtitle'),
            'posts'             => $posts,
            'page_image'        => config('blog.page_image'),
            'meta_description'  => config('blog.description'),
            'reverse_direction' => true,
            //            'reverse_direction' => false,
            'tag'               => null,
        ];
    }

    /**
     * Return data for a tag index page
     * @param string $tag
     * @return array
     */
    protected function tagIndexData($tag) {
        $tag = Tag::where('tag', $tag)->firstOrFail();
        $reverse_direction = (bool)$tag->reverse_direction;
        $posts = PostServices::getPostsByTag($tag);
        $page_image = $tag->page_image ? : config('blog.page_image');
        return [
            'title'             => $tag->title,
            'subtitle'          => $tag->subtitle,
            'posts'             => $posts,
            'page_image'        => $page_image,
            'tag'               => $tag,
            'reverse_direction' => $reverse_direction,
            'meta_description'  => $tag->meta_description ? : config('blog.description'),
        ];
    }
}
