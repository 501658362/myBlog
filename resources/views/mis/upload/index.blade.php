@extends('mis.layout')

@section('content')
    <div class="container-fluid">

        {{-- 顶部工具栏 --}}
        <div class="row page-title-row">
            <div class="col-md-6">
                <h3 class="pull-left">Uploads </h3>
                <div class="pull-left">
                    <ul class="breadcrumb">
                        @foreach ($breadcrumbs as $path => $disp)
                            <li><a href="/mis/upload?folder={{ $path }}">{{ $disp }}</a></li>
                        @endforeach
                        <li class="active">{{ $folderName }}</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6 text-right">
                <button type="button" class="btn btn-success btn-md" data-toggle="modal" data-target="#modal-folder-create">
                    <i class="fa fa-plus-circle"></i> New Folder
                </button>
                <button type="button" class="btn btn-primary btn-md" data-toggle="modal" data-target="#modal-file-upload">
                    <i class="fa fa-upload"></i> Upload
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">

                @include('mis.partials.errors')
                @include('mis.partials.success')

                <table id="uploads-table" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Size</th>
                        <th data-sortable="false">Actions</th>
                    </tr>
                    </thead>
                    <tbody>

                    {{-- 子目录 --}}
                    @foreach ($subfolders as $path => $name)
                        <tr>
                            <td>
                                <a href="/mis/upload?folder={{ $path }}">
                                    <i class="fa fa-folder fa-lg fa-fw"></i>
                                    {{ $name }}
                                </a>
                            </td>
                            <td>Folder</td>
                            <td>-</td>
                            <td>-</td>
                            <td>
                                <button type="button" class="btn btn-xs btn-danger" onclick="delete_folder('{{ $name }}')">
                                    <i class="fa fa-times-circle fa-lg"></i>
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach

                    {{-- 所有文件 --}}
                    @foreach ($files as $file)
                        <tr>
                            <td>
                                <a href="{{ $file['webPath'] }}">
                                    @if (is_image($file['mimeType']))
                                        <i class="fa fa-file-image-o fa-lg fa-fw"></i>
                                    @else
                                        <i class="fa fa-file-o fa-lg fa-fw"></i>
                                    @endif
                                    {{ $file['name'] }}
                                </a>
                            </td>
                            <td>{{ $file['mimeType'] or 'Unknown' }}</td>
                            <td>{{ $file['modified']->format('j-M-y g:ia') }}</td>
                            <td>{{ human_filesize($file['size']) }}</td>
                            <td>
                                <button type="button" class="btn btn-xs btn-danger" onclick="delete_file('{{ $file['name'] }}')">
                                    <i class="fa fa-times-circle fa-lg"></i>
                                    Delete
                                </button>
                                @if (is_image($file['mimeType']))
                                    <button type="button" class="btn btn-xs btn-success" onclick="preview_image('{{ $file['webPath'] }}')">
                                        <i class="fa fa-eye fa-lg"></i>
                                        Preview
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>

            </div>
        </div>
    </div>

    @include('mis.upload._modals')

@stop

@section('scripts')
    <script>

        // 确认文件删除
        function delete_file(name) {
            $("#delete-file-name1").html(name);
            $("#delete-file-name2").val(name);
            $("#modal-file-delete").modal("show");
        }

        // 确认目录删除
        function delete_folder(name) {
            $("#delete-folder-name1").html(name);
            $("#delete-folder-name2").val(name);
            $("#modal-folder-delete").modal("show");
        }

        // 预览图片
        function preview_image(path) {
            $("#preview-image").attr("src", path);
            $("#modal-image-view").modal("show");
        }

        // 初始化数据
        $(function() {
            $("#uploads-table").DataTable();
        });
    </script>
@stop