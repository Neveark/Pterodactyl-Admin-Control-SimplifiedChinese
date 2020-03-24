<div class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.packs.new') }}" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">从模板安装包</h4>
                </div>
                <div class="modal-body">
                    <div class="well" style="margin-bottom:0">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="pEggIdModal" class="form-label">关联的 Egg:</label>
                                <select id="pEggIdModal" name="egg_id" class="form-control">
                                    @foreach($nests as $nest)
                                        <optgroup label="{{ $nest->name }}">
                                            @foreach($nest->eggs as $egg)
                                                <option value="{{ $egg->id }}">{{ $egg->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                <p class="text-muted small">此包关联的 Egg。只有分配了此 Egg 的服务器才能访问此包。</p>
                            </div>
                        </div>
                        <div class="row" style="margin-top:15px;">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label class="control-label">包压缩:</label>
                                        <input name="file_upload" type="file" accept=".zip,.json, application/json, application/zip" />
                                        <p class="text-muted"><small>这个文件应该是一个<code>.json</code>模板文件，或是一个<code>.zip</code>压缩包，包含了<code>archive.tar.gz</code>和<code>import.json</code>。<br /><br />此服务器目前已配置了以下限制：<code>upload_max_filesize={{ ini_get('upload_max_filesize') }}</code>和<code>post_max_size={{ ini_get('post_max_size') }}</code>。如果你的文件大于其中任意一个限制，请求将会失败。</small></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    {!! csrf_field() !!}
                    <button type="submit" name="action" value="from_template" class="btn btn-primary btn-sm">安装</button>
                    <button type="button" class="btn btn-default btn-sm pull-left" data-dismiss="modal">取消</button>
                </div>
            </form>
        </div>
    </div>
</div>
