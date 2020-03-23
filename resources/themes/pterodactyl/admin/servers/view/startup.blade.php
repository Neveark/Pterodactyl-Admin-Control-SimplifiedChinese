{{-- Pterodactyl - Panel --}}
{{-- Copyright (c) 2015 - 2017 Dane Everitt <dane@daneeveritt.com> --}}

{{-- This software is licensed under the terms of the MIT license. --}}
{{-- https://opensource.org/licenses/MIT --}}
@extends('layouts.admin')

@section('title')
    服务器 — {{ $server->name }}: 启动
@endsection

@section('content-header')
    <h1>{{ $server->name }}<small>控制服务器的启动命令以及变量。</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">管理</a></li>
        <li><a href="{{ route('admin.servers') }}">服务器</a></li>
        <li><a href="{{ route('admin.servers.view', $server->id) }}">{{ $server->name }}</a></li>
        <li class="active">启动</li>
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
                    <li class="active"><a href="{{ route('admin.servers.view.startup', $server->id) }}">启动</a></li>
                    <li><a href="{{ route('admin.servers.view.database', $server->id) }}">数据库</a></li>
                    <li><a href="{{ route('admin.servers.view.manage', $server->id) }}">管理</a></li>
                @endif
                <li class="tab-danger"><a href="{{ route('admin.servers.view.delete', $server->id) }}">删除</a></li>
                <li class="tab-success"><a href="{{ route('server.index', $server->uuidShort) }}"><i class="fa fa-external-link"></i></a></li>
            </ul>
        </div>
    </div>
</div>
<form action="{{ route('admin.servers.view.startup', $server->id) }}" method="POST">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">启动命令修改</h3>
                </div>
                <div class="box-body">
                    <label for="pStartup" class="form-label">启动命令</label>
                    <input id="pStartup" name="startup" class="form-control" type="text" value="{{ old('startup', $server->startup) }}" />
                    <p class="small text-muted">在此处编辑服务器的启动命令。 默认情况下，以下变量可用：<code>@{{SERVER_MEMORY}}</code>、 <code>@{{SERVER_IP}}</code>以及 <code>@{{SERVER_PORT}}</code>。</p>
                </div>
                <div class="box-body">
                    <label for="pDefaultStartupCommand" class="form-label">默认服务启动命令</label>
                    <input id="pDefaultStartupCommand" class="form-control" type="text" readonly />
                </div>
                <div class="box-footer">
                    {!! csrf_field() !!}
                    <button type="submit" class="btn btn-primary btn-sm pull-right">保存更改</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">服务配置</h3>
                </div>
                <div class="box-body row">
                    <div class="col-xs-12">
                        <p class="small text-danger">
                            更改以下任何值将导致服务器处理重新安装命令。服务器将停止，然后继续。
                            如果您要更换包，则现有数据<em>可能</em>会被覆盖。如果您希望服务脚本不运行，请确保选中底部的复选框。
                        </p>
                        <p class="small text-danger">
                            <strong>在许多情况下，这是毁灭性的操作。 该服务器将立即停止，以便执行此操作。</strong>
                        </p>
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="pNestId">Nest</label>
                        <select name="nest_id" id="pNestId" class="form-control">
                            @foreach($nests as $nest)
                                <option value="{{ $nest->id }}"
                                    @if($nest->id === $server->nest_id)
                                        selected
                                    @endif
                                >{{ $nest->name }}</option>
                            @endforeach
                        </select>
                        <p class="small text-muted no-margin">Select the Nest that this server will be grouped into.</p>
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="pEggId">Egg</label>
                        <select name="egg_id" id="pEggId" class="form-control"></select>
                        <p class="small text-muted no-margin">Select the Egg that will provide processing data for this server.</p>
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="pPackId">Data Pack</label>
                        <select name="pack_id" id="pPackId" class="form-control"></select>
                        <p class="small text-muted no-margin">Select a data pack to be automatically installed on this server when first created.</p>
                    </div>
                    <div class="form-group col-xs-12">
                        <div class="checkbox checkbox-primary no-margin-bottom">
                            <input id="pSkipScripting" name="skip_scripts" type="checkbox" value="1" @if($server->skip_scripts) checked @endif />
                            <label for="pSkipScripting" class="strong">Skip Egg Install Script</label>
                        </div>
                        <p class="small text-muted no-margin">If the selected Egg has an install script attached to it, the script will run during install after the pack is installed. If you would like to skip this step, check this box.</p>
                    </div>
                </div>
            </div>
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Docker Container Configuration</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label for="pDockerImage" class="control-label">Image</label>
                        <input type="text" name="docker_image" id="pDockerImage" value="{{ $server->image }}" class="form-control" />
                        <p class="text-muted small">The Docker image to use for this server. The default image for the selected egg is <code id="setDefaultImage"></code>.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row" id="appendVariablesTo"></div>
        </div>
    </div>
</form>
@endsection

@section('footer-scripts')
    @parent
    {!! Theme::js('vendor/lodash/lodash.js') !!}
    <script>
    $(document).ready(function () {
        $('#pPackId').select2({placeholder: 'Select a Service Pack'});
        $('#pEggId').select2({placeholder: 'Select a Nest Egg'}).on('change', function () {
            var selectedEgg = _.isNull($(this).val()) ? $(this).find('option').first().val() : $(this).val();
            var parentChain = _.get(Pterodactyl.nests, $("#pNestId").val());
            var objectChain = _.get(parentChain, 'eggs.' + selectedEgg);

            $('#setDefaultImage').html(_.get(objectChain, 'docker_image', 'undefined'));
            $('#pDockerImage').val(_.get(objectChain, 'docker_image', 'undefined'));
            if (objectChain.id === parseInt(Pterodactyl.server.egg_id)) {
                $('#pDockerImage').val(Pterodactyl.server.image);
            }

            if (!_.get(objectChain, 'startup', false)) {
                $('#pDefaultStartupCommand').val(_.get(parentChain, 'startup', 'ERROR: Startup Not Defined!'));
            } else {
                $('#pDefaultStartupCommand').val(_.get(objectChain, 'startup'));
            }

            $('#pPackId').html('').select2({
                data: [{id: '0', text: 'No Service Pack'}].concat(
                    $.map(_.get(objectChain, 'packs', []), function (item, i) {
                        return {
                            id: item.id,
                            text: item.name + ' (' + item.version + ')',
                        };
                    })
                ),
            });

            if (Pterodactyl.server.pack_id !== null) {
                $('#pPackId').val(Pterodactyl.server.pack_id);
            }

            $('#appendVariablesTo').html('');
            $.each(_.get(objectChain, 'variables', []), function (i, item) {
                var setValue = _.get(Pterodactyl.server_variables, item.env_variable, item.default_value);
                var isRequired = (item.required === 1) ? '<span class="label label-danger">Required</span> ' : '';
                var dataAppend = ' \
                    <div class="col-xs-12"> \
                        <div class="box"> \
                            <div class="box-header with-border"> \
                                <h3 class="box-title">' + isRequired + item.name + '</h3> \
                            </div> \
                            <div class="box-body"> \
                                <input name="environment[' + item.env_variable + ']" class="form-control" type="text" id="egg_variable_' + item.env_variable + '" /> \
                                <p class="no-margin small text-muted">' + item.description + '</p> \
                            </div> \
                            <div class="box-footer"> \
                                <p class="no-margin text-muted small"><strong>Startup Command Variable:</strong> <code>' + item.env_variable + '</code></p> \
                                <p class="no-margin text-muted small"><strong>Input Rules:</strong> <code>' + item.rules + '</code></p> \
                            </div> \
                        </div> \
                    </div>';
                $('#appendVariablesTo').append(dataAppend).find('#egg_variable_' + item.env_variable).val(setValue);
            });
        });

        $('#pNestId').select2({placeholder: 'Select a Nest'}).on('change', function () {
            $('#pEggId').html('').select2({
                data: $.map(_.get(Pterodactyl.nests, $(this).val() + '.eggs', []), function (item) {
                    return {
                        id: item.id,
                        text: item.name,
                    };
                }),
            });

            if (_.isObject(_.get(Pterodactyl.nests, $(this).val() + '.eggs.' + Pterodactyl.server.egg_id))) {
                $('#pEggId').val(Pterodactyl.server.egg_id);
            }

            $('#pEggId').change();
        }).change();
    });
    </script>
@endsection
