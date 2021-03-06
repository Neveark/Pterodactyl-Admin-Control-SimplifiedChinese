@extends('layouts.admin')
@include('partials/admin.settings.nav', ['activeTab' => 'mail'])

@section('title')
    邮件设置
@endsection

@section('content-header')
    <h1>邮件设置<small>配置翼龙面板如何发送邮件。</small></h1>
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
                    <h3 class="box-title">电子邮件设置</h3>
                </div>
                @if($disabled)
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="alert alert-info no-margin-bottom">
                                此接口仅限于使用SMTP作为邮件驱动程序的实例。请使用<code>php artisan p:environment:mail</code>命令更新您的电子邮件设置，或者在您的环境文件中设置<code>MAIL_DRIVER = smtp</code>。
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <form>
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="control-label">SMTP 主机</label>
                                    <div>
                                        <input required type="text" class="form-control" name="mail:host" value="{{ old('mail:host', config('mail.host')) }}" />
                                        <p class="text-muted small">Enter the SMTP server address that mail should be sent through.</p>
                                    </div>
                                </div>
                                <div class="form-group col-md-2">
                                    <label class="control-label">SMTP 端口</label>
                                    <div>
                                        <input required type="number" class="form-control" name="mail:port" value="{{ old('mail:port', config('mail.port')) }}" />
                                        <p class="text-muted small">Enter the SMTP server port that mail should be sent through.</p>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="control-label">加密</label>
                                    <div>
                                        @php
                                            $encryption = old('mail:encryption', config('mail.encryption'));
                                        @endphp
                                        <select name="mail:encryption" class="form-control">
                                            <option value="" @if($encryption === '') selected @endif>None</option>
                                            <option value="tls" @if($encryption === 'tls') selected @endif>传输层安全 (TLS)</option>
                                            <option value="ssl" @if($encryption === 'ssl') selected @endif>安全链路层 (SSL)</option>
                                        </select>
                                        <p class="text-muted small">Select the type of encryption to use when sending mail.</p>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="control-label">用户名 <span class="field-optional"></span></label>
                                    <div>
                                        <input type="text" class="form-control" name="mail:username" value="{{ old('mail:username', config('mail.username')) }}" />
                                        <p class="text-muted small">The username to use when connecting to the SMTP server.</p>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="control-label">密码 <span class="field-optional"></span></label>
                                    <div>
                                        <input type="password" class="form-control" name="mail:password"/>
                                        <p class="text-muted small">The password to use in conjunction with the SMTP username. Leave blank to continue using the existing password. To set the password to an empty value enter <code>!e</code> into the field.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <hr />
                                <div class="form-group col-md-6">
                                    <label class="control-label">邮件来源</label>
                                    <div>
                                        <input required type="email" class="form-control" name="mail:from:address" value="{{ old('mail:from:address', config('mail.from.address')) }}" />
                                        <p class="text-muted small">Enter an email address that all outgoing emails will originate from.</p>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="control-label">作为邮件来源的名称 <span class="field-optional"></span></label>
                                    <div>
                                        <input type="text" class="form-control" name="mail:from:name" value="{{ old('mail:from:name', config('mail.from.name')) }}" />
                                        <p class="text-muted small">The name that emails should appear to come from.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            {{ csrf_field() }}
                            <div class="pull-right">
                                <button type="button" id="testButton" class="btn btn-sm btn-success">测试</button>
                                <button type="button" id="saveButton" class="btn btn-sm btn-primary">保存</button>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('footer-scripts')
    @parent
    
    <script>
        function saveSettings() {
            return $.ajax({
                method: 'PATCH',
                url: Router.route('admin.settings.mail'),
                contentType: 'application/json',
                data: JSON.stringify({
                    'mail:host': $('input[name="mail:host"]').val(),
                    'mail:port': $('input[name="mail:port"]').val(),
                    'mail:encryption': $('select[name="mail:encryption"]').val(),
                    'mail:username': $('input[name="mail:username"]').val(),
                    'mail:password': $('input[name="mail:password"]').val(),
                    'mail:from:address': $('input[name="mail:from:address"]').val(),
                    'mail:from:name': $('input[name="mail:from:name"]').val()
                }),
                headers: { 'X-CSRF-Token': $('input[name="_token"]').val() }
            }).fail(function (jqXHR) {
                showErrorDialog(jqXHR, 'save');
            });
        }

        function testSettings() {
            swal({
                type: 'info',
                title: '测试邮件设置',
                text: '点击"测试"来开始测试。',
                showCancelButton: true,
                confirmButtonText: '测试',
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            }, function () {
                $.ajax({
                    method: 'GET',
                    url: Router.route('admin.settings.mail.test'),
                    headers: { 'X-CSRF-Token': $('input[name="_token"]').val() }
                }).fail(function (jqXHR) {
                    showErrorDialog(jqXHR, 'test');
                }).done(function () {
                    swal({
                        title: '成功',
                        text: '测试信息已经成功发送。',
                        type: 'success'
                    });
                });
            });
        }

        function saveAndTestSettings() {
            saveSettings().done(testSettings);
        }

        function showErrorDialog(jqXHR, verb) {
            console.error(jqXHR);
            var errorText = '';
            if (!jqXHR.responseJSON) {
                errorText = jqXHR.responseText;
            } else if (jqXHR.responseJSON.error) {
                errorText = jqXHR.responseJSON.error;
            } else if (jqXHR.responseJSON.errors) {
                $.each(jqXHR.responseJSON.errors, function (i, v) {
                    if (v.detail) {
                        errorText += v.detail + ' ';
                    }
                });
            }

            swal({
                title: '哦豁!',
                text: '在尝试进行下列操作的时候发生了错误： ' + verb + ' 邮件设置：' + errorText,
                type: 'error'
            });
        }

        $(document).ready(function () {
            $('#testButton').on('click', saveAndTestSettings);
            $('#saveButton').on('click', function () {
                saveSettings().done(function () {
                    swal({
                        title: '成功',
                        text: '邮件设置已经成功并且队列工作者已经被重启以应用此更改。',
                        type: 'success'
                    });
                });
            });
        });
    </script>
@endsection
