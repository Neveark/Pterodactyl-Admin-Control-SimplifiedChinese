{{-- Pterodactyl - Panel --}}
{{-- Copyright (c) 2015 - 2017 Dane Everitt <dane@daneeveritt.com> --}}

{{-- This software is licensed under the terms of the MIT license. --}}
{{-- https://opensource.org/licenses/MIT --}}
@extends('layouts.admin')

@section('title')
    服务器 — {{ $server->name }}: Details
@endsection

@section('content-header')
    <h1>{{ $server->name }}<small>编辑包括此服务器的主人以及容器的详情。</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">管理</a></li>
        <li><a href="{{ route('admin.servers') }}">服务器</a></li>
        <li><a href="{{ route('admin.servers.view', $server->id) }}">{{ $server->name }}</a></li>
        <li class="active">Details</li>
    </ol>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="nav-tabs-custom nav-tabs-floating">
            <ul class="nav nav-tabs">
                <li><a href="{{ route('admin.servers.view', $server->id) }}">关于</a></li>
                @if($server->installed === 1)
                    <li class="active"><a href="{{ route('admin.servers.view.details', $server->id) }}">详情</a></li>
                    <li><a href="{{ route('admin.servers.view.build', $server->id) }}">构建配置</a></li>
                    <li><a href="{{ route('admin.servers.view.startup', $server->id) }}">启动</a></li>
                    <li><a href="{{ route('admin.servers.view.database', $server->id) }}">数据库</a></li>
                    <li><a href="{{ route('admin.servers.view.manage', $server->id) }}">管理</a></li>
                @endif
                <li class="tab-danger"><a href="{{ route('admin.servers.view.delete', $server->id) }}">删除</a></li>
                <li class="tab-success"><a href="{{ route('server.index', $server->uuidShort) }}"><i class="fa fa-external-link"></i></a></li>
            </ul>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">基本信息</h3>
            </div>
            <form action="{{ route('admin.servers.view.details', $server->id) }}" method="POST">
                <div class="box-body">
                    <div class="form-group">
                        <label for="name" class="control-label">服务器名称 <span class="field-required"></span></label>
                        <input type="text" name="name" value="{{ old('name', $server->name) }}" class="form-control" />
                        <p class="text-muted small">字符限制: <code>a-zA-Z0-9_-</code> 以及 <code>[空格]</code> (最大 35 字符)。</p>
                    </div>
                    <div class="form-group">
                        <label for="external_id" class="control-label">外部标识符</label>
                        <input type="text" name="external_id" value="{{ old('external_id', $server->external_id) }}" class="form-control" />
                        <p class="text-muted small">留空来不为此服务器分配任何外部标识符。外部ID应该是独特的，并且不能被用于其他服务器。</p>
                    </div>
                    <div class="form-group">
                        <label for="pUserId" class="control-label">服务器主人 <span class="field-required"></span></label>
                        <select name="owner_id" class="form-control" id="pUserId">
                            <option value="{{ $server->owner_id }}" selected>{{ $server->user->email }}</option>
                        </select>
                        <p class="text-muted small">你可以通过更改此格为一个可以在此系统上被查询到的电子邮件地址来更改此服务器的主人。如果你进行这个操作，系统会自动生成一个新的守护进程安全密钥。</p>
                    </div>
                    <div class="form-group">
                        <label for="description" class="control-label">服务器描述</label>
                        <textarea name="description" rows="3" class="form-control">{{ old('description', $server->description) }}</textarea>
                        <p class="text-muted small">对此服务器进行一个简短的描述。</p>
                    </div>
                </div>
                <div class="box-footer">
                    {!! csrf_field() !!}
                    {!! method_field('PATCH') !!}
                    <input type="submit" class="btn btn-sm btn-primary" value="更新详情" />
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('footer-scripts')
    @parent
    <script>
    $('#pUserId').select2({
        ajax: {
            url: Router.route('admin.users.json'),
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page,
                };
            },
            processResults: function (data, params) {
                return { results: data };
            },
            cache: true,
        },
        escapeMarkup: function (markup) { return markup; },
        minimumInputLength: 2,
        templateResult: function (data) {
            if (data.loading) return data.text;

            return '<div class="user-block"> \
                <img class="img-circle img-bordered-xs" src="https://www.gravatar.com/avatar/' + data.md5 + '?s=120" alt="User Image"> \
                <span class="username"> \
                    <a href="#">' + data.name_first + ' ' + data.name_last +'</a> \
                </span> \
                <span class="description"><strong>' + data.email + '</strong> - ' + data.username + '</span> \
            </div>';
        },
        templateSelection: function (data) {
            if (typeof data.name_first === 'undefined') {
                data = {
                    md5: '{{ md5(strtolower($server->user->email)) }}',
                    name_first: '{{ $server->user->name_first }}',
                    name_last: '{{ $server->user->name_last }}',
                    email: '{{ $server->user->email }}',
                    id: {{ $server->owner_id }}
                };
            }

            return '<div> \
                <span> \
                    <img class="img-rounded img-bordered-xs" src="https://www.gravatar.com/avatar/' + data.md5 + '?s=120" style="height:28px;margin-top:-4px;" alt="User Image"> \
                </span> \
                <span style="padding-left:5px;"> \
                    ' + data.name_first + ' ' + data.name_last + ' (<strong>' + data.email + '</strong>) \
                </span> \
            </div>';
        }
    });
    </script>
@endsection
