{{-- Pterodactyl - Panel --}}
{{-- Copyright (c) 2015 - 2017 Dane Everitt <dane@daneeveritt.com> --}}

{{-- This software is licensed under the terms of the MIT license. --}}
{{-- https://opensource.org/licenses/MIT --}}
@extends('layouts.admin')

@section('title')
    {{ $node->name }}: 配置
@endsection

@section('content-header')
    <h1>{{ $node->name }}<small>你的守护进程配置文件。</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">管理</a></li>
        <li><a href="{{ route('admin.nodes') }}">节点</a></li>
        <li><a href="{{ route('admin.nodes.view', $node->id) }}">{{ $node->name }}</a></li>
        <li class="active">配置</li>
    </ol>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="nav-tabs-custom nav-tabs-floating">
            <ul class="nav nav-tabs">
                <li><a href="{{ route('admin.nodes.view', $node->id) }}">关于</a></li>
                <li><a href="{{ route('admin.nodes.view.settings', $node->id) }}">设置</a></li>
                <li class="active"><a href="{{ route('admin.nodes.view.configuration', $node->id) }}">配置</a></li>
                <li><a href="{{ route('admin.nodes.view.allocation', $node->id) }}">分配</a></li>
                <li><a href="{{ route('admin.nodes.view.servers', $node->id) }}">服务器</a></li>
            </ul>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">配置文件</h3>
            </div>
            <div class="box-body">
                <pre class="no-margin">{{ $node->getConfigurationAsJson(true) }}</pre>
            </div>
            <div class="box-footer">
                <p class="no-margin">该文件应放置在守护程序的<code>config</code>目录中并命名为<code>core.json</code>。</p>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">自动部署</h3>
            </div>
            <div class="box-body">
                <p class="text-muted small">为了简化节点的配置，可以从面板中获取配置。此过程需要密钥。下面的按钮将生成一个密钥，并为你提供自动配置节点所需的命令。<em>密钥仅有5分钟的有效期。</em></p>
            </div>
            <div class="box-footer">
                <button type="button" id="configTokenBtn" class="btn btn-sm btn-default" style="width:100%;">生成密钥</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer-scripts')
    @parent
    <script>
    $('#configTokenBtn').on('click', function (event) {
        $.getJSON('{{ route('admin.nodes.view.configuration.token', $node->id) }}').done(function (data) {
            swal({
                type: 'success',
                title: '密钥已创建。',
                text: '你的密钥将会在<strong>五分钟</strong>后过期。<br /><br />' +
                      '<p>运行此命令以自动部署节点：<br /><small><pre>npm run configure -- --panel-url {{ config('app.url') }} --token ' + data.token + '</pre></small></p>',
                html: true
            })
        }).fail(function () {
            swal({
                title: '错误',
                text: '创建你的密钥时发生了错误。',
                type: 'error'
            });
        });
    });
    </script>
@endsection
