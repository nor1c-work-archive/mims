<?php $catCode = uriSegment(3); ?>
<!-- <div>
    <div class="custom-control custom-checkbox mr-sm-8 mb-8" style="float:right;">
        <input type="checkbox" class="custom-control-input" id="nonActive" name="chkStatus[]" value="Non-Active">
        <label class="custom-control-label" for="nonActive">Non-Active</label>
    </div>
    <div class="custom-control custom-checkbox mr-sm-8 mb-8" style="float:right;">
        <input type="checkbox" class="custom-control-input" id="active" name="chkStatus[]" value="Active" checked>
        <label class="custom-control-label" for="active">Active</label>
    </div>
</div>
<br><br> -->
<div class="table-responsive">
    <table id="example" class="table table-striped table-bordered border display" style="width:<?=(array_sum($width) < 100 ? '100' : array_sum($width))?>%">
        <thead>
            <tr>
                <th></th>
                <?php if ($totalUnusedColumn == '3') echo "<th></th>"; ?>
                <th>NO</th>
                <?php
                    foreach ($columns as $columnKey => $columnAliasing) {
                        echo "<th>".$columnAliasing."</th>";
                    }
                ?>
            </tr>
        </thead>
    </table>
</div>

<script>
    $(document).ready(function() {
        table = $('#example').DataTable({
            "sDom": "Rlfrtip",
            "scrollX": true,
            "scrollY": "400px",
            "scrollCollapse": true,
            "processing": true,
            "serverSide": true,
            "pageLength": 50,
            "ajax": {
                "url" : "<?=site_url(uriSegment(1).'/data?catCode='.$catCode)?>",
                'method': 'POST',
                'data': function(d) {
                    return $.extend(d, filters)
                },
            },
            "deferRender": true,
            "columns": [
                {},
                <?php if ($totalUnusedColumn == '3') { ?>
                    {
                        "className": 'details-control',
                        "data": null,
                        "orderable": false,
                        "defaultContent": ''
                    },
                <?php } ?>
                { 
                    "className": 'datatables-number',
                    "data": null,
                    "orderable": false,
                    "defaultContent": '',
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                <?php 
                    $key = 0;
                    foreach ($columns as $columnKey => $columnAliasing) { 
                ?>
                    {
                        "className": '<?=$key == 0 ? 'idValue' : '' ?>',
                        "data": <?=$key?>,
                    },
                <?php $key++; } ?>
            ],
            "columnDefs": [
                <?php foreach ($width as $key => $value) { ?>
                    {
                        "width": <?=$value?>+'%',
                        "targets": <?=$totalUnusedColumn+$key?>
                    },
                <?php } ?>
                {
                    "width": "5%", 
                    "targets": 0,
                    "checkboxes": {
                        "selectRow": true
                    }
                },
                {
                    "width": "5%", 
                    "targets": 1,
                },
                <?php if ($totalUnusedColumn == '3') { ?>
                    {
                        "width": "5%", 
                        "targets": 2
                    },
                <?php } ?>
                {
                    "visible": false,
                    "targets": <?=$totalUnusedColumn?>,
                },
                <?php 
                $key = 0;
                foreach ($columns as $colKey => $colValue) { ?>
                    {
                        "render": function(data, type, row) {
                            <?php if ($colKey == 'assetParent') { ?>
                                var kode = '<?=$catCode?>';
                                return '<a href="#" onClick="detailButton('+row[0]+')"><?=$catCode?>-'+(kode == 'MIP' ? /*(row[1] ? row[1]+'-' : '')*/ '' : '')+ row[0]+'</a>';
                            <?php } else if ($colKey == 'propAdmin.priceBuy') { ?>
                                return (row[<?=$key?>] ? "Rp " + formatRupiah(row[<?=$key?>].toString()) : '-');
                            <?php } else if ($colKey == 'propAdmin.procureDate') { ?>
                                return (row[<?=$key?>] ? moment(row[<?=$key?>]).locale('id').format('dddd, DD/MM/Y') : '-');
                            <?php } else { ?>
                                return row[<?=$key?>] ? row[<?=$key?>] : '-';
                            <?php } ?>
                        },
                        "targets": <?=$totalUnusedColumn+$key?>,
                    },
                <?php $key++; } ?>
            ],
            'select': {
                'style': 'multi',
            },
            "order": [[3, 'asc']],
            "oLanguage": {
                "sSearch": "Quick Search",
                "sProcessing": '<image style="width:150px" src="http://superstorefinder.net/support/wp-content/uploads/2018/01/blue_loading.gif">',
            }
        });
    });
</script>
