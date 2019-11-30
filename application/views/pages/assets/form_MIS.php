<!-- SET INFORMATION -->
<fieldset>
	<legend>INFORMASI ASET</legend>

	<div class="card-body">
		<input type="hidden" id="idAsset" name="asset[1][idAsset]" value="0">
		<input type="hidden" id="catCode" name="asset[1][catCode]" value="<?= uriSegment(3) ?>">

		<div id="systemCodeDiv" class="form-group row align-items-center mb-0">
			<label class="col-3 control-label col-form-label">Kode Sistem</label>
			<div class="col-2 border-left pb-2 pt-2">
				<span id="systemCode" class="form-control" style="background:#eee"></span>
			</div>
		</div>

		<div class="form-group row align-items-center mb-0">
			<label class="col-3 control-label col-form-label">Container/Box</label>
			<div class="col-6 border-left pb-2 pt-2">
				<input id="assetParent" name="asset[1][assetParent]" type="text" class="form-control" style="width:80%;float:left;background:#eee;" readonly>
				<a class="btn btn-info fancy-link" data-fancybox data-type="ajax" data-src="<?= site_url('picker/boxContainer') ?>" href="javascript:;" data-toggle="tooltip" data-placement="right" title="Pilih Container/Box">
					<i class="mdi mdi-reorder-horizontal"></i>
				</a>
			</div>
		</div>

		<?php
		// input('select2_automatic', 'Katalog Instrument Set', 'idAssetMaster', 'asset[1][idAssetMaster]', 'setMasterSelector', '8', ' style="width:100%" ');
		// input('text', 'Nama Aset', 'assetName', 'asset[1][assetName]', '', '8', ' required ');
		input('select2_automatic', 'Instrument Set', 'idAssetMaster', 'asset[1][idAssetMaster]', 'setMasterSelector', '8', ' style="width:100%" ');
		input('hidden', 'Nama Aset', 'assetName', 'asset[1][assetName]', 'assetName', '8', ' required ');
		// input('text', 'Kategori Set', 'insCategory', 'asset[1][insCategory]', '', '4', ' required ');
		// input('select2', 'Level Resiko', 'riskLevel', 'asset[1][riskLevel]', ' select2 ', '3', ' style="width:100%" ', 'Pilih Level Resiko', explode(',', env('LEVEL_RESIKO')));
		input('select2', 'Kondisi', 'condition', 'asset[1][condition]', ' select2 ', '3', ' style="width:100%" ', 'Pilih Kondisi', explode(',', env('KONDISI')));
		input('select2', 'Status', 'status', 'asset[1][status]', ' select2 ', '3', ' style="width:100%" ', 'Pilih Status', explode(',', env('STATUS')));
		// input('select2_automatic', 'Lokasi', 'idLocation', 'asset[1][idLocation]', 'locationSelector', '8', ' style="width:100%" ');
		// input('select2_automatic', 'Supplier', 'idSupplier', 'asset[1][supplier]', 'supplierSelector', '6', ' style="width:100%" ');
		// input('textarea', 'Deskripsi Aset', 'assetDesc', 'asset[1][assetDesc]', '', '9');
		?>
	</div>
</fieldset>
<!-- END OF SET INFORMATION -->

<!-- START OF ADMINISTATION -->
<fieldset>
	<legend>INFORMASI ADMINISTRASI</legend>
	<div class="card-body">
		<?php
		input('text', 'Tanggal Perolehan', 'procureDate', 'asset[1][procureDate]', 'bootstrapMaterialDatePicker', '2', ' required ');
		input('text', 'Harga Beli', 'priceBuy', 'asset[1][priceBuy]', 'currency', '2', ' required ');
		input('select2', 'Kepemilikan', 'ownershipType', 'asset[1][ownershipType]', 'select2', '3', ' style="width:100%" ', 'Pilih Kepemilikan', explode(',', env('KEPEMILIKAN')));
		// input('textarea', 'Keterangan', 'keterangan', 'assets[1][keterangan]', '', '9');
		?>
	</div>
</fieldset>
<!-- END OF ADMINISTRATION -->

<!-- SET PIECES -->
<fieldset>
	<legend>DAFTAR INSTRUMENT</legend>
	<div class="card-body">
		<br>
		<input type="hidden" name="isEdit" value="0">
		<?php
		table(
			'blank_tbody',
			array(
				array('title' => 'KODE SISTEM', 'id' => 'assetCodeTH', 'style' => 'width:15%'),
				array('title' => 'NAMA KATALOG', 'id' => 'catalogueNameTH', 'style' => 'width:7%'),
				array('title' => 'QUANTITY', 'id' => 'quantityTH', 'style' => 'width:10%'),
				array('title' => 'HARGA SATUAN', 'id' => 'priceTH', 'style' => 'width:15%'),
				array('title' => 'TANGGAL PEROLEHAN', 'id' => 'procureDateTH', 'style' => 'width:15%'),
				array('title' => '', 'id' => 'deleteTH', 'style' => 'width:7%'),
			),
			'instrumentsForm',
			'table-striped'
		);
		?>
		<button id="addMoreForm" type="button" class="btn btn-info"><i class="fas fa-plus"></i> &nbsp; ADD MORE</button>
	</div>
</fieldset>
<!-- END OF SET PIECES -->

<script>
	$(document).ready(function() {
		$('#addMoreForm').click(function() {
			addCount += 1;
			formHTML = 	'<tr id="tr-' + addCount + '" class="addableInstruments">' +
							'<input type="hidden" name="instruments[' + addCount + '][idAssetMaster]" id="idAssetMaster">' +
							'<input type="hidden" name="instruments[' + addCount + '][idAsset]" id="idAsset" value="">' +
							'<input type="hidden" name="instruments[' + addCount + '][catCode]" id="catCode" value="MIP">' +
							'<td class="assetCodeTD">' +
								'<input type="text" class="form-control" name="instruments[' + addCount + '][assetCode]" id="assetCode" readonly>' +
							'</td>' +
							'<td>' +
								'<select id="instrumentsIdAssetMaster'+addCount+'" name="instruments[' + addCount + '][idAssetMaster]" class="form-control select2 instrumentSelector" style="width:100%;"></select>' +
							'</td>' +
							'<td>' +
								'<input type="number" class="form-control" name="instruments[' + addCount + '][quantity]" id="quantity" placeholder="Quantity" value="1" min="1">' +
							'</td>' +
							'<td class="priceTD">' +
								'<input type="text" id="priceBuy" class="form-control" name="instruments[' + addCount + '][priceBuy]" autocomplete="off">' +
							'</td>' +
							'<td class="procureDateTD">' +
								'<input type="text" id="procureDate" class="form-control bootstrapMaterialDatePicker" name="instruments[' + addCount + '][procureDate]">' +
							'</td>' +
							'<td id="deleteTD">' +
								'<button type="button" class="btn btn-danger" onClick="deleteInputRow(' + addCount + ')"><i class="mdi mdi-close"></i></button>';
							'</td>' +
						'</tr>';

			$('#instrumentsForm').append(formHTML);

			if ($('input[name=isEdit]').val() == '0') {
				$('.assetCodeTD').hide();
				$('.priceTD').hide();
				$('.procureDateTD').hide();
			}

			initializeInsSelect();
		});

		if ($('input[name=isEdit]').val() == '0') {
			$('#catalogueNameTH').css('width', '90%');

			$('#assetCodeTH').hide();
			$('#priceTH').hide();
			$('#procureDateTH').hide();
		}

		// // Default Option Merk
		// $.ajax({
		// 	'url': '<?= site_url('ajax/catalogue/MIP') ?>',
		// }).then(res => {
		// 	res = JSON.parse(res);

		// 	var Opt = '';
		// 	for (key in res) {
		// 		Opt += '<option value="' + key + '">' + res[key] + '</option>';
		// 	}

		// 	$('select[name="instruments['+addCount+'][idAssetMaster]"]').append(Opt);
		// });
		// $('.instrumentSelector').select2();
	});

	function initializeInputAdv(res) {

		$('#formModalTitle').html('Ubah Detail Asset <b><?= uriSegment(3) ?>-' + res.set.data[0].idAsset + ' | ' + res.set.data[0].assetName + '</b>');

		$('#assetCodeTH').show();
		$('#priceTH').show();
		$('#procureDateTH').show();

		$('.addableInstruments').remove();
		$('input[name=isEdit]').val('1');
		$('#catalogueNameTH').css('width', '40%');

		// autoselect set catalogue
		setCatalogue = res.setCatalogue.data[0];
		$("#idAssetMaster").empty().append('<option value="' + setCatalogue.idAssetMaster + '">' + setCatalogue.productCode + ' | ' + setCatalogue.assetMasterName + '</option>').val(setCatalogue.idAssetMaster).trigger('change');

		// autofill set
		set = res.set.data[0];

		// set basic set information
		$('#systemCodeDiv').show();
		$('#systemCode').html('<?= uriSegment(3) ?>-' + set.idAsset);
		for (var key in set) {
			if (key == 'assetParent') {
				setBox(set.assetParent);
			} else {
				$('#form #' + key).val(set[key]);
			}
		}

		// set propAdmin information
		propAdmin = set.propAdmin;
		var propAdminOpt = ['riskLevel', 'ownershipType', 'condition', 'status'];
		for (var key in propAdmin) {
			if (key == 'procureDate') {
				$('#form #' + key).val(moment(propAdmin[key]).format('DD/MM/Y'));
			} else if (propAdminOpt.includes(key)) {
				$('#form #' + key).val(propAdmin[key]).trigger('change');
			} else
				$('#form #' + key).val(propAdmin[key]);
		}

		// set propIns information
		propInstrument = set.propInstrument;
		for (var key in propInstrument) {
			$('#form #' + key).val(propInstrument[key]);
		}

		// set pieces
		pieces = res.pieces.data;

		var editCount = 1;
		var productCode = '';

		for (var key in pieces) {

			formHTMLEdit = 	'<tr id="tr-' + editCount + '" class="addableInstruments">' +
								'<input type="hidden" name="instruments[' + editCount + '][idAsset]" id="idAsset" value="' + pieces[key].idAsset + '">' +
								'<input type="hidden" name="instruments[' + editCount + '][catCode]" id="catCode" value="MIP">' +
								'<td class="assetCodeTD">' +
									'<span class="form-control" style="background:#eee">MIP-' + set.idAsset + '-' + pieces[key].idAsset + '</span>' +
								'</td>' +
								'<td>' +
									'<select name="instruments[' + editCount + '][idAssetMaster]" class="form-control select2 instrumentSelector idAssetMaster' + editCount + '" style="width:100%;"></select>' +
								'</td>' +
								'<td class="quantityTD">' +
									'<input type="number" id="quantity" class="form-control" name="instruments[' + editCount + '][quantity]" readonly value="1" min="1">' +
								'</td>' +
								'<td class="priceTD">' +
									'<input type="text" id="priceBuy" class="form-control" name="instruments[' + editCount + '][priceBuy]" value="' + (pieces[key].propAdmin.priceBuy ? pieces[key].propAdmin.priceBuy : '') + '" autocomplete="off">' +
								'</td>' +
								'<td class="procureDateTD">' +
									'<input type="text" id="procureDate" class="form-control" name="instruments[' + editCount + '][procureDate]" value="' + (pieces[key].propAdmin.procureDate ? moment(pieces[key].propAdmin.procureDate).format('DD/MM/Y') : '') + '">' +
								'</td>' +
								'<td id="deleteTD">' +
									'<button type="button" class="btn btn-danger" onClick="deleteInputRow(' + editCount + ', ' + pieces[key].idAsset + ')"><i class="mdi mdi-close"></i></button>';
								'</td>' +
							'</tr>';

			$('#instrumentsForm').append(formHTMLEdit);
			$('.assetCodeTD').show();

			initializeInsSelect();

			$('.idAssetMaster' + editCount).empty().append('<option value="' + pieces[key].idAssetMaster + '">' + pieces[key].assetName + '</option>').val(pieces[key].idAssetMaster).trigger('change');

			editCount += 1;
			addCount = editCount + 1;
		}
	}

	function setBox(selectedBox) {
		$.ajax({
			url: '<?= site_url(uriSegment(1) . '/getSingleData?id=') ?>' + (typeof selectedBox === 'object' ? selectedBox[0] : selectedBox),
			type: 'GET',
		}).then(res => {
			res = JSON.parse(res).set.data[0];

			$('#assetParent').val(res.catCode + '-' + res.idAsset + ' | ' + res.assetName);
		});
	}
</script>
