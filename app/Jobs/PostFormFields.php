<?php

namespace App\Jobs;

use App\Http\Model\Post;
use App\Http\Model\Tag;
use App\Jobs\Job;
use Carbon\Carbon;
use Illuminate\Contracts\Bus\SelfHandling;

class PostFormFields extends Job implements SelfHandling
{
    /**
     * The id (if any) of the Post row
     *
     * @var integer
     */
    protected $id;

    /**
     * List of fields and default value for each field
     *
     * @var array
     */
    protected $fieldList = [
        'title' => '',
        'subtitle' => '',
        'page_image' => '',
        'content' => '',
        'meta_description' => '',
        'is_draft' => "0",
        'publish_date' => '',
        'publish_time' => '',
        'layout' => 'web.blog.layouts.post',
        'top_level' => 0,
        'tags' => [],
    ];

    /**
     * Create a new job instance.
     * @param null $id
     */
    public function __construct($id = null)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $fields = $this->fieldList;

        if ($this->id) {
            $fields = $this->fieldsFromModel($this->id, $fields);
        } else {
            $when = Carbon::now()->addHour();
            $fields['publish_date'] = $when->format('Y-m-d');
            $fields['publish_time'] = $when->format('H:i:s');
        }

        foreach ($fields as $fieldName => $fieldValue) {
            $fields[$fieldName] = old($fieldName, $fieldValue);
        }
        return array_merge(
            $fields,
            ['allTags' => Tag::lists('tag')->all()]
        );
    }


    /**
     * Return the field values from the model
     *
     * @param integer $id
     * @param array $fields
     * @return array
     */
    protected function fieldsFromModel($id, array $fields)
    {
        $post = Post::findOrFail($id);

        $fieldNames = array_keys(array_except($fields, ['tags']));

        $fields = ['id' => $id];
        foreach ($fieldNames as $field) {
            $fields[$field] = $post->{$field};
        }

        $fields['tags'] = $post->tags()->lists('tag')->all();

        return $fields;
    }
}
