<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item nav-item-detail"> <a class="nav-link active" data-toggle="tab" href="#information" role="tab"><span class="hidden-sm-up"></span> <span class="hidden-xs-down">Informasi Aset</span></a> </li>
    <li class="nav-item nav-item-detail"> <a class="nav-link" data-toggle="tab" href="#administrative" role="tab"><span class="hidden-sm-up"></span> <span class="hidden-xs-down">Informasi Administrasi</span></a> </li>
    <li class="nav-item nav-item-detail"> <a class="nav-link" data-toggle="tab" href="#pieces" role="tab"><span class="hidden-sm-up"></span> <span class="hidden-xs-down">Daftar Instrument</span></a> </li>
</ul>
<!-- Tab panes -->
<div class="tab-content tabcontent-border">
    <div class="tab-pane p-2 active" id="information" role="tabpanel">
        <table class="table th-noColor">
            <tbody>
                <tr>
                    <th>KODE SISTEM</th>
                    <td id="set_systemCode"></td>
                </tr>
                <tr>
                    <th>NAMA SET</th>
                    <td id="set_assetName"></td>
                </tr>
                <!-- <tr>
                    <th>NAMA KATALOG</th>
                    <td id="set_catalogueName"></td>
                </tr> -->
                <tr>
                    <th>CONTAINER/BOX</th>
                    <td id="boxContainer"></td>
                </tr>
                <tr>
                    <th>KATEGORI</th>
                    <td id="set_insCategory"></td>
                </tr>
                <tr>
                    <th>LEVEL RESIKO</th>
                    <td id="set_riskLevel"></td>
                </tr>
                <tr>
                    <th>KONDISI</th>
                    <td id="set_condition"></td>
                </tr>
                <tr>
                    <th>STATUS</th>
                    <td id="set_status"></td>
                </tr>
                <tr>
                    <th>LOKASI</th>
                    <td id="set_location"></td>
                </tr>
                <tr>
                    <th>SUPPLIER</th>
                    <td id="set_supplier"></td>
                </tr>
                <tr>
                    <th>DESKRIPSI</th>
                    <td id="set_assetDesc"></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="tab-pane p-2" id="administrative" role="tabpanel">
        <table class="table th-noColor">
            <tbody>
                <tr>
                    <th>TANGGAL PEROLEHAN</th>
                    <td id="set_procureDate"></td>
                </tr>
                <tr>
                    <th>HARGA BELI</th>
                    <td id="set_priceBuy"></td>
                </tr>
                <!-- <tr>
                    <th>KETERANGAN</th>
                    <td id="set_keterangan"></td>
                </tr> -->
            </tbody>
        </table>
    </div>
    <div class="tab-pane p-2" id="pieces" role="tabpanel">
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
        // autofill set
        setCatalogue = res.setCatalogue.data[0];
        // $('#set_catalogueName').html(setCatalogue.productCode + ' | ' + setCatalogue.assetMasterName);

        set = res.set.data[0];
        $('#set_systemCode').html(set.catCode+'-'+set.idAsset);

        // set information
        for (var key in set) {
            if (key == 'assetParent') 
                setBoxDetail(set.assetParent);
            else 
                $('#set_'+key).html(set[key]);
        }

        // set propAdmin information
        propAdmin = set.propAdmin;
        for (var key in propAdmin) {
            if (key == 'procureDate')
                $('#set_'+key).html(moment(propAdmin[key]).locale('id').format('dddd, DD/MM/Y'))
            else if (key == 'priceBuy')
                $('#set_'+key).html('Rp '+formatRupiah(propAdmin[key].toString()));
            else
                $('#set_'+key).html(propAdmin[key]);
        }

        // set propInstrument information
        propInstrument = set.propInstrument;
        for (var key in propInstrument) {
            $('#set_'+key).html(propInstrument[key]);
        }

        // set pieces
        pieces = res.pieces.data;

        $('#setPieces').html('');
        var setPiecesHTML = '';

        count = 1;
        if (pieces.length > 0) {
            for (var key in pieces) {
                setPiecesHTML += '<tr class="addableInstruments">'+
                                    '<td>'+count+'</td>'+
                                    '<td class="assetCodeTD">'+
                                        '<span class="pieces">MIP-'+set.idAsset+'-'+pieces[key].idAsset+'</span>'+
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
    }

    function setBoxDetail(selectedBox) {
        if (selectedBox != 0) {
            $.ajax({
                url: '<?=site_url(uriSegment(1).'/getSingleData?id=')?>' + (selectedBox.isArray ? selectedBox[0] : selectedBox),
                type: 'GET',
            }).then(res => {
                res = JSON.parse(res).set.data[0];

                $('#boxContainer').html(res.catCode+'-'+res.idAsset + ' | ' +res.assetName);
            });
        } else
            $('#boxContainer').html('-');
    }

</script>
