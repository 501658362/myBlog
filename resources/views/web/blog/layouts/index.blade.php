@extends('web.blog.layouts.master')

@section('page-header')
    <header class="intro-header"
            style="background-image: url('{{ page_image($page_image) }}')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="site-heading">
                        <h1>{{ $title }}</h1>
                        <hr class="small">
                        <h2 class="subheading">{{ $subtitle }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </header>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">

                {{-- 文章列表 --}}
                @foreach ($posts as $post)
                    <div class="post-preview">

                        <a href="{{ $post->url($tag) }}">
                           <h2 class="post-title"
                               @if( $post->top_level > 0)
                               style="color: red"
                                   @endif
                           >
                               {{ $post->title }}</h2>
                            @if ($post->subtitle)
                                <h3 class="post-subtitle">{{ $post->subtitle }}</h3>
                            @endif
                        </a>
                        <p class="post-meta">
                            Posted on {{ $post->published_at->format('Y-m-d H:i:s') }}
                            @if ($post->tags->count())
                                in
                            {!! join(', ', $post->tagLinks()) !!}
                        @endif


                        <!-- UYAN COUNT BEGIN -->
                            {{--<a class="pull-right"--}}
                               {{--style="    color: #808080;    font-size: 18px;    font-style: italic;    margin-top: 0;"--}}
                               {{--href="{!! url("/blog/$post->slug") !!}" id="uyan_count_unit" du=www.chenyanjin.top" su="{!! $post->slug !!}">评论(0)</a>--}}
                            <!-- UYAN COUNT END -->
                            <font style="margin-right: 10px"  class="pull-right">  &nbsp;  阅读({!! $post->views !!})</font>

                            @if ($post->published_at != $post->updated_at)
                                <span class="pull-right"> Last updated on {{ $post->updated_at->format('Y-m-d H:i:s') }}</span>
                            @endif
                        </p>
                    </div>
                    <hr>
                @endforeach

                {{-- 分页 --}}
                <ul class="pager">

                    {{-- Reverse direction --}}
                    @if ($reverse_direction)
                        @if ($posts->currentPage() > 1)
                            <li class="previous">
                                <a href="{!! $posts->url($posts->currentPage() - 1) !!}">
                                    <i class="fa fa-long-arrow-left fa-lg"></i>
                                    {{--Previous {{ $tag->tag }} Posts--}}
                                    Previous Page
                                </a>
                            </li>
                        @endif
                        @if ($posts->hasMorePages())
                            <li class="next">
                                <a href="{!! $posts->nextPageUrl() !!}">
                                    Next Page
                                    {{--Next {{ $tag->tag }} Posts--}}
                                    <i class="fa fa-long-arrow-right"></i>
                                </a>
                            </li>
                        @endif
                    @else
                        @if ($posts->currentPage() > 1)
                            <li class="previous">
                                <a href="{!! $posts->url($posts->currentPage() - 1) !!}">
                                    <i class="fa fa-long-arrow-left fa-lg"></i>
                                    Newer {{ $tag ? $tag->tag : '' }} Posts
                                </a>
                            </li>
                        @endif
                        @if ($posts->hasMorePages())
                            <li class="next">
                                <a href="{!! $posts->nextPageUrl() !!}">
                                    Older {{ $tag ? $tag->tag : '' }} Posts
                                    <i class="fa fa-long-arrow-right"></i>
                                </a>
                            </li>
                        @endif
                    @endif
                </ul>
            </div>

        </div>
    </div>
@stop