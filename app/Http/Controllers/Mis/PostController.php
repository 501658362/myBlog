<?php

namespace App\Http\Controllers\Mis;

use App\Http\Model\Post;
use App\Http\Requests\PostUpdateRequest;
use App\Jobs\PostFormFields;
use Illuminate\Http\Request;
use App\Http\Requests\PostCreateRequest;
use App\Http\Controllers\Controller;

class PostController extends BaseController
{


    public function __construct(Request $request){
        $this->viewPath = "mis.post.%s";
        parent::__construct($request);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return parent::view('index')  ->withPosts(Post::all());
    }


    /**
     * Show the new post form
     */
    public function create()
    {
        $data = $this->dispatch(new PostFormFields());

        return parent::view('create', $data);
    }

    /**
     * Store a newly created Post
     *
     * @param PostCreateRequest $request
     */
    public function store(PostCreateRequest $request)
    {
        $post = Post::create($request->postFillData());
        $post->syncTags($request->get('tags', []));

        return redirect()
            ->route('mis.post.index')
            ->withSuccess('New Post Successfully Created.');
    }

    /**
     * Show the post edit form
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $data = $this->dispatch(new PostFormFields($id));

        return parent::view('edit', $data);
    }

    /**
     * Update the Post
     *
     * @param PostUpdateRequest $request
     * @param int $id
     */
    public function update(PostUpdateRequest $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->fill($request->postFillData());
        $post->save();
        $post->syncTags($request->get('tags', []));

        if ($request->action === 'continue') {
            return redirect()
                ->back()
                ->withSuccess('Post saved.');
        }

        return redirect()
            ->route('mis.post.index')
            ->withSuccess('Post saved.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->tags()->detach();
        $post->delete();

        return redirect()
            ->route('mis.post.index')
            ->withSuccess('Post deleted.');
    }
}
