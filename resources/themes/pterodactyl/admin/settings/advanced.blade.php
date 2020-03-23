@extends('layouts.admin')
@include('partials/admin.settings.nav', ['activeTab' => 'advanced'])

@section('title')
    Advanced Settings
@endsection

@section('content-header')
    <h1>高级设置<small>配置翼龙面板的高级设置。</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">管理</a></li>
        <li class="active">设置</li>
    </ol>
@endsection

@section('content')
    @yield('settings::nav')
    <div class="row">
        <div class="col-xs-12">
            <form action="" method="POST">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">reCAPTCHA</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label class="control-label">状态</label>
                                <div>
                                    <select class="form-control" name="recaptcha:enabled">
                                        <option value="true">已启用</option>
                                        <option value="false" @if(old('recaptcha:enabled', config('recaptcha.enabled')) == '0') selected @endif>已禁用</option>
                                    </select>
                                    <p class="text-muted small">If enabled, login forms and password reset forms will do a silent captcha check and display a visible captcha if needed.</p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">站点密钥</label>
                                <div>
                                    <input type="text" required class="form-control" name="recaptcha:website_key" value="{{ old('recaptcha:website_key', config('recaptcha.website_key')) }}">
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">站点私钥</label>
                                <div>
                                    <input type="text" required class="form-control" name="recaptcha:secret_key" value="{{ old('recaptcha:secret_key', config('recaptcha.secret_key')) }}">
                                    <p class="text-muted small">Used for communication between your site and Google. Be sure to keep it a secret.</p>
                                </div>
                            </div>
                        </div>
                        @if($showRecaptchaWarning)
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="alert alert-warning no-margin">
                                        你正在使用随面板附带的reCAPTCHA密钥。For improved security it is recommended to <a href="https://www.google.com/recaptcha/admin">generate new invisible reCAPTCHA keys</a> that tied specifically to your website.
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">HTTP连接</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="control-label">连接超时</label>
                                <div>
                                    <input type="number" required class="form-control" name="pterodactyl:guzzle:connect_timeout" value="{{ old('pterodactyl:guzzle:connect_timeout', config('pterodactyl.guzzle.connect_timeout')) }}">
                                    <p class="text-muted small">The amount of time in seconds to wait for a connection to be opened before throwing an error.</p>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">请求超时</label>
                                <div>
                                    <input type="number" required class="form-control" name="pterodactyl:guzzle:timeout" value="{{ old('pterodactyl:guzzle:timeout', config('pterodactyl.guzzle.timeout')) }}">
                                    <p class="text-muted small">The amount of time in seconds to wait for a request to be completed before throwing an error.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">控制台</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="control-label">消息数量</label>
                                <div>
                                    <input type="number" required class="form-control" name="pterodactyl:console:count" value="{{ old('pterodactyl:console:count', config('pterodactyl.console.count')) }}">
                                    <p class="text-muted small">The number of messages to be pushed to the console per frequency tick.</p>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">频率刻</label>
                                <div>
                                    <input type="number" required class="form-control" name="pterodactyl:console:frequency" value="{{ old('pterodactyl:console:frequency', config('pterodactyl.console.frequency')) }}">
                                    <p class="text-muted small">The amount of time in milliseconds between each console message sending tick.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box box-primary">
                    <div class="box-footer">
                        {{ csrf_field() }}
                        <button type="submit" name="_method" value="PATCH" class="btn btn-sm btn-primary pull-right">保存</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
