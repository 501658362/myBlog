<?php

namespace App\Http\Controllers\Mis;

use App\Http\Model\Tag;
use Illuminate\Http\Request;
use App\Http\Requests\TagCreateRequest;
use App\Http\Requests\TagUpdateRequesT;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class TagController extends BaseController
{
    protected $fields = [
        'tag' => '',
        'title' => '',
        'subtitle' => '',
        'meta_description' => '',
        'page_image' => '',
        'layout' => 'blog.layouts.index',
        'reverse_direction' => 0,
    ];

    public function __construct(Request $request){
        $this->viewPath="mis.tag.%s";
        parent::__construct($request);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $tags = Tag::all();
        return parent::view('index')->withTags($tags);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $data = [];
        foreach ($this->fields as $field => $default) {
            $data[$field] = old($field, $default);
        }

        return parent::view('create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param TagCreateRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(TagCreateRequest $request)
    {
        //
        $tag = new Tag();
        foreach (array_keys($this->fields) as $field) {
            $tag->$field = $request->get($field);
        }
        $tag->save();

        return redirect('/mis/tag')
            ->withSuccess("The tag '$tag->tag' was created.");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $tag = Tag::findOrFail($id);
        $data = ['id' => $id];
        foreach (array_keys($this->fields) as $field) {
            $data[$field] = old($field, $tag->$field);
        }

        return parent::view('edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param TagUpdateRequesT $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(TagUpdateRequesT $request, $id)
    {
        $tag = Tag::findOrFail($id);

        foreach (array_keys(array_except($this->fields, ['tag'])) as $field) {
            $tag->$field = $request->get($field);
        }
        $tag->save();

        return redirect("/mis/tag/$id/edit")
            ->withSuccess("Changes saved.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();

        return redirect('/mis/tag')
            ->withSuccess("The '$tag->tag' tag has been deleted.");
    }
}
