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
            <button type="submit" class="btn btn-info waves-effect waves-light">Start Import</button>
            <button type="button" class="btn btn-dark waves-effect waves-light" data-dismiss="modal">Cancel</button>
            &nbsp;
            <a target="_blank" href="<?=base_url('assets/format/Format Import Catalogue.xlsx')?>" class="btn btn-primary button">DOWNLOAD TEMPLATE</a>
            <button type="button" class="btn btn-info">INFO</button>
        </div>
        </form>
        </div>
    </div>
</div>

<script>

    function setDefaultImportModalTitle() {
        $('#importModalTitle').html('Import Data Katalog ' + '<?=instrumentCategory(uriSegment(3))?>');
        clearAll();
    }
    
    function clearAll() {
        $('input[name="file"]').val(null);

        $('#previewArea').hide();
        $('#previewNote').hide();
        $('#previewNote').html('');
    }

    // $(document).ready(function() {
    //     var submittedData;
    //     var rawData;

    //     $('#loading').hide();

    //     $('#previewArea').hide();
    //     $('#previewNote').hide();
    //     $('#previewNote').html('');

    //     $('#formImport').submit(function(e) {
    //         $('#loading').show();
            
    //         e.preventDefault();
    //         if ($('input[name=file]').val() != '') {
    //             $.ajax({
    //                 url: '<?=site_url(uriSegment(1).'/form')?>',
    //                 type: 'POST',
    //                 data: {'data': rawData}
    //             }).then(res => {
    //                 $('#loading').hide();
    //                 res = JSON.parse(res);

    //                 insertResponseStatus = res.insertResponse.httpStatus;
    //                 updateResponseStatus = res.updateResponse.httpStatus;

    //                 if (insertResponseStatus == '200' && updateResponseStatus == '200') {
    //                     Swal.fire('Success!', 'Data berhasil diimport!', 'success');
    //                 } else if (insertResponseStatus != '200' && updateResponseStatus != '200') {
    //                     Swal.fire('Error!', 'Data gagal diimport, silahkan coba lagi!', 'error');
    //                 } else {
    //                     Swal.fire('Warning!', 'Beberapa data gagal diimport, silahkan import kembali!', 'error');
    //                 }   

    //                 table.ajax.reload(null, false);
    //                 $('#importModal').modal('hide');
    //             });
    //         } else {
    //             return false;
    //         }
    //     });

    //     $('input[name=file]').change(function() {
    //         $('#loading').show();

    //         var file = $(this).val();
    //         var ext = file.split('.').pop();

    //         if (ext == 'xlsx') {
    //             event.preventDefault();

    //             var form = $('#formImport')[0];
    //             var data = new FormData(form);

    //             $.ajax({
    //                 url: '<?=site_url(uriSegment(1).'/importPreview?catCode='.uriSegment(3))?>',
    //                 type: 'POST',
    //                 enctype: 'multipart/form-data',
    //                 data: data,
    //                 processData: false,
    //                 contentType: false,
    //                 cache: false,
    //             }).then(res => {
    //                 $('#loading').hide();

    //                 var previewTable = '';
    //                 res = JSON.parse(res);

    //                 rawData         = res.raw;
    //                 submittedData   = res.data;
    //                 header          = res.header;

    //                 $('#previewArea').show();
    //                 $('#previewNote').show();
    //                 $('#previewNote').html(res.note);

    //                 var thead = '<thead>'+
    //                                 '<tr>';
    //                 for(var key in header) {
    //                     thead += '<th>'+header[key]+'</th>';
    //                 }
    //                 thead +=    '</tr>'+
    //                             '</thead>';

    //                 $('table#previewData').append(thead);

    //                 previewTable = $('table#previewData').DataTable({
    //                     data: submittedData
    //                 });

    //                 previewTable.draw();
    //             });
    //         } else {
    //             Swal.fire('File extension is not supported!');
    //             $('#previewArea').hide();
    //             $('#previewData').html('');
    //             $(this).val('');
    //             submittedData = '';
    //         }
    //     });
    // });

    $(document).ready(function() {
        var submittedData;
        var rawData;

        $('#loading').hide();

        $('#previewArea').hide();
        $('#previewNote').hide();
        $('#previewNote').html('');

        $('#formImport').submit(function(e) {
            $('#loading').show();
            $('#startImportButton').attr('disabled', true);
            
            e.preventDefault();
            if ($('input[name=file]').val() != '') {
                $.ajax({
                    url: '<?=site_url(uriSegment(1).'/form?catCode='.uriSegment(3))?>',
                    type: 'POST',
                    data: {'data': rawData}
                }).then(res => {
                    $('#loading').hide();

                    if (res) {
                        Swal.fire('Success!', 'Data berhasil diimport!', 'success');
                    }

                    table.ajax.reload(null, false);
                    $('#importModal').modal('hide');
                    $('#startImportButton').removeAttr('disabled', true);
                });
            } else {
                $('#startImportButton').removeAttr('disabled', true);
                return false;
            }
        });

        $('input[type=file]').click(function(){ // reset input, avoid same file reattached
            $(this).val(null);
        });
        $('input[name=file]').change(function() {
            // reset
            var tableInit = '<table id="previewData" class="table table-stripped table-bordered dataTable" style="background:#fff;width:150%;"></table>';
            $('table#previewData').remove();
            $('#previewData_wrapper').remove();
            $('#previewArea').append(tableInit);

            $('#loading').show();

            var file = $(this).val();
            var ext = file.split('.').pop();

            if (ext == 'xlsx') {
                event.preventDefault();

                var form = $('#formImport')[0];
                var data = new FormData(form);

                $.ajax({
                    "url": '<?=site_url(uriSegment(1).'/importPreview?catCode='.uriSegment(3))?>',
                    "type": 'POST',
                    "enctype": 'multipart/form-data',
                    "data": data,
                    "processData": false,
                    "contentType": false,
                    "cache": false,
                }).then(res => {
                    $('#loading').hide();
                    res = JSON.parse(res);

                    rawData         = res.raw;
                    submittedData   = res.data;
                    header          = res.header;

                    $('#previewArea').show();
                    $('#previewNote').show();
                    $('#previewNote').html(res.note);

                    var thead = '<thead>'+
                                    '<tr>';
                    for(var data in header) {
                        thead += '<th>'+header[data]+'</th>';
                    }
                    thead +=    '</tr>'+
                                '</thead>';

                    $('table#previewData').append(thead);

                    var previewTable = '';
                    previewTable = $('table#previewData').DataTable({
                        "sDom": "Rlfrtip",
                        "scrollX": true,
                        data: submittedData,
                        "createdRow": function(row, data, dataIndex){
                            if(data[5] != '-'){
                                $(row).addClass('background-alert');
                            }
                        },
                        "columns": [    
                            {'data':0},
                            {'data':1},
                            {'data':2},
                            {'data':3},
                            {'data':4},
                            {'data':5},
                        ],
                        "columnDefs": [
                            {
                                'width' : '25%',
                                'targets' : [1, 2]
                            },
                            {
                                'width' : '25%',
                                'targets' : [5]
                            }
                        ]
                    });
                    
                    previewTable.draw();
                });
            } else {
                Swal.fire('File extension is not supported!');
                $('#previewArea').hide();
                $('#previewData').html('');
                $(this).val('');
                submittedData = '';
            }

        });
    });

</script>