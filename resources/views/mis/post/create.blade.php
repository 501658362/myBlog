@extends('mis.layout')

@section('styles')
  @include("mis.post.css")
@stop

@section('content')
    <div class="container-fluid">
        <div class="row page-title-row">
            <div class="col-md-12">
                @include("mis.partials._header1",['name' =>  'language.posts','action' => 'add','language' => 'language.add'])
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

                        <form class="form-horizontal" role="form" method="POST" action="{{ route('mis.post.store') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            @include('mis.post._form')

                            <div class="col-md-8">
                                <div class="form-group">
                                    <div class="col-md-10 col-md-offset-2">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fa fa-disk-o"></i>
                                            {!! trans('language.save').trans('language.posts') !!}
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('scripts')
    @include("mis.post.js")

@stop