<?php

namespace App\Http\Model;

use App\Services\Markdowner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    //
//    public $dateFormat = 'U';

//    protected $guarded = ['id','views','user_id','updated_at','created_at'];

    protected $dates = ['delete_at','published_at'];
    // 在 Post 类的 $dates 属性后添加 $fillable 属性
    protected $fillable = [
        'title', 'subtitle', 'content_raw', 'page_image', 'meta_description','layout', 'is_draft', 'published_at',
    ];

    /**
     * Return the date portion of published_at
     */
    public function getPublishDateAttribute($value)
    {
        return $this->published_at->format('M-j-Y');
    }

    /**
     * Return the time portion of published_at
     */
    public function getPublishTimeAttribute($value)
    {
        return $this->published_at->format('g:i A');
    }

    /**
     * Alias for content_raw
     */
    public function getContentAttribute($value)
    {
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
     *
     * @return BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag_pivot');
    }

    /**
     * Set the title attribute and automatically the slug
     *
     * @param string $value
     */
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;

        if (! $this->exists) {
            $this->setUniqueSlug($value, '');
        }
    }

    /**
     * Recursive routine to set a unique slug
     *
     * @param string $title
     * @param mixed $extra
     */
    protected function setUniqueSlug($title, $extra)
    {
        $slug = str_slug($title.'-'.$extra);

        if (static::whereSlug($slug)->exists()) {
            $this->setUniqueSlug($title, $extra + 1);
            return;
        }

        $this->attributes['slug'] = $slug;
    }

    /**
     * Set the HTML content automatically when the raw content is set
     *
     * @param string $value
     */
    public function setContentRawAttribute($value)
    {
        $markdown = new Markdowner();

        $this->attributes['content_raw'] = $value;
        $this->attributes['content_html'] = $markdown->toHTML($value);
    }

    /**
     * Sync tag relation adding new tags as needed
     *
     * @param array $tags
     */
    public function syncTags(array $tags)
    {
        Tag::addNeededTags($tags);

        if (count($tags)) {
            $this->tags()->sync(
                Tag::whereIn('tag', $tags)->lists('id')->all()
            );
            return;
        }

        $this->tags()->detach();
    }

}
