<div class="table-responsive">
    <table id="example" class="table table-striped table-bordered border display" style="width:<?=(array_sum($width) < 100 ? '100' : array_sum($width))?>%">
        <thead id="main_thead">
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
            "scrollY": "300px",
            "scrollCollapse": true,
            "aLengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
            "pageLength": 50,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url" : "<?=site_url(uriSegment(1).'/data?catCode='.uriSegment(3))?>",    
                'method': 'POST',
                'data': function(d) {
                    return $.extend(d, filters);
                },
            },
            'drawCallback': function(res) {
                allID = res.json.allID;
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
                {
                    "render": function (data, type, row) {
                        return '<a href="#" onClick="detailButton('+row[0]+')"><?=uriSegment(3)?>-'+row[0]+'</a>';
                    }, 
                    "targets": <?=$totalUnusedColumn + 1?>,
                }
            ],
            'select': {
                'style': 'multi'
            },
            "order": [[3, 'asc']],
            "oLanguage": {
                "sSearch": "Quick Search",
                "sProcessing": '<image style="width:150px" src="http://superstorefinder.net/support/wp-content/uploads/2018/01/blue_loading.gif">',
            }
        });
    });
</script>
