@extends('mis.layout')

@section('styles')
    @include("mis.post.css")
@stop

@section('content')
    <div class="container-fluid">
        <div class="row page-title-row">
            <div class="col-md-12">
                @include("mis.partials._header1",['name' =>  'language.posts','action' => 'edit','language' => 'language.edit'])
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">{!! trans('language.posts').trans('language.form') !!}</h3>
                    </div>
                    <div class="panel-body">

                        @include('mis.partials.errors')
                        @include('mis.partials.success')

                        <form class="form-horizontal" role="form" method="POST" action="{{ route('mis.post.update', $id) }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="PUT">

                            @include('mis.post._form')

                            <div class="col-md-8">
                                <div class="form-group">
                                    <div class="col-md-10 col-md-offset-2">
                                        <button type="submit" class="btn btn-primary btn-lg" name="action" value="continue">
                                            <i class="fa fa-floppy-o"></i>
                                            {!! trans('language.save')!!} -  {!! trans('language.continue')!!}
                                        </button>
                                        <button type="submit" class="btn btn-success btn-lg" name="action" value="finished">
                                            <i class="fa fa-floppy-o"></i>
                                            {!! trans('language.save')!!} -  {!! trans('language.finished')!!}
                                        </button>
                                        <button type="button" class="btn btn-danger btn-lg" data-toggle="modal" data-target="#modal-delete">
                                            <i class="fa fa-times-circle"></i>
                                            {!! trans('language.delete')!!}
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>

        {{-- 确认删除 --}}
        <div class="modal fade" id="modal-delete" tabIndex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            ×
                        </button>
                        <h4 class="modal-title">{!! trans('language.please')!!}{!! trans('language.confirm')!!} </h4>
                    </div>
                    <div class="modal-body">
                        <p class="lead">
                            <i class="fa fa-question-circle fa-lg"></i>
                            你确定要{!! trans('language.delete')!!}这篇{!! trans('language.posts')!!}吗？
                        </p>
                    </div>
                    <div class="modal-footer">
                        <form method="POST" action="{{ route('mis.post.destroy', $id) }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="button" class="btn btn-default" data-dismiss="modal">   {!! trans('language.close')!!}</button>
                            <button type="submit" class="btn btn-danger">
                                <i class="fa fa-times-circle"></i>    {!! trans('language.yes')!!}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @include("mis.post.markdown")

    </div>


@stop

@section('scripts')
    @include("mis.post.js")
@stop