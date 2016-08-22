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

                <table id="posts-table" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>{!! trans('language.published') !!}</th>
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
                            <td>{{ $post->title }}</td>
                            <td>{{ $post->subtitle }}</td>
                            <td>
                                <a href="/mis/post/{{ $post->id }}/edit" class="btn btn-xs btn-info">
                                    <i class="fa fa-edit"></i> {!! trans('language.edit') !!}
                                </a>
                                <a href="/blog/{{ $post->slug }}" class="btn btn-xs btn-warning">
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
    <script>
        $(function() {
            $("#posts-table").DataTable({
                order: [[0, "desc"]]
            });
        });
    </script>
@stop