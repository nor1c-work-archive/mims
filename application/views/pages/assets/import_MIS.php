<script>
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
            var tableInit = '<table id="previewData" class="table table-stripped table-bordered dataTable" style="background:#fff;width:320%;"></table>';
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

                $('table#previewData').find('tbody').off('click', 'tr td.details-control');

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
                    submittedData   = res.dataSet;
                    pieceData       = res.dataPiece;
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
                            if(data[16] != '-'){
                                $(row).addClass('background-alert');
                            }
                        },
                        "columns": [                
                            {
                                "className" : 'details-control',
                                "orderable" : false,
                                "data" : null,
                                "defaultContent" : ''
                            },
                            {'data':1},
                            {'data':2},
                            {'data':3},
                            {'data':4},
                            {'data':5},
                            {'data':6},
                            {'data':7},
                            {'data':8},
                            {'data':9},
                            {'data':10},
                            {'data':11},
                            {'data':12},
                            {'data':13},
                            {'data':14},
                            {'data':15},
                            {'data':16},
                        ],
                        "columnDefs": [
                            {
                                'width' : '5%',
                                'targets' : 0
                            },
                            {
                                'width' : '15%',
                                'targets' : [6, 15]
                            },
                            {
                                'width' : '10%',
                                'targets' : 14
                            }
                        ]
                    });
                    
                    previewTable.draw();

                    var tbody = [];
                    for (var data in pieceData) {
                        if (pieceData[data][16] != '-') {
                            tbody[pieceData[data][0]] += '<tr style="background-color:#fff2f3">';
                        } else {
                            tbody[pieceData[data][0]] += '<tr>';
                        }
                        for (var data2 in pieceData[data]) {
                            if (data2 != 0) {
                                tbody[pieceData[data][0]] += '<td>'+(pieceData[data][data2] != null ? pieceData[data][data2] : '-')+'</td>';
                            }
                        }
                        tbody[pieceData[data][0]] += '</tr>';
                    }

                    $('table#previewData tbody').on('click', 'td.details-control', function () {
                        var tr = $(this).closest('tr');
                        var row = previewTable.row(tr);
                            
                        if (row.child.isShown()) {
                            row.child.hide();
                            tr.removeClass('shown');
                        } else {
                            row.child(format(row.data())).show();
                            tr.addClass('shown');
                        }
                    });

                    function format (d) {
                        return '<div style="width:100%;background-color:#fff;text-align:center;padding:0px 5px 5px 5px;margin-top:-2px;"><i class="fas fa-caret-up" style="color:#000;padding:0;border-bottom:solid 1px #ccc;width:100%;"></i><br>'+
                            '<table class="table table-bordered table-child" cellspacing="0" border="0" style="text-align:left;width:100%;background:#f4f4f4;">'+
                            '<thead>'+
                                '<tr style="background-color:#ececec">'+
                                    '<th>KODE SISTEM</th>'+
                                    '<th>KODE SISTEM BOX</th>'+
                                    '<th>KODE KATALOG BOX</th>'+
                                    '<th>KATEGORI INSTRUMENT</th>'+
                                    '<th>KODE KATALOG</th>'+
                                    '<th width="15%">NAMA ALIAS ASET</th>'+
                                    '<th>KONDISI</th>'+
                                    '<th>STATUS</th>'+
                                    '<th>LEVEL RESIKO</th>'+
                                    '<th>KEPEMILIKAN</th>'+
                                    '<th>LOKASI</th>'+
                                    '<th>HARGA BELI</th>'+
                                    '<th>TANGGAL PEROLEHAN</th>'+
                                    '<th>SUPPLIER</th>'+
                                    '<th>KETERANGAN</th>'+
                                    '<th width="8%">MARK</th>'+
                                '</tr>'+
                            '</thead>'+
                            '<tbody>'+
                                tbody[d[0]].replace('undefined', '')
                            '</tbody>'+
                        '</table>';
                    }
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