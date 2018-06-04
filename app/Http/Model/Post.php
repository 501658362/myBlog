<?php
namespace App\Http\Model;

use App\Services\Markdowner;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;

class Post extends Model {
    use SoftDeletes;

    //
    //    public $dateFormat = 'U';
    //    protected $guarded = ['id','views','user_id','updated_at','created_at'];
    protected $dates = ['delete_at', 'published_at'];
    // 在 Post 类的 $dates 属性后添加 $fillable 属性
    protected $fillable
        = [
            'title',
            'subtitle',
            'content_raw',
            'page_image',
            'meta_description',
            'layout',
            'top_level',
            'is_draft',
            'published_at',
        ];

    /**
     * Return the date portion of published_at
     */
    public function getPublishDateAttribute($value) {
        return $this->published_at->format('Y-m-d');
    }

    /**
     * Return the time portion of published_at
     */
    public function getPublishTimeAttribute($value) {
        return $this->published_at->format('H:i:s');
    }

    /**
     * Alias for content_raw
     */
    public function getContentAttribute($value) {
        return $this->content_raw;
    }

    //    public function setTitleAttribute($value)
    //    {
    //        $this->attributes['title'] = $value;
    //
    //        if (! $this->exists) {
    //            $this->attributes['slug'] = str_slug($value);
    //        }
    //    }
    /**
     * The many-to-many relationship between posts and tags.
     * @return BelongsToMany
     */
    public function tags() {
        return $this->belongsToMany(Tag::class, 'post_tag_pivot');
    }

    /**
     * Set the title attribute and automatically the slug
     * @param string $value
     */
    public function setTitleAttribute($value) {
        $this->attributes[ 'title' ] = $value;
        if (!$this->exists) {
            $this->setUniqueSlug($value, '');
        }
        if(!$this->slug){
            $this->setUniqueSlug($value, '');
        }
    }

    /**
     * Recursive routine to set a unique slug
     * @param string $title
     * @param mixed $extra
     */
    protected function setUniqueSlug($title, $extra) {
        $slug = str_slug($title . '-' . $extra);
        if (static::whereSlug($slug)->exists()) {
            $this->setUniqueSlug($title, $extra + 1);
            return;
        }
        $this->attributes[ 'slug' ] = $slug;
    }

    /**
     * Set the HTML content automatically when the raw content is set
     * @param string $value
     */
    public function setContentRawAttribute($value) {
        $markdown = new Markdowner();
        $this->attributes[ 'content_raw' ] = $value;
        $this->attributes[ 'content_html' ] = $markdown->toHTML($value);
    }

    /**
     * Sync tag relation adding new tags as needed
     * @param array $tags
     */
    public function syncTags(array $tags) {
        Tag::addNeededTags($tags);
        if (count($tags)) {
            $this->tags()->sync(Tag::whereIn('tag', $tags)->lists('id')->all());
            return;
        }
        $this->tags()->detach();
    }

    /**
     * Return URL to post
     * @param Tag $tag
     * @return string
     */
    public function url(Tag $tag = null) {
        $url = url('blog/' . $this->slug);
        if ($tag) {
            $url .= '?tag=' . urlencode($tag->tag);
        }
        return $url;
    }

    /**
     * Return array of tag links
     * @param string $base
     * @return array
     */
    public function tagLinks($base = '/blog?tag=%TAG%') {
        $tags = $this->tags()->lists('tag');
        $return = [];
        foreach ($tags as $tag) {
            $url = str_replace('%TAG%', urlencode($tag), $base);
            $return[] = '<a href="' . $url . '">' . e($tag) . '</a>';
        }
        return $return;
    }

    /**
     * Return next post after this one or null
     * @param Tag $tag
     * @return Post
     */
    public function newerPost(Tag $tag = null) {
        $query
            = static::where('published_at', '>', $this->published_at)->where('published_at', '<=', Carbon::now())->where('is_draft', 0)->orderBy('published_at', 'asc');
        if ($tag) {
            $query = $query->whereHas('tags', function ($q) use ($tag) {
                $q->where('tag', '=', $tag->tag);
            });
        }
        return $query->first();
    }

    /**
     * Return older post before this one or null
     * @param Tag $tag
     * @return Post
     */
    public function olderPost(Tag $tag = null) {
        $query
            = static::where('published_at', '<', $this->published_at)->where('is_draft', 0)->orderBy('published_at', 'desc');
        if ($tag) {
            $query = $query->whereHas('tags', function ($q) use ($tag) {
                $q->where('tag', '=', $tag->tag);
            });
        }
        return $query->first();
    }
}
