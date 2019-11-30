<?php
switch (uriSegment(3)) {
	case 'building':
		$locType = 'Gedung';
		break;

	case 'room':
		$locType = 'Ruangan';
		break;
}
?>
	<!-- Modal -->
	<div class="modal fade bd-example-modal-xl" id="detailModal" role="dialog" aria-labelledby="detailModalTitle" aria-hidden="true">
		<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header bg-light">
					<h5 class="modal-title" id="detailModalTitle"></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div>
						<div class="card-body">
							<table class="table th-noColor" style="background:#fff;">
								<tbody id="detail"></tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="modal-footer bg-light">
					<button type="button" class="btn btn-dark waves-effect waves-light" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<script>
		function setDefaultDetailModalTitle() {}

		function initializeDetailInput(res) {
			res = res[0];
			$('#detailModalTitle').html('Detail <?= $locType ?> <b><?= env('L_' . strtoupper(uriSegment(3))) ?>-' + res.idLocation + ' | ' + res.locName + '</b>');

			var detailHtml = '';
			detailHtml +=
				'<tr>' +
				'<th>KODE SISTEM</th>' +
				'<td><?= env('L_' . strtoupper(uriSegment(3))) ?>-' + res.idLocation + '</td>' +
				'</tr>' +
				'<tr>' +
				'<th>NAMA <?= strtoupper(uriSegment(3)) ?></th>' +
				'<td>' + res.locName + '</td>' +
				'</tr>' +
				'<tr>' +
				'<th>KOORDINAT</th>' +
				'<td>' + res.locLonglat + '</td>' +
				'</tr>' +
				'<tr>' +
				'<th>DESKRIPSI</th>' +
				'<td>' + res.locDesc + '</td>' +
				'</tr>';

			$('#detail').html(detailHtml);
		}
	</script>
