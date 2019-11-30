<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item nav-item-detail"> <a class="nav-link active" data-toggle="tab" href="#information" role="tab"><span class="hidden-sm-up"></span> <span class="hidden-xs-down">Informasi Dasar</span></a> </li>
    <li class="nav-item nav-item-detail"> <a class="nav-link" data-toggle="tab" href="#administrative" role="tab"><span class="hidden-sm-up"></span> <span class="hidden-xs-down">Administratif</span></a> </li>
    <li class="nav-item nav-item-detail"> <a class="nav-link" data-toggle="tab" href="#set_instruments" role="tab"><span class="hidden-sm-up"></span> <span class="hidden-xs-down">Set & Instruments</span></a> </li>
</ul>
<!-- Tab panes -->
<div class="tab-content tabcontent-border">
    <div class="tab-pane p-2 active" id="information" role="tabpanel">
        <fieldset>
            <legend>INFORMASI ASET</legend>
            <table class="table th-noColor" style="background:#fff">
                <tbody>
                    <tr>
                        <th width="300">KODE SISTEM</th>
                        <td id="aset_systemCode"></td>
                    </tr>
                    <tr>
                        <th width="300">NAMA PIECE</th>
                        <td id="aset_assetName"></td>
                    </tr>
                </tbody>
            </table>
        </fieldset>
    </div>
    <div class="tab-pane p-2" id="administrative" role="tabpanel">
        <table class="table th-noColor" style="background:#fff">
            <tr>
                <th width="300">HARGA BELI</th>
                <td id="aset_priceBuy"></td>
            </tr>
            <tr>
                <th width="300">TANGGAL PEROLEHAN</th>
                <td id="aset_procureDate"></td>
            </tr>
            <tr>
                <th width="300">KETERANGAN</th>
                <td id="aset_keterangan"></td>
            </tr>
        </table>
    </div>
    
    <div class="tab-pane p-2" id="set_instruments" role="tabpanel">
        <fieldset>
            <legend>INFORMASI SET</legend>
            <table class="table th-noColor" style="background:#fff">
                <tr>
                    <th width="300">SET CODE</th>
                    <td id="set_systemCode"></td>
                </tr>
                <tr>
                    <th width="300">SET NAME</th>
                    <td id="set_assetName"></td>
                </tr>
                <!-- <tr>
                    <th width="300">SET CATALOGUE</th>
                    <td id="set_catalogue"></td>
                </tr> -->
            </table>
        </fieldset>
        <hr>
        <table class="table">
            <thead>
                <tr>
                    <th style="width:5%">NO.</th>
                    <th style="width:15%" id="assetCodeTH">KODE SISTEM</th>
                    <th id="catalogueNameTH" style="width:60%">NAMA KATALOG</th>
                    <th id="priceTH" style="width:12%">HARGA</th>
                    <th id="procureDateTH" style="width:20%">TANGGAL PEROLEHAN</th>
                </tr>
            </thead>
            <tbody id="setPieces"></tbody>
        </table>
    </div>

</div>

<script>
    function initializeDetailAdv(res) {
        // fill aset
        aset = res.set.data[0];

        $('#aset_systemCode').html(aset.catCode+'-'+aset.idAsset);

        for (var key in aset) {
            $('#aset_'+key).html(aset[key]);
        }

        propAdmin = aset.propAdmin;
        for (var key in propAdmin) {
            if (key == 'procureDate')
                $('#aset_'+key).html(moment(propAdmin[key]).locale('id').format('dddd, DD/MM/Y'))
            else if (key == 'priceBuy')
                $('#aset_'+key).html('Rp '+formatRupiah(propAdmin[key].toString()));
            else
                $('#aset_'+key).html(propAdmin[key]);
        }
        // end of fill aset

        // fill set & pieces
        $.ajax({
            url: '<?=site_url(uriSegment(1).'/getContainerPieces?id=')?>'+aset.idAsset,
            type: 'GET',
        }).then(setPieces => {
            setPieces = JSON.parse(setPieces);

            set = setPieces.set.data[0];
            pieces = setPieces.pieces.data;

            $('#setPieces').html('');
            var setPiecesHTML = '';

            // fill set
            $('#set_systemCode').html(set.catCode+'-'+set.idAsset);
            $('#set_assetName').html(set.assetName);

            // fill piece
            count = 1;
            if (pieces.length > 0) {
                for (var key in pieces) {
                    setPiecesHTML += '<tr class="addableInstruments">'+
                                        '<td>'+count+'</td>'+
                                        '<td class="assetCodeTD">'+
                                            '<span class="pieces">'+pieces[key].catCode+'-'+set.idAsset+'-'+pieces[key].idAsset+'</span>'+
                                        '</td>'+
                                        '<td>'+
                                            '<span class="pieces">'+pieces[key].assetName+'</span>'+
                                        '</td>'+
                                        '<td class="priceTD">'+
                                            '<span class="pieces">'+(pieces[key].propAdmin.priceBuy ? 'Rp ' + formatRupiah(pieces[key].propAdmin.priceBuy.toString()) : '-')+'</span>'+
                                        '</td>'+
                                        '<td class="procureDateTD">'+
                                            '<span class="pieces">'+(pieces[key].propAdmin.procureDate ? moment(pieces[key].propAdmin.procureDate).lang('id').format('dddd, DD/MM/Y') : '-')+'</span>'+
                                        '</td>'+
                                    '</tr>';
                    count++;
                }
            } else {
                setPiecesHTML += '<tr><td colspan="5" style="text-align:center">This set doesn\'t have any instruments!</td></tr>';
            }

            $('#setPieces').append(setPiecesHTML);
        });
        // end of fill set & pieces
    }
</script>