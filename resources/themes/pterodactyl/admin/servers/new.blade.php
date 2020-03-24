{{-- Pterodactyl - Panel --}}
{{-- Copyright (c) 2015 - 2017 Dane Everitt <dane@daneeveritt.com> --}}

{{-- This software is licensed under the terms of the MIT license. --}}
{{-- https://opensource.org/licenses/MIT --}}
@extends('layouts.admin')

@section('title')
    新服务器
@endsection

@section('content-header')
    <h1>创建服务器<small>向面板中添加新服务器。</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">管理</a></li>
        <li><a href="{{ route('admin.servers') }}">服务器</a></li>
        <li class="active">创建服务器</li>
    </ol>
@endsection

@section('content')
<form action="{{ route('admin.servers.new') }}" method="POST">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">核心信息</h3>
                </div>
                <div class="box-body row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="pName">服务器名称</label>
                            <input type="text" class="form-control" id="pName" name="name" value="{{ old('name') }}" placeholder="服务器名称">
                            <p class="small text-muted no-margin">字符限制：<code>a-z A-Z 0-9 _ - .</code> 以及 <code>[空格]</code> (最大200字节)。</p>
                        </div>
                        <div class="form-group">
                            <label for="pUserId">服务器主人</label>
                            <select class="form-control" style="padding-left:0;" name="owner_id" id="pUserId"></select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="description" class="control-label">服务器描述</label>
                            <textarea name="description" rows="3" class="form-control">{{ old('description') }}</textarea>
                            <p class="text-muted small">一段对此服务器的简短的描述。</p>
                        </div>
                        <div class="form-group">
                            <div class="checkbox checkbox-primary no-margin-bottom">
                                <input id="pStartOnCreation" name="start_on_completion" type="checkbox" value="1" checked></i>
                                <label for="pStartOnCreation" class="strong">安装完成后启动服务器</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="overlay" id="allocationLoader" style="display:none;"><i class="fa fa-refresh fa-spin"></i></div>
                <div class="box-header with-border">
                    <h3 class="box-title">分配管理</h3>
                </div>
                <div class="box-body row">
                    <div class="form-group col-sm-4">
                        <label for="pNodeId">节点</label>
                        <select name="node_id" id="pNodeId" class="form-control">
                            @foreach($locations as $location)
                                <optgroup label="{{ $location->long }} ({{ $location->short }})">
                                @foreach($location->nodes as $node)

                                <option value="{{ $node->id }}"
                                    @if($location->id === old('location_id')) selected @endif
                                >{{ $node->name }}</option>

                                @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        <p class="small text-muted no-margin">服务器将会被部署到的节点。</p>
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="pAllocation">默认分配</label>
                        <select name="allocation_id" id="pAllocation" class="form-control"></select>
                        <p class="small text-muted no-margin">默认被指定到此服务器的分配。</p>
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="pAllocationAdditional">附加分配</label>
                        <select name="allocation_additional[]" id="pAllocationAdditional" class="form-control" multiple></select>
                        <p class="small text-muted no-margin">在创建时被指定到此服务器的附加分配。</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="overlay" id="allocationLoader" style="display:none;"><i class="fa fa-refresh fa-spin"></i></div>
                <div class="box-header with-border">
                    <h3 class="box-title">应用程序特性限制</h3>
                </div>
                <div class="box-body row">
                    <div class="form-group col-xs-6">
                        <label for="cpu" class="control-label">数据库限制</label>
                        <div>
                            <input type="text" name="database_limit" class="form-control" value="{{ old('database_limit', 0) }}"/>
                        </div>
                        <p class="text-muted small">一个用户最多可为此服务器创建的数据库。留空代表不限制。</p>
                    </div>
                    <div class="form-group col-xs-6">
                        <label for="cpu" class="control-label">分配限制</label>
                        <div>
                            <input type="text" name="allocation_limit" class="form-control" value="{{ old('allocation_limit', 0) }}"/>
                        </div>
                        <p class="text-muted small">一个用户最多可为此服务器指定的分配。留空代表不限制。</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">资源管理</h3>
                </div>
                <div class="box-body row">
                    <div class="form-group col-sm-4">
                        <label for="pMemory">内存</label>
                        <div class="input-group">
                            <input type="text" value="{{ old('memory') }}" class="form-control" name="memory" id="pMemory" />
                            <span class="input-group-addon">MB</span>
                        </div>
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="pSwap">交换空间</label>
                        <div class="input-group">
                            <input type="text" value="{{ old('swap', 0) }}" class="form-control" name="swap" id="pSwap" />
                            <span class="input-group-addon">MB</span>
                        </div>
                    </div>
                </div>
                <div class="box-footer no-border no-pad-top no-pad-bottom">
                    <p class="text-muted small">如果你不希望为此服务器分配交换空间，将此值设为<code>0</code>即可，或是设为<code>-1</code>来不限制交换空间。如果你希望为此服务器禁用内存限制，将内存值设置为<code>0</code>即可。<p>
                </div>
                <div class="box-body row">
                    <div class="form-group col-sm-4">
                        <label for="pDisk">硬盘空间</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ old('disk') }}" name="disk" id="pDisk" />
                            <span class="input-group-addon">MB</span>
                        </div>
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="pCPU">CPU 限制</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ old('cpu', 0) }}" name="cpu" id="pCPU" />
                            <span class="input-group-addon">%</span>
                        </div>
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="pIO">Block IO 权重</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ old('io', 500) }}" name="io" id="pIO" />
                            <span class="input-group-addon">I/O</span>
                        </div>
                    </div>
                </div>
                <div class="box-footer no-border no-pad-top no-pad-bottom">
                            <p class="text-muted small">如果你不希望限制CPU用量，将此值设为<code>0</code>。要确定一个值，取所有<em>physical</em> 核心数并将它们乘以100。例如，在一个四核心的系统上，<code>(4 * 100 = 400)</code>，这里有总共<code>400%</code>可用。如需限制一个服务器使用一个核心的一半，你可以将此值设为<code>50</code>。如需允许一个服务器使用最多两个物理核心，将此值设为<code>200</code>。BlockIO 应该在<code>10</code>到<code>1000</code>之前。请参阅<a href="https://docs.docker.com/engine/reference/run/#/block-io-bandwidth-blkio-constraint" target="_blank">这篇文档</a>来了解有关它的全部信息。<p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Nest 配置</h3>
                </div>
                <div class="box-body row">
                    <div class="form-group col-xs-12">
                        <label for="pNestId">Nest</label>
                        <select name="nest_id" id="pNestId" class="form-control">
                            @foreach($nests as $nest)
                                <option value="{{ $nest->id }}"
                                    @if($nest->id === old('nest_id'))
                                        selected="selected"
                                    @endif
                                >{{ $nest->name }}</option>
                            @endforeach
                        </select>
                        <p class="small text-muted no-margin">设置这个服务器会被分配至的 Nest。</p>
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="pEggId">Egg</label>
                        <select name="egg_id" id="pEggId" class="form-control"></select>
                        <p class="small text-muted no-margin">选择一个会指定服务器应该被如何控制的Egg。</p>
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="pPackId">Data Pack</label>
                        <select name="pack_id" id="pPackId" class="form-control"></select>
                        <p class="small text-muted no-margin">选择一个会在服务器创建时自动安装到服务器的数据包。</p>
                    </div>
                    <div class="form-group col-xs-12">
                        <div class="checkbox checkbox-primary no-margin-bottom">
                            <input id="pSkipScripting" name="skip_scripts" type="checkbox" value="1" checked />
                            <label for="pSkipScripting" class="strong">跳过 Egg 安装脚本</label>
                        </div>
                        <p class="small text-muted no-margin">选择的 Egg 有一个绑定的安装脚本，这个脚本会在服务器安装时运行。如果你希望跳过这一步，请选中此选框。</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Docker 配置</h3>
                </div>
                <div class="box-body row">
                    <div class="form-group col-xs-12">
                        <label for="pDefaultContainer">Docker 镜像</label>
                        <input id="pDefaultContainer" name="image" value="{{ old('image') }}" class="form-control" />
                        <p class="small text-muted no-margin">这是默认会用于运行此服务器的Docker镜像。</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">启动配置</h3>
                </div>
                <div class="box-body row">
                    <div class="form-group col-xs-12">
                        <label for="pStartup">启动指令</label>
                        <input type="text" id="pStartup" value="{{ old('startup') }}" class="form-control" name="startup" />
                        <p class="small text-muted no-margin">这些数据变量可以用于启动指令：<code>@{{SERVER_MEMORY}}</code>, <code>@{{SERVER_IP}}</code>，以及 <code>@{{SERVER_PORT}}</code>。他们会分别被分配的内存、服务器IP以及服务器端口替换。</p>
                    </div>
                </div>
                <div class="box-header with-border" style="margin-top:-10px;">
                    <h3 class="box-title">服务变量</h3>
                </div>
                <div class="box-body row" id="appendVariablesTo"></div>
                <div class="box-footer">
                    {!! csrf_field() !!}
                    <input type="submit" class="btn btn-success pull-right" value="创建服务器" />
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('footer-scripts')
    @parent
    {!! Theme::js('vendor/lodash/lodash.js') !!}
    {!! Theme::js('js/admin/new-server.js') !!}
@endsection
