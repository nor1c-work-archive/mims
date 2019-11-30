<script>
    var addCount = 1;
</script>

<!-- Modal -->
<div class="modal fade bd-example-modal-xl" id="formModal" role="dialog" aria-labelledby="formModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document" style="max-width:85%">
        <div class="modal-content">
        <div class="modal-header bg-light">
            <h5 class="modal-title" id="formModalTitle"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="form" class="form-horizontal r-separator" onkeydown="return event.key != 'Enter';">
            <?php loadView($modulePath.'/form_'.uriSegment(3))?>
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

    var deletedPieces = [];

    function setDefaultFormModalTitle() {
        $('#formModalTitle').html('Tambah Asset');
        $('#deleteTH').show();
        $('#deleteTD').show();
        $('#addMoreForm').show();
        
        <?php if (uriSegment(3) == env('C_SET')) { ?>
            $('#assetCodeTH').hide();
            $('#priceTH').hide();
            $('#procureDateTH').hide();
        <?php } ?>

        $('.addableInstruments').remove();

        $('input[name=isEdit]').val('0');
        $('#systemCodeDiv').hide();
    }

    function initializeInput(res) {
        res = JSON.parse(res);

        $('#deleteTH').hide();
        $('#deleteTD').hide();

        initializeInputAdv(res);
    }

    $(document).ready(function() {

        formHTML =  '<tr id="tr-'+addCount+'" class="addableInstruments">'+
                        '<input type="hidden" name="instruments['+addCount+'][idAssetMaster]" id="idAssetMaster">'+
                        '<input type="hidden" name="instruments['+addCount+'][catCode]" id="catCode" value="MIP">'+
                        '<td>'+
                            '<select name="instruments['+addCount+'][idAssetMaster]" class="form-control select2 instrumentSelector" style="width:100%;"></select>'+
                        '</td>'+
                        '<td>'+
                            '<input type="number" class="form-control" name="instruments['+addCount+'][quantity]" id="quantity" placeholder="Quantity" value="1" min="1">'+
                        '</td>'+
                        '<td id="deleteTD">'+
                            '<button type="button" class="btn btn-danger" onClick="deleteInputRow('+addCount+')"><i class="mdi mdi-close"></i></button>';
                        '</td>'+
                    '</tr>';
        $('#instrumentsForm').append(formHTML);
        $('.assetCodeTD').hide();

        initializeInsSelect();

        $('.setMasterSelector').select2({
            minimumInputLength: 3,
            allowClear: true,
            placeholder: 'Dapat dicari menggunakan kode katalog ataupun nama katalog.',
            ajax: {
                dataType: 'json',
                url: '<?=site_url('master/data')?>',
                delay: 800,
                data: function(params) {
                    return {
                        query: 'keyword='+params.term+'&catCode=MIS&column=productCode|assetMasterName',
                    }
                },
                processResults: function (data, page) {
                    data = data.data;

                    var options = [];
                    for (var list in data) {
                        options = [{
                            'id' : data[list][0],
                            'text' : data[list][2] + ' | ' + data[list][3]
                        }];
                    }

                    return {
                        results: options,
                    };
                },
            }
        }).on('select2:select', function (evt) {
            var data = $(".instrumentSelector option:selected").val();
        });

        $('.setMasterSelector').change(function() {
            $('.assetName').val($(this).text().split('|')[1]);
        });

    });

    function initializeInsSelect() {
        // set instrument select option
        $('.instrumentSelector').select2({
            minimumInputLength: 3,
            allowClear: true,
            placeholder: 'Katalog Instrument',
            ajax: {
                dataType: 'json',
                url: '<?=site_url('master/data')?>',
                delay: 800,
                data: function(params) {
                    return {
                        query: 'keyword='+params.term+'&catCode=MIP&column=productCode|assetMasterName',
                    }
                },
                processResults: function (data, page) {
                    data = data.data;

                    var options = [];
                    for (var list in data) {
                        options = [{
                            'id' : data[list][0],
                            'text' : data[list][2] + ' | ' + data[list][3] + ', ' + data[list][4]
                        }];
                    }

                    return {
                        results: options,
                    };
                },
            }
        }).on('select2:select', function (evt) {
            var data = $(".instrumentSelector option:selected").val();
        });

        // set condition select option
		var conditionEnv = '<?=env('KONDISI')?>'.split(',');
		var conditionOpt = '';
		for (var key in conditionEnv) {
			conditionOpt += '<option value="'+conditionEnv[key].split('|')[0]+'">'+(conditionEnv[key].split('|')[1] ? conditionEnv[key].split('|')[1] : conditionEnv[key])+'</option>';
		}
		$('.selectCondition').append(conditionOpt).select2({minimumResultsForSearch: -1});

		// set status select option
		var statusEnv = '<?=env('STATUS')?>'.split(',');
		var statusOpt = '';
		for (var key in statusEnv) {
			statusOpt += '<option value="'+statusEnv[key].split('|')[0]+'">'+(statusEnv[key].split('|')[1] ? statusEnv[key].split('|')[1] : statusEnv[key])+'</option>';
		}
		$('.selectStatus').append(statusOpt).select2({minimumResultsForSearch: -1});
    }
    
    function deleteInputRow(id, idAsset = null) {
        $('#tr-'+id).remove();

        if (idAsset) {
            deletedPieces.push(idAsset);
            
            var deletedPieceInput = '';
            for (var key in deletedPieces) {
                deletedPieceInput += '<input type="hidden" class="deletedPiece" name="deletedPieces['+key+']" value="'+deletedPieces[key]+'">';
            }
            $('.deletedPiece').remove();
            $('#instrumentsForm').append(deletedPieceInput);
        }
    }

</script>
