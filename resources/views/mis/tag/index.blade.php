@extends('mis.layout')

@section('content')
    <div class="container-fluid">
        <div class="row page-title-row">
            <div class="col-md-6">
                @include("mis.partials._header",['name' => 'language.tags'])
            </div>
            <div class="col-md-6 text-right">
                <a href="/mis/tag/create" class="btn btn-success btn-md">
                    <i class="fa fa-plus-circle"></i> {!! trans('language.new').trans('language.tags') !!}
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
                        <th>{!! trans('language.tags') !!}</th>
                        <th>{!! trans('language.title') !!}</th>
                        <th class="hidden-sm">{!! trans('language.subtitle') !!}</th>
                        <th class="hidden-md">{!! trans('language.page_image') !!}</th>
                        <th class="hidden-md">{!! trans('language.meta') !!}</th>
                        <th class="hidden-md">{!! trans('language.layout') !!}</th>
                        <th class="hidden-sm">Direction</th>
                        <th data-sortable="false">{!! trans('language.actions') !!}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($tags as $tag)
                        <tr>
                            <td>{{ $tag->tag }}</td>
                            <td>{{ $tag->title }}</td>
                            <td class="hidden-sm">{{ $tag->subtitle }}</td>
                            <td class="hidden-md">{{ $tag->page_image }}</td>
                            <td class="hidden-md">{{ $tag->meta_description }}</td>
                            <td class="hidden-md">{{ $tag->layout }}</td>
                            <td class="hidden-sm">
                                @if ($tag->reverse_direction)
                                    Reverse
                                @else
                                    Normal
                                @endif
                            </td>
                            <td>
                                <a href="/mis/tag/{{ $tag->id }}/edit" class="btn btn-xs btn-info">
                                    <i class="fa fa-edit"></i> {!! trans('language.edit') !!}
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