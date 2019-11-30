<?php
    if (uriSegment(3) == 'MIP')
        $templateName = 'Format Import Instrument Piece';
    else
        $templateName = 'Format Import Set';
?>

<!-- Modal -->
<div class="modal fade bd-example-modal-xl" id="importModal" role="dialog" aria-labelledby="formModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
        <div class="modal-header bg-light">
            <h5 class="modal-title" id="importModalTitle"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="formImport" class="form-horizontal r-separator" enctype="multipart/form-data" onkeydown="return event.key != 'Enter';">
                <!-- START OF FIRST INPUT GROUP -->
                <div>
                    <div class="card-body">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">.xlsx File</span>
                            </div>
                            <div class="custom-file">
                                <input type="file" name="file" class="custom-file-input" id="inputGroupFile01">
                                <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                            </div>
                        </div>
                    </div>

                    <div id="loading" style="text-align:center">
                        <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <br>
                        <h6><b>Please Wait</b></h6>
                        <br><br>
                    </div>

                    <div id="previewArea" class="card-body" style="margin-top:80px;">
                        <div id="previewNote" class="alert alert-warning" style="font-weight:bold;margin-bottom: 30px;"></div>
                        <div>
                            <h5><b>PREVIEW DATA : </b></h5>
                            <br>
                        </div>
                        <table id="previewData" class="table table-stripped table-bordered" style="background:#fff;width:380%;"></table>
                    </div>
                </div>
                <!-- END OF FIRST INPUT GROUP -->
        </div>
        <div class="modal-footer bg-light">
            <button id="startImportButton" type="submit" class="btn btn-info waves-effect waves-light">Start Import</button>
            <button type="button" class="btn btn-dark waves-effect waves-light" data-dismiss="modal">Cancel</button>
            &nbsp;
            <a href="<?=base_url('assets/format/import/'.$templateName.'.xlsx')?>" class="btn btn-primary button">DOWNLOAD TEMPLATE</a>
            <button type="button" class="btn btn-info">INFO</button>
        </div>
        </form>
        </div>
    </div>
</div>

<script>

    function setDefaultImportModalTitle () {
        $('#importModalTitle').html('Import Asset');
        clearAll();
    }

    function clearAll() {
        $('input[name="file"]').val(null);

        $('#previewArea').hide();
        $('#previewNote').hide();
        $('#previewNote').html('');
    }

</script>
<?php loadView($modulePath.'/import_'.uriSegment(3)) ?>