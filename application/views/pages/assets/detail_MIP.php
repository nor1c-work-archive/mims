<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item nav-item-detail"> <a class="nav-link active" data-toggle="tab" href="#information" role="tab"><span class="hidden-sm-up"></span> <span class="hidden-xs-down">Informasi Dasar</span></a> </li>
    <li class="nav-item nav-item-detail"> <a class="nav-link" data-toggle="tab" href="#administrative" role="tab"><span class="hidden-sm-up"></span> <span class="hidden-xs-down">Administratif</span></a> </li>
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
                        <td id="piece_systemCode"></td>
                    </tr>
                    <tr>
                        <th width="300">NAMA PIECE</th>
                        <td id="piece_assetName"></td>
                    </tr>
                    <tr>
                        <th width="300">NAMA KATALOG PIECE</th>
                        <td id="piece_catalogueName"></td>
                    </tr>
                    <tr>
                        <th width="300">MERK</th>
                        <td id="piece_merk"></td>
                    </tr>
                </tbody>
            </table>
        </fieldset>

        <fieldset>
            <legend>INFORMASI SET (Parent Aset)</legend>
            <table class="table th-noColor" style="background:#fff">
                <tbody>
                    <tr>
                        <th width="300">KODE SISTEM SET</th>
                        <td id="set_systemCode"></td>
                    </tr>
                    <tr>
                        <th width="300">NAMA SET</th>
                        <td id="set_assetName"></td>
                    </tr>
                    <tr>
                        <th width="300">NAMA KATALOG SET</th>
                        <td id="set_catalogueName"></td>
                    </tr>
                    <tr>
                        <th width="300">CONTAINER/BOX</th>
                        <td id="set_boxContainer"></td>
                    </tr>
                    <tr>
                        <th width="300">MERK</th>
                        <td id="set_merk"></td>
                    </tr>
                </tbody>
            </table>
        </fieldset>
    </div>
    <div class="tab-pane p-2 active" id="administrative" role="tabpanel">
        <table class="table th-noColor" style="background:#fff">
            <tr>
                <th width="300">HARGA BELI</th>
                <td id="piece_priceBuy"></td>
            </tr>
            <tr>
                <th width="300">TANGGAL PEROLEHAN</th>
                <td id="piece_procureDate"></td>
            </tr>
            <tr>
                <th width="300">KETERANGAN</th>
                <td id="piece_keterangan"></td>
            </tr>
        </table>
    </div>
</div>

<script>
    function initializeDetailAdv(res) {
        // autofill set

        $('#setInformationArea').hide();

        if (res.parentCatalogue) {
            $('#setInformationArea').show();
            parentCatalogue = res.parentCatalogue.data[0];
            $('#set_catalogueName').html(parentCatalogue.productCode + ' | ' + parentCatalogue.assetMasterName);

            parent = res.parent.data[0];
            for (var key in parent) {
                if (key == 'assetParent')
                    setBoxDetail(parent.assetParent)
                else
                    $('#set_'+key).html(parent[key]);
            }
            $('#set_systemCode').html(parent.catCode + '-' + parent.idAsset);
            $('#set_merk').html(parentCatalogue.merk);
        }

        // set information
        setCatalogue = res.setCatalogue.data[0];
        set = res.set.data[0];

        $('#piece_catalogueName').html(setCatalogue.productCode + ' | ' + setCatalogue.assetMasterName);
        $('#piece_systemCode').html(setCatalogue.catCode+'-'+(set.assetParent ? set.assetParent+'-' : '')+set.idAsset);
        $('#piece_merk').html(setCatalogue.merk);

        // asset information
        for (var key in set) {
            $('#piece_'+key).html(set[key]);
        }

        // set propAdmin information
        propAdmin = set.propAdmin;
        for (var key in propAdmin) {
            if (key == 'procureDate') {
                $('#piece_'+key).html(propAdmin[key] ? moment(propAdmin[key]).lang('id').format('dddd, DD/MM/Y') : '-')
            } else if (key == 'priceBuy') {
                $('#piece_'+key).html(propAdmin[key] ? 'Rp '+formatRupiah(propAdmin[key].toString()) : '-');
            } else
                $('#piece_'+key).html(propAdmin[key]);
        }
    }

    function setBoxDetail(selectedBox) {
        if (selectedBox != 0) {
            $.ajax({
                url: '<?=site_url(uriSegment(1).'/getSingleData?id=')?>' + (selectedBox.isArray ? selectedBox[0] : selectedBox),
                type: 'GET',
            }).then(res => {
                res = JSON.parse(res).set.data[0];

                $('#set_boxContainer').html(res.catCode+'-'+res.idAsset + ' | ' +res.assetName);
            });
        } else
            $('#set_boxContainer').html('-');
    }

</script>
