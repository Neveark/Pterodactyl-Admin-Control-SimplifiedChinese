{{-- Pterodactyl - Panel --}}
{{-- Copyright (c) 2015 - 2017 Dane Everitt <dane@daneeveritt.com> --}}

{{-- This software is licensed under the terms of the MIT license. --}}
{{-- https://opensource.org/licenses/MIT --}}
@extends('layouts.admin')

@section('title')
    服务器 — {{ $server->name }}: Manage
@endsection

@section('content-header')
    <h1>{{ $server->name }}<small>操作此服务器的附加操作。</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">管理</a></li>
        <li><a href="{{ route('admin.servers') }}">服务器</a></li>
        <li><a href="{{ route('admin.servers.view', $server->id) }}">{{ $server->name }}</a></li>
        <li class="active">管理</li>
    </ol>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="nav-tabs-custom nav-tabs-floating">
            <ul class="nav nav-tabs">
                <li><a href="{{ route('admin.servers.view', $server->id) }}">关于</a></li>
                @if($server->installed === 1)
                    <li><a href="{{ route('admin.servers.view.details', $server->id) }}">详情</a></li>
                    <li><a href="{{ route('admin.servers.view.build', $server->id) }}">构建配置</a></li>
                    <li><a href="{{ route('admin.servers.view.startup', $server->id) }}">启动</a></li>
                    <li><a href="{{ route('admin.servers.view.database', $server->id) }}">数据库</a></li>
                    <li class="active"><a href="{{ route('admin.servers.view.manage', $server->id) }}">管理</a></li>
                @endif
                <li class="tab-danger"><a href="{{ route('admin.servers.view.delete', $server->id) }}">删除</a></li>
                <li class="tab-success"><a href="{{ route('server.index', $server->uuidShort) }}"><i class="fa fa-external-link"></i></a></li>
            </ul>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">重新安装服务器</h3>
            </div>
            <div class="box-body">
                <p>这将使用分配的包和服务脚本重新安装服务器。 <strong>危险！</ strong>这可能会覆盖服务器数据。</p>
            </div>
            <div class="box-footer">
                @if($server->installed === 1)
                    <form action="{{ route('admin.servers.view.manage.reinstall', $server->id) }}" method="POST">
                        {!! csrf_field() !!}
                        <button type="submit" class="btn btn-danger">重新安装服务器</button>
                    </form>
                @else
                    <button class="btn btn-danger disabled">服务器必须被正确安装才能被重新安装。</button>
                @endif
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">安装状态</h3>
            </div>
            <div class="box-body">
                    <p>如果您需要将安装状态从“未安装”更改为“已安装”，反之亦然，则可以使用下面的按钮进行更改。</p>
            </div>
            <div class="box-footer">
                <form action="{{ route('admin.servers.view.manage.toggle', $server->id) }}" method="POST">
                    {!! csrf_field() !!}
                    <button type="submit" class="btn btn-primary">切换安装状态</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">重新构建容器</h3>
            </div>
            <div class="box-body">
                <p>下次启动时，这将触发服务器容器的重建。 如果您手动修改了服务器配置文件，或者某些功能不正常，则这很有用。</p>
            </div>
            <div class="box-footer">
                <form action="{{ route('admin.servers.view.manage.rebuild', $server->id) }}" method="POST">
                    {!! csrf_field() !!}
                    <button type="submit" class="btn btn-default">重新构建服务器容器</button>
                </form>
            </div>
        </div>
    </div>
    @if(! $server->suspended)
        <div class="col-sm-4">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">停用服务器</h3>
                </div>
                <div class="box-body">
                    <p>这将停用服务器，停止所有正在运行的进程，并立即阻止用户访问其文件或通过面板或API管理服务器。</p>
                </div>
                <div class="box-footer">
                    <form action="{{ route('admin.servers.view.manage.suspension', $server->id) }}" method="POST">
                        {!! csrf_field() !!}
                        <input type="hidden" name="action" value="suspend" />
                        <button type="submit" class="btn btn-warning">停用服务器</button>
                    </form>
                </div>
            </div>
        </div>
    @else
        <div class="col-sm-4">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">启用服务器</h3>
                </div>
                <div class="box-body">
                    <p>这将取消服务器的停用并恢复正常的用户访问。</p>
                </div>
                <div class="box-footer">
                    <form action="{{ route('admin.servers.view.manage.suspension', $server->id) }}" method="POST">
                        {!! csrf_field() !!}
                        <input type="hidden" name="action" value="unsuspend" />
                        <button type="submit" class="btn btn-success">启用服务器</button>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
