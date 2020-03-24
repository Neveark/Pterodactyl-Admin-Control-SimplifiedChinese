@extends('layouts.admin')
@include('partials/admin.settings.nav', ['activeTab' => 'basic'])

@section('title')
    设置
@endsection

@section('content-header')
    <h1>面板设置<small>用你喜欢的方式配置翼龙面板。</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">管理</a></li>
        <li class="active">设置</li>
    </ol>
@endsection

@section('content')
    @yield('settings::nav')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">面板设置</h3>
                </div>
                <form action="{{ route('admin.settings') }}" method="POST">
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label class="control-label">公司名称</label>
                                <div>
                                    <input type="text" class="form-control" name="app:name" value="{{ old('app:name', config('app.name')) }}" />
                                    <p class="text-muted"><small>这是整个面板使用的名称，以及给客户发送的邮件当中的面板名称。</small></p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">强制两步验证</label>
                                <div>
                                    <div class="btn-group" data-toggle="buttons">
                                        @php
                                            $level = old('pterodactyl:auth:2fa_required', config('pterodactyl.auth.2fa_required'));
                                        @endphp
                                        <label class="btn btn-primary @if ($level == 0) active @endif">
                                            <input type="radio" name="pterodactyl:auth:2fa_required" autocomplete="off" value="0" @if ($level == 0) checked @endif> 不强制
                                        </label>
                                        <label class="btn btn-primary @if ($level == 1) active @endif">
                                            <input type="radio" name="pterodactyl:auth:2fa_required" autocomplete="off" value="1" @if ($level == 1) checked @endif> 仅限管理
                                        </label>
                                        <label class="btn btn-primary @if ($level == 2) active @endif">
                                            <input type="radio" name="pterodactyl:auth:2fa_required" autocomplete="off" value="2" @if ($level == 2) checked @endif> 所有用户
                                        </label>
                                    </div>
                                    <p class="text-muted"><small>If enabled, any account falling into the selected grouping will be required to have 2-Factor authentication enabled to use the Panel.</small></p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">默认语言</label>
                                <div>
                                    <select name="app:locale" class="form-control">
                                        @foreach($languages as $key => $value)
                                            <option value="{{ $key }}" @if(config('app.locale') === $key) selected @endif>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-muted"><small>渲染UI组件的时候使用的默认语言。</small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        {!! csrf_field() !!}
                        <button type="submit" name="_method" value="PATCH" class="btn btn-sm btn-primary pull-right">保存</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
