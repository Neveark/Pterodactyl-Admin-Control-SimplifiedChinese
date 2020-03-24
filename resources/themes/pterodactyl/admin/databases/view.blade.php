{{-- Pterodactyl - Panel --}}
{{-- Copyright (c) 2015 - 2017 Dane Everitt <dane@daneeveritt.com> --}}

{{-- This software is licensed under the terms of the MIT license. --}}
{{-- https://opensource.org/licenses/MIT --}}
@extends('layouts.admin')

@section('title')
    Database Hosts &rarr; View &rarr; {{ $host->name }}
@endsection

@section('content-header')
    <h1>{{ $host->name }}<small>查看关联的数据库以及此数据库主机的详细信息。</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">管理</a></li>
        <li><a href="{{ route('admin.databases') }}">数据库主机</a></li>
        <li class="active">{{ $host->name }}</li>
    </ol>
@endsection

@section('content')
<form action="{{ route('admin.databases.view', $host->id) }}" method="POST">
    <div class="row">
        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">主机详情</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label for="pName" class="form-label">名称</label>
                        <input type="text" id="pName" name="name" class="form-control" value="{{ old('name', $host->name) }}" />
                    </div>
                    <div class="form-group">
                        <label for="pHost" class="form-label">主机</label>
                        <input type="text" id="pHost" name="host" class="form-control" value="{{ old('host', $host->host) }}" />
                        <p class="text-muted small">尝试从<em>面板</em>连接到此MySQL主机以添加新数据库时应使用的IP地址或FQDN。</p>
                    </div>
                    <div class="form-group">
                        <label for="pPort" class="form-label">端口</label>
                        <input type="text" id="pPort" name="port" class="form-control" value="{{ old('port', $host->port) }}" />
                        <p class="text-muted small">MySQL在此主机上运行的端口。</p>
                    </div>
                    <div class="form-group">
                        <label for="pNodeId" class="form-label">连接的节点</label>
                        <select name="node_id" id="pNodeId" class="form-control">
                            <option value="0">无</option>
                            @foreach($locations as $location)
                                <optgroup label="{{ $location->short }}">
                                    @foreach($location->nodes as $node)
                                        <option value="{{ $node->id }}" {{ $host->node_id !== $node->id ?: 'selected' }}>{{ $node->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        <p class="text-muted small">在将数据库添加到所选节点上的服务器时，此设置除了对该数据库主机的默认设置外没有任何作用。</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">用户详情</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label for="pUsername" class="form-label">用户名</label>
                        <input type="text" name="username" id="pUsername" class="form-control" value="{{ old('username', $host->username) }}" />
                        <p class="text-muted small">具有足够权限在系统上创建新用户和数据库的帐户的用户名。</p>
                    </div>
                    <div class="form-group">
                        <label for="pPassword" class="form-label">密码</label>
                        <input type="password" name="password" id="pPassword" class="form-control" />
                        <p class="text-muted small">所定义帐户的密码。保留空白以继续使用之前指定的密码。</p>
                    </div>
                    <hr />
                    <p class="text-danger small text-left">为此数据库主机指定的账户<strong>必须</strong>拥有<code>WITH GRANT OPTION</code>权限。如果定义的帐户没有此权限，则创建数据库的请求将失败。 <strong>请勿为该面板使用与使用MySQL时使用的相同的帐户详细信息。</strong></p>
                </div>
                <div class="box-footer">
                    {!! csrf_field() !!}
                    <button name="_method" value="PATCH" class="btn btn-sm btn-primary pull-right">保存</button>
                    <button name="_method" value="DELETE" class="btn btn-sm btn-danger pull-left muted muted-hover"><i class="fa fa-trash-o"></i></button>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">数据库</h3>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <tr>
                        <th>服务器</th>
                        <th>数据库名称</th>
                        <th>用户名</th>
                        <th>连接来源</th>
                        <th></th>
                    </tr>
                    @foreach($databases as $database)
                        <tr>
                            <td class="middle"><a href="{{ route('admin.servers.view', $database->getRelation('server')->id) }}">{{ $database->getRelation('server')->name }}</a></td>
                            <td class="middle">{{ $database->database }}</td>
                            <td class="middle">{{ $database->username }}</td>
                            <td class="middle">{{ $database->remote }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.servers.view.database', $database->getRelation('server')->id) }}">
                                    <button class="btn btn-xs btn-primary">管理</button>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
            @if($databases->hasPages())
                <div class="box-footer with-border">
                    <div class="col-md-12 text-center">{!! $databases->render() !!}</div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('footer-scripts')
    @parent
    <script>
        $('#pNodeId').select2();
    </script>
@endsection
