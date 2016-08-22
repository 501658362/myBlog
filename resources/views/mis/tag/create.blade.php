@extends('mis.layout')

@section('content')
    <div class="container-fluid">
        <div class="row page-title-row">
            <div class="col-md-12">
                @include("mis.partials._header1",['name' =>  'language.tags','action' => 'add','language' => 'language.add'])
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">     {!! trans('language.add').trans('language.form') !!}</h3>
                    </div>
                    <div class="panel-body">

                        @include('mis.partials.errors')

                        <form class="form-horizontal" role="form" method="POST" action="/mis/tag">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="form-group">
                                <label for="tag" class="col-md-3 control-label">Tag</label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="tag" id="tag" value="{{ $tag }}" autofocus>
                                </div>
                            </div>

                            @include('mis.tag._form')

                            <div class="form-group">
                                <div class="col-md-7 col-md-offset-3">
                                    <button type="submit" class="btn btn-primary btn-md">
                                        <i class="fa fa-plus-circle"></i>
                                        {!! trans('language.add').trans('language.new').trans('language.tags') !!}
                                    </button>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

@stop