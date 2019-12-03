<!-- Modal -->
<div class="modal fade bd-example-modal-xl" id="formModal" role="dialog" aria-labelledby="formModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
        <div class="modal-header bg-light">
            <h5 class="modal-title" id="formModalTitle"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="form" class="form-horizontal r-separator" enctype="multipart/form-data" onkeydown="return event.key != 'Enter';">

                <!-- START OF FIRST INPUT GROUP -->
                <div>
                    <div class="card-body">
                        <table class="table table-striped" style="background:#fff;">
                            <thead>
                                <tr>
                                    <th>KODE KATALOG</th>
                                    <th>NAMA KATALOG</th>
<!--                                    <th>MERK</th>-->
                                    <th>FOTO</th>
                                    <th width="70" id="deleteTH"></th>
                                </tr>
                            </thead>
                            <tbody id="instrumentsForm"></tbody>
                        </table>
                    </div> 
                </div>
                <!-- END OF FIRST INPUT GROUP -->

                <div class="card-body">
                    <button id="addMoreForm" type="button" class="btn btn-info"><i class="fas fa-plus"></i> &nbsp; ADD MORE</button>
                </div>
                
        </div>
        <div class="modal-footer bg-light">
            <button type="submit" class="btn btn-info waves-effect waves-light">Save</button>
            <button type="reset" class="btn btn-secondary waves-effect waves-light">Clear</button>
            <button type="button" class="btn btn-dark waves-effect waves-light" data-dismiss="modal">Close</button>
        </div>
        </form>
        </div>
    </div>
</div>

<script>

    var merkOpt = '';

    function setDefaultFormModalTitle() {
        $('#formModalTitle').html('Tambah Katalog Baru');
        $('#deleteTH').show();
        $('#deleteTD').show();
        $('#addMoreForm').show();
    }

    function initializeInput(res) {
        res = JSON.parse(res)[0];
        
        $('#formModalTitle').html('Ubah Informasi Katalog <b>' + res.assetMasterName + '</b>');

        // create one line input field
        $('#instrumentsForm').html('');
        var addCount = 1;
        formHTML =  '<tr id="tr-'+addCount+'">'+
                        '<input type="hidden" name="instruments['+addCount+'][idAssetMaster]" id="idAssetMaster">'+
                        '<input type="hidden" name="instruments['+addCount+'][catCode]" id="catCode" value="<?=uriSegment(3)?>">'+
                        '<input type="hidden" name="instruments['+addCount+'][currentPict]" id="idPictMain">'+
                        '<td>'+
                            '<input type="text" class="form-control" name="instruments['+addCount+'][productCode]" id="productCode" placeholder="Kode Katalog" required>'+
                        '</td>'+
                        '<td>'+
                            '<input type="text" class="form-control" name="instruments['+addCount+'][assetMasterName]" id="assetMasterName" placeholder="Nama Instrument" required>'+
                        '</td>'+
                        // '<td>'+
                            // '<input type="text" class="form-control" name="instruments['+addCount+'][merk]" id="merk" placeholder="Merk" required>'+
						// 	'<select name="instruments['+addCount+'][merk]" id="instrumentsMerk_'+addCount+'" class="form-control select2" style="width:100%;"></select>'+
                        // '</td>'+
                        '<td>'+
                            '<input type="file" class="my-pond" name="instruments[pic]['+addCount+']" id="pic"/>'+
                        '</td>'+
                        '<td id="deleteTD">'+
                            '<button type="button" class="btn btn-danger" onClick="deleteInputRow('+addCount+')"><i class="mdi mdi-close"></i></button>';
                        '</td>'+
                    '</tr>';
        $('#instrumentsForm').append(formHTML);

        for(var key in res) {
            $('#form #'+key).val(res[key]);
        }
        $('#deleteTH').hide();
        $('#deleteTD').hide();
        $('#addMoreForm').hide();
    }

    $(document).ready(function() {
        var addCount = 1;
        formHTML =  '<tr id="tr-'+addCount+'">'+
                        '<input type="hidden" name="instruments['+addCount+'][idAssetMaster]" id="idAssetMaster">'+
                        '<input type="hidden" name="instruments['+addCount+'][catCode]" id="catCode" value="<?=uriSegment(3)?>">'+
                        '<input type="hidden" name="instruments['+addCount+'][currentPict]" id="idPictMain">'+
                        '<td>'+
                            '<input type="text" class="form-control" name="instruments['+addCount+'][productCode]" id="productCode" placeholder="Kode Katalog" required>'+
                        '</td>'+
                        '<td>'+
                            '<input type="text" class="form-control" name="instruments['+addCount+'][assetMasterName]" id="assetMasterName" placeholder="Nama Instrument" required>'+
                        '</td>'+
                        // '<td>'+
                            // '<input type="text" class="form-control" name="instruments['+addCount+'][merk]" id="merk" placeholder="Merk" required>'+
						// 	'<select name="instruments['+addCount+'][merk]" id="instrumentsMerk_'+addCount+'" class="form-control select2" style="width:100%;"></select>'+
                        // '</td>'+
                        '<td>'+
                            '<input type="file" class="my-pond" name="instruments[pic]['+addCount+']" id="pic"/>'+
                        '</td>'+
                        '<td id="deleteTD">'+
                            '<button type="button" class="btn btn-danger" onClick="deleteInputRow('+addCount+')"><i class="mdi mdi-close"></i></button>';
                        '</td>'+
                    '</tr>';
        $('#instrumentsForm').append(formHTML);

        if (addCount == 1) {
            // Default Option Merk
            $.ajax({
                'url': '<?= site_url('ajax/merk') ?>',
            }).then(res => {
                res = JSON.parse(res);

                var Opt = '';
                for (key in res) {
                    Opt += '<option value="' + res[key] + '">' + res[key] + '</option>';
                }
                $('select[name="instruments['+addCount+'][merk]"]').append(Opt);
                $('select[name="instruments['+addCount+'][merk]"]').select2();

                merkOpt = Opt;
            });
        }

        $('#addMoreForm').click(function() {
            addCount+=1;
            
            formHTML =  '<tr id="tr-'+addCount+'">'+
                            '<input type="hidden" name="instruments['+addCount+'][idAssetMaster]">'+
                            '<input type="hidden" name="instruments['+addCount+'][catCode]" id="catCode" value="<?=uriSegment(3)?>">'+
                            '<input type="text" name="instruments['+addCount+'][currentPict]" id="idPictMain" required>'+
                            '<td>'+
                                '<input type="text" class="form-control" name="instruments['+addCount+'][productCode]" placeholder="Kode Katalog" required>'+
                            '</td>'+
                            '<td>'+
                                '<input type="text" class="form-control" name="instruments['+addCount+'][assetMasterName]" placeholder="Nama Instrument" required>'+
                            '</td>'+
                            // '<td>'+
                                // '<input type="text" class="form-control" name="instruments['+addCount+'][merk]" placeholder="Merk" required>'+
							// 	'<select name="instruments['+addCount+'][merk]" id="instrumentsMerk_'+addCount+'" class="form-control select2" style="width:100%;"></select>'+
                            // '</td>'+
                            '<td>'+
                                '<input type="file" class="my-pond" name="instruments[pic]['+addCount+']"/>'+
                            '</td>'+
                            '<td>'+
                                '<button type="button" class="btn btn-danger" onClick="deleteInputRow('+addCount+')"><i class="mdi mdi-close"></i></button>';
                            '</td>'+
                        '</tr>';

            $('#instrumentsForm').append(formHTML);

            $('select[name="instruments['+addCount+'][merk]"]').append(merkOpt);
            $('select[name="instruments['+addCount+'][merk]"]').select2();
        });
    });

    function deleteInputRow(id) {
        $('#tr-'+id).remove();
    }

</script>
