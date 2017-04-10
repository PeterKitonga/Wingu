<div class="modal fade" id="modal-import-dat" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Please Add File</h4>
            </div>
            <form action="" id="import-dat-form" method="post" enctype="multipart/form-data">
                <input name="_token" value="{{ csrf_token() }}" type="hidden">
                <div class="modal-body import-dat-body">
                    <div class="input-group">
                        <label class="input-group-btn">
                                <span class="btn btn-default">
                                    Browse… <input type="file" style="display: none;" name="dat">
                                </span>
                        </label>
                        <input type="text" class="form-control import-read" readonly="">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-default">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->