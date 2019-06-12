@extends('mis.layout')

@section('content')
    <div class="container-fluid">
        <div class="row page-title-row">
            <div class="col-md-6">
                @include("mis.partials._header",['name' => 'language.posts'])
            </div>
            <div class="col-md-6 text-right">
                <a href="/mis/post/create" class="btn btn-success btn-md">
                    <i class="fa fa-plus-circle"></i> {!! trans('language.new') !!}{!! trans('language.posts') !!}
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">

                @include('mis.partials.errors')
                @include('mis.partials.success')

                <table id="table" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>{!! trans('language.published') !!}</th>
                        <th>更新时间</th>
                        <th>置顶</th>
                        <th>{!! trans('language.title') !!}</th>
                        <th>{!! trans('language.subtitle') !!}</th>
                        <th data-sortable="false">{!! trans('language.actions') !!}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($posts as $post)
                        <tr>
                            <td data-order="{{ $post->published_at->timestamp }}">
                                {{ $post->published_at->format('Y-m-d H:i:s') }}
                            </td>
                            <td data-order="{{ $post->updated_at->timestamp }}">
                                {{ $post->updated_at->format('Y-m-d H:i:s') }}
                            </td>
                            <td data-order="{{ $post->top_level }}">
                                {{ $post->top_level }}
                            </td>
                            <td>{{ $post->title }}</td>
                            <td>{{ $post->subtitle }}</td>
                            <td>
                                <a href="/mis/post/{{ $post->id }}/edit" target="_blank" class="btn btn-xs btn-info">
                                    <i class="fa fa-edit"></i> {!! trans('language.edit') !!}
                                </a>
                                <a href="/blog/{{ $post->slug }}" target="_blank" class="btn btn-xs btn-warning">
                                    <i class="fa fa-eye"></i> {!! trans('language.view') !!}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@stop

@section('scripts')
    @include('mis.partials.datatable')
@stop