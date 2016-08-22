@extends('mis.layout')

@section('content')
    <div class="container-fluid">
        <div class="row page-title-row">
            <div class="col-md-12">
                @include("mis.partials._header1",['name' =>  'language.tags','action' => 'edit','language' => 'language.edit'])
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">  {!! trans('language.edit').trans('language.form') !!}</h3>
                    </div>
                    <div class="panel-body">

                        @include('mis.partials.errors')
                        @include('mis.partials.success')

                        <form class="form-horizontal" role="form" method="POST" action="/mis/tag/{{ $id }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="id" value="{{ $id }}">

                            <div class="form-group">
                                <label for="tag" class="col-md-3 control-label">
                                    {!! trans('language.tags') !!}</label>
                                <div class="col-md-3">
                                    <p class="form-control-static">{{ $tag }}</p>
                                </div>
                            </div>

                            @include('mis.tag._form')

                            <div class="form-group">
                                <div class="col-md-7 col-md-offset-3">
                                    <button type="submit" class="btn btn-primary btn-md">
                                        <i class="fa fa-save"></i>
                                        {!! trans('language.save') !!}
                                    </button>
                                    <button type="button" class="btn btn-danger btn-md" data-toggle="modal" data-target="#modal-delete">
                                        <i class="fa fa-times-circle"></i>
                                        {!! trans('language.delete') !!}
                                    </button>

                                </div>
                            </div>

                        </form>

                    </div>
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
                    <h4 class="modal-title">Please Confirm</h4>
                </div>
                <div class="modal-body">
                    <p class="lead">
                        <i class="fa fa-question-circle fa-lg"></i>
                        Are you sure you want to delete this tag?
                    </p>
                </div>
                <div class="modal-footer">
                    <form method="POST" action="/mis/tag/{{ $id }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fa fa-times-circle"></i> Yes
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop