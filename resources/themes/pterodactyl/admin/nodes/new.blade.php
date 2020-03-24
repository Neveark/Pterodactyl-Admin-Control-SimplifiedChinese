{{-- Pterodactyl - Panel --}}
{{-- Copyright (c) 2015 - 2017 Dane Everitt <dane@daneeveritt.com> --}}

{{-- This software is licensed under the terms of the MIT license. --}}
{{-- https://opensource.org/licenses/MIT --}}
@extends('layouts.admin')

@section('title')
    节点 &rarr; 创建
@endsection

@section('content-header')
    <h1>新节点<small>创建一个新的本地或远程节点以用于安装服务器。</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">管理</a></li>
        <li><a href="{{ route('admin.nodes') }}">节点</a></li>
        <li class="active">新建</li>
    </ol>
@endsection

@section('content')
<form action="{{ route('admin.nodes.new') }}" method="POST">
    <div class="row">
        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">基本信息</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label for="pName" class="form-label">名称</label>
                        <input type="text" name="name" id="pName" class="form-control" value="{{ old('name') }}"/>
                        <p class="text-muted small">字符限制：<code>a-zA-Z0-9_.-</code> 以及 <code>[空格]</code> (最小1，最大100字节)。</p>
                    </div>
                    <div class="form-group">
                        <label for="pDescription" class="form-label">描述</label>
                        <textarea name="description" id="pDescription" rows="4" class="form-control">{{ old('description') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="pLocationId" class="form-label">地域</label>
                        <select name="location_id" id="pLocationId">
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ $location->id != old('location_id') ?: 'selected' }}>{{ $location->short }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">节点可见性</label>
                        <div>
                            <div class="radio radio-success radio-inline">

                                <input type="radio" id="pPublicTrue" value="1" name="public" checked>
                                <label for="pPublicTrue"> 公开 </label>
                            </div>
                            <div class="radio radio-danger radio-inline">
                                <input type="radio" id="pPublicFalse" value="0" name="public">
                                <label for="pPublicFalse"> 私密 </label>
                            </div>
                        </div>
                        <p class="text-muted small">通过将节点设置为<code>private</code>你将会禁用这个节点的自动部署功能。
                    </div>
                    <div class="form-group">
                        <label for="pFQDN" class="form-label">FQDN</label>
                        <input type="text" name="fqdn" id="pFQDN" class="form-control" value="{{ old('fqdn') }}"/>
                        <p class="text-muted small">请输入用于连接到守护进程的FQDN（例如<code>node.example.com</code>）。您<em>只能</em>在不使用SSL时使用IP地址。</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">通过SSL通信</label>
                        <div>
                            <div class="radio radio-success radio-inline">
                                <input type="radio" id="pSSLTrue" value="https" name="scheme" checked>
                                <label for="pSSLTrue"> 使用SSL连接</label>
                            </div>
                            <div class="radio radio-danger radio-inline">
                                <input type="radio" id="pSSLFalse" value="http" name="scheme" @if(request()->isSecure()) disabled @endif>
                                <label for="pSSLFalse"> 使用HTTP连接</label>
                            </div>
                        </div>
                        @if(request()->isSecure())
                            <p class="text-danger small">您的面板当前配置为使用安全连接。为了使浏览器连接到您的节点，它<strong>必须</strong>使用SSL连接。</p>
                        @else
                            <p class="text-muted small">在大多数情况下，您应该选择使用SSL连接。如果您希望使用IP地址或根本不想使用SSL，请选择HTTP连接。</p>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="form-label">透过代理</label>
                        <div>
                            <div class="radio radio-success radio-inline">
                                <input type="radio" id="pProxyFalse" value="0" name="behind_proxy" checked>
                                <label for="pProxyFalse"> 不透过代理 </label>
                            </div>
                            <div class="radio radio-info radio-inline">
                                <input type="radio" id="pProxyTrue" value="1" name="behind_proxy">
                                <label for="pProxyTrue"> 透过代理 </label>
                            </div>
                        </div>
                        <p class="text-muted small">如果您在诸如CloudFlare之类的代理后面运行守护程序，请选择此项以使守护程序跳过引导时查找证书的操作。</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">配置</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="pDaemonBase" class="form-label">守护进程服务器文件目录</label>
                            <input type="text" name="daemonBase" id="pDaemonBase" class="form-control" value="/srv/daemon-data" />
                            <p class="text-muted small">输入用于存储服务器文件的目录。<strong>如果您使用OVH，则应检查分区结构。您可能需要使用<code>/home/daemon-data</code>来确保拥有足够的空间。</strong></p>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="pMemory" class="form-label">总内存</label>
                            <div class="input-group">
                                <input type="text" name="memory" data-multiplicator="true" class="form-control" id="pMemory" value="{{ old('memory') }}"/>
                                <span class="input-group-addon">MB</span>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="pMemoryOverallocate" class="form-label">内存过量分配</label>
                            <div class="input-group">
                                <input type="text" name="memory_overallocate" class="form-control" id="pMemoryOverallocate" value="{{ old('memory_overallocate') }}"/>
                                <span class="input-group-addon">%</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <p class="text-muted small">输入可用于新服务器的内存总量。如果要允许内存过度分配，请输入要允许的百分比。要禁用检查是否过度分配，请在字段中输入<code>-1</code>。如果输入<code>0</code>将使节点超过限制时阻止创建新服务器。</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="pDisk" class="form-label">总磁盘空间</label>
                            <div class="input-group">
                                <input type="text" name="disk" data-multiplicator="true" class="form-control" id="pDisk" value="{{ old('disk') }}"/>
                                <span class="input-group-addon">MB</span>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="pDiskOverallocate" class="form-label">磁盘空间过量分配</label>
                            <div class="input-group">
                                <input type="text" name="disk_overallocate" class="form-control" id="pDiskOverallocate" value="{{ old('disk_overallocate') }}"/>
                                <span class="input-group-addon">%</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <p class="text-muted small">输入可用于新服务器的磁盘空间总量。如果要允许磁盘空间过度分配，请输入要允许的百分比。要禁用检查是否过度分配，请在字段中输入<code>-1</code>。如果输入<code>0</code>将使节点超过限制时阻止创建新服务器。</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="pDaemonListen" class="form-label">守护进程端口</label>
                            <input type="text" name="daemonListen" class="form-control" id="pDaemonListen" value="8080" />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="pDaemonSFTP" class="form-label">守护进程SFTP端口</label>
                            <input type="text" name="daemonSFTP" class="form-control" id="pDaemonSFTP" value="2022" />
                        </div>
                        <div class="col-md-12">
                            <p class="text-muted small">守护程序运行其自己的SFTP管理容器，并且不在主物理服务器上使用SSHd进程。<Strong>不要使用为物理服务器的SSH进程分配的端口。</strong>如果要在CloudFlare&reg;后面运行守护程序，则应将守护程序端口设置为<code>8443</code>，以允许通过SSL进行WebSocket代理。</p>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    {!! csrf_field() !!}
                    <button type="submit" class="btn btn-success pull-right">创建节点</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('footer-scripts')
    @parent
    <script>
        $('#pLocationId').select2();
    </script>
@endsection
