<script src="http://demos.flesler.com/jquery/scrollTo/js/jquery.scrollTo-min.js"></script>

<!-- <table class="table th-noColor">
	<tbody>
		<tr>
			<th>CONTAINER/BOX INFO</th>
			<td id="infoBox"></td>
		</tr>
		<tr>
			<th>SET INFO</th>
			<td id="infoSet"></td>
		</tr>
		<tr>
			<th>NUMBER OF PIECES</th>
			<td id="infoNumOfPieces"></td>
		</tr>
	</tbody>
</table> -->

<input id="scan" type="text" name="barcode" class="form-control" placeholder="SCANNED CODE" autofocus autocomplete="off">

<div id="loading" style="text-align:center">
	<div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status">
		<span class="sr-only">Loading...</span>
	</div>
	<br>
	<h6><b>Please Wait</b></h6>
</div>
<!-- <div id="boxLocation" style="width:15%;margin:0 auto;padding:30px 10px;border-radius:5px;background:#fcfcfc;border:dotted 1px #ccc;text-align:center;font-weight:bold;font-size:20px;margin-top:20px;">BOX LOCATION</div> -->
<br>
<div id="trackingTables"></div>
<!-- <table class="table table-stripped">
		<thead>
			<th width="50">NO</th>
			<th width="250">PIECE CODE</th>
			<th>INSTRUMENT NAME</th>
		</thead>
		<tbody id="scannedArea" style="font-weight:bold"></tbody>
	</table> -->
<script>
	var numOfPieces = 0;
	var scannedHistory = [];
	var totalEachSet = [];
	var piecesOfCurrentSet = [];

	$(document).ready(function() {

		checkFocus();

		$('#scan').focus();
		$('#loading').hide();
		$('#boxLocation').hide();

		$(document).click(function() {
			$('#scan').focus();
			checkFocus();
		});

		$('#scan').change(function() {
			scannedCode = $(this).val().replace('MIP-', '');

			$('#loading').show();

			$.ajax({
				'url': '<?= site_url('tracking/scan?code=') ?>' + $(this).val(),
				'method': 'GET'
			}).then(res => {
				if (res) {
					res = JSON.parse(res);

					piece = res.piece.data[0];
					set = res.set.data[0];
					if (res.box != undefined) {
						box = res.box.data[0];
					}

					$('#boxLocation').show();

					if (set) {
						$('#boxLocation').html(box.catCode + '-' + box.idAsset);

						// if (scannedHistory.length == 0) {
							$('#infoBox').html('<b>' + box.catCode + '-' + box.idAsset + '</b> | ' + box.assetName);
							$('#infoSet').html('<b>' + set.catCode + '-' + set.idAsset + '</b> | ' + set.assetName);

							// show all pieces
							$.ajax({
								url: '<?= site_url('tracking/getAllPieces?pieceCode=') ?>' + $(this).val(),
								method: 'GET',
							}).then(res => {

								// append table each set
								if (!scannedHistory.hasOwnProperty(set.idAsset)) {
									scannedHistory[set.idAsset] = ['MIP-' + scannedCode];

									$('#trackingTables').append('<table class="table table-stripped" id="table-'+set.idAsset+'">' +
																	'<thead>' +
																		'<th width="50">NO</th>' +
																		'<th>CONTAINER INFORMATION</th>' +
																		'<th>SET INFORMATION</th>' +
																		'<th>PIECE CODE</th>' +
																		'<th>INSTRUMENT NAME</th>' +
																	'</thead>' +
																	'<tbody id="scannedArea-' + set.idAsset + '" style="font-weight:bold"></tbody>' +
																'</table>');
								} else {
									if (!scannedHistory[set.idAsset].includes('MIP-' + scannedCode)) {
										scannedHistory[set.idAsset].push('MIP-' + scannedCode);
									}
								}

								pieces = JSON.parse(res).data;

								$('#infoNumOfPieces').html(pieces.length);

								no = 0;
								var piecesList = '';

								for (var piece in pieces) {
									piecesOfCurrentSet.push(pieces[piece].idAsset);
									piecesList += 	'<tr id="piece' + pieces[piece].idAsset + '" style="background-color:' + (pieces[piece].idAsset == scannedCode ? '#d4ffd5' : '#ffe8e8') + '">' +
														'<td>' + (no += 1) + '</td>' +
														'<td>' + box.catCode + '-' + box.idAsset + ' | ' + box.assetName + '</td>' +
														'<td>' + set.catCode + '-' + set.idAsset + ' | ' + set.assetName + '</td>' +
														'<td>' + pieces[piece].catCode + '-' + pieces[piece].idAsset + '</td>' +
														'<td>' + pieces[piece].assetName + '</td>' +
													'</tr>';
								}
								if (!totalEachSet.hasOwnProperty(set.idAsset))
									$('#scannedArea-' + set.idAsset).append(piecesList);

								
								numOfPieces = pieces.length;
								totalEachSet[set.idAsset] = numOfPieces;
								if (scannedHistory[set.idAsset].length == totalEachSet[set.idAsset]) {
									$('#table-' + set.idAsset).append('<button class="btn btn-success mt-4" onclick="hideTable('+set.idAsset+')">Hide</button>');
								}
							});
						// } else {
							// console.log(piecesOfCurrentSet.includes(scannedCode));
						// }

						// if (!scannedHistory.includes($(this).val())) {
						// 	// scannedHistory.push(set.idAsset);
						// 	// scannedHistory[set.idAsset] = $(this).val();

						// 	// $('#piece' + $(this).val().replace('MIP-', '')).css('background-color', '#d4ffd5');
						// 	// $('body').scrollTo('#piece' + $(this).val().replace('MIP-', ''));
						// }

						// scannedHistory[set.idAsset][$(this).val()] = $(this).val();

						$('#piece' + $(this).val().replace('MIP-', '')).css('background-color', '#d4ffd5');
						$('body').scrollTo('#piece' + $(this).val().replace('MIP-', ''));
					} else {
						// var scannedHTML = '<tr><td colspan="3" style="text-align:center">NOT IN SET</td></tr>';
						// $('#boxLocation').html('NOT FOUND');
					}
				} else {
					// var scannedHTML = '<tr><td colspan="3" style="text-align:center">NOT FOUND</td></tr>';
				}

				$('#loading').hide();
				$(this).val('');
				$(this).focus();
				checkFocus();

				if (scannedHistory.length == numOfPieces) {
					// Swal.fire('All Pieces Scanned!');
				}
			});
		});

	});

	function checkFocus() {
		if ($('#scan').focus()) {
			// console.log('ready scan');
		} else {
			// console.log('paused');
		}
	}

	function hideTable(setID) {
		$('#table-'+setID).hide();
	}
</script>
