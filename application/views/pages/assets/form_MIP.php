<!-- SET PIECES -->
<h6 class="card-title card-title-top">Instruments</h6>

<input type="hidden" name="isEdit" value="0">
<input type="hidden" name="isPiece" value="1">
<table class="table table-striped" style="background:#fff;">
	<thead>
		<tr>
			<th style="width:15%" id="assetCodeTH">KODE SISTEM</th>
			<th id="catalogueNameTH" style="width:30%">NAMA KATALOG</th>
			<th id="quantityTH" style="width:10%">QUANTITY</th>
			<th id="priceTH" style="width:15%">HARGA SATUAN</th>
			<th id="procureDateTH" style="width:15%">TANGGAL PENGADAAN</th>
			<th id="conditionTH" style="width:15%">KONDISI</th>
			<th id="statusTH" style="width:15%">STATUS</th>
			<th style="width:7%" id="deleteTH"></th>
		</tr>
	</thead>
	<tbody id="instrumentsForm"></tbody>
</table>

<button id="addMoreForm" type="button" class="btn btn-info"><i class="fas fa-plus"></i> &nbsp; ADD MORE</button>

<script>

	$(document).ready(function() {
		$('#addMoreForm').click(function() {
			addCount += 1;
			formHTML = '<tr id="tr-' + addCount + '" class="addableInstruments">' +
							'<input type="hidden" name="asset[' + addCount + '][idAssetMaster]" id="idAssetMaster">' +
							'<input type="hidden" name="asset[' + addCount + '][idAsset]" id="idAsset" value="">' +
							'<input type="hidden" name="asset[' + addCount + '][catCode]" id="catCode" value="MIP">' +
							'<td class="assetCodeTD">' +
								'<input type="text" class="form-control" name="asset[' + addCount + '][assetCode]" id="assetCode" readonly>' +
							'</td>' +
							'<td>' +
								'<select name="asset[' + addCount + '][idAssetMaster]" class="form-control select2 instrumentSelector" style="width:100%;"></select>' +
							'</td>' +
							'<td>' +
								'<input type="number" class="form-control" name="asset[' + addCount + '][quantity]" id="quantity" placeholder="Quantity" value="1" min="1">' +
							'</td>' +
							'<td class="priceTD">' +
								'<input type="text" id="priceBuy" class="form-control" name="asset[' + addCount + '][priceBuy]" autocomplete="off">' +
							'</td>' +
							'<td class="procureDateTD">' +
								'<input type="text" id="procureDate" class="form-control bootstrapMaterialDatePicker" name="asset[' + addCount + '][procureDate]">' +
							'</td>' +
							'<td class="conditionTD">' +
								'<select name="asset['+ addCount +'][condition]" class="select2 form-control selectCondition"></select>'+
							'</td>' +
							'<td class="statusTD">' +
								'<select name="asset['+ addCount +'][status]" class="select2 form-control selectStatus"></select>'+
							'</td>' +
							'<td id="deleteTD">' +
								'<button type="button" class="btn btn-danger" onClick="deleteInputRow(' + addCount + ')"><i class="mdi mdi-close"></i></button>';
							'</td>' +
						'</tr>';

			$('#instrumentsForm').append(formHTML);

			initializeInsSelect();
		});
	});

	function initializeInputAdv(res) {

		$('#formModalTitle').html('Ubah Detail Asset <b><?= uriSegment(3) ?>-' + (res.set.data[0].assetParent ? res.set.data[0].assetParent + '-' : '') + res.set.data[0].idAsset + ' | ' + res.set.data[0].assetName + '</b>');

		$('.addableInstruments').remove();
		$('input[name=isEdit]').val('2');

		$('#addMoreForm').show();

		if (res.set.data.length > 0) {
			$('#addMoreForm').hide();
			$('input[name=isPiece]').val(0);

			// autofill set
			piece = res.set.data[0];
			parent = res.parent ? res.parent.data[0] : [];

			formHTMLEdit = 	'<tr id="tr-1" class="addableInstruments">' +
								'<input type="hidden" name="asset[1][idAsset]" id="idAsset" value="' + piece.idAsset + '">' +
								'<input type="hidden" name="asset[1][catCode]" id="catCode" value="MIP">' +
								'<input type="hidden" name="asset[1][assetParent]" id="catCode" value="' + piece.assetParent + '">' +
								'<td class="assetCodeTD">' +
									'<span class="form-control" style="background:#eee">MIP-' + piece.idAsset + '</span>' +
								'</td>' +
								'<td>' +
									'<select name="asset[1][idAssetMaster]" class="form-control select2 instrumentSelector idAssetMaster1" style="width:100%;"></select>' +
								'</td>' +
								'<td class="quantityTD">' +
									'<input type="number" id="quantity" class="form-control" name="asset[1][quantity]" readonly value="1" min="1">' +
								'</td>' +
								'<td class="priceTD">' +
									'<input type="text" id="priceBuy" class="form-control currency" name="asset[1][priceBuy]" value="' + (piece.propAdmin.priceBuy ? piece.propAdmin.priceBuy : '') + '" autocomplete="off">' +
								'</td>' +
								'<td class="procureDateTD">' +
									'<input type="text" id="procureDate" class="form-control bootstrapMaterialDatePicker" name="asset[1][procureDate]" value="' + (piece.propAdmin.procureDate ? moment(piece.propAdmin.procureDate).format('DD/MM/Y') : '') + '">' +
								'</td>' +
								'<td class="conditionTD">' +
									'<select name="asset['+ addCount +'][condition]" class="select2 form-control selectCondition" style="width:100%"></select>'+
								'</td>' +
								'<td class="statusTD">' +
									'<select name="asset['+ addCount +'][status]" class="select2 form-control selectStatus" style="width:100%"></select>'+
								'</td>' +
								'<td id="deleteTD">' +
									'<button type="button" class="btn btn-danger" onClick="deleteInputRow(1, ' + piece.idAsset + ')"><i class="mdi mdi-close"></i></button>';
								'</td>' +
							'</tr>';

			$('#instrumentsForm').append(formHTMLEdit);
			$('.assetCodeTD').show();

			initializeInsSelect();

			$('.idAssetMaster1').empty().append('<option value="' + piece.idAssetMaster + '">' + piece.assetName + '</option>').val(piece.idAssetMaster).trigger('change');
			$('select[name="asset['+ addCount +'][condition]"]').val(piece.propAdmin.condition).trigger('change');
			$('select[name="asset['+ addCount +'][status]"]').val(piece.propAdmin.status).trigger('change');
		}

	}
</script>
