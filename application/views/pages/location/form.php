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
<div class="modal fade bd-example-modal-xl" id="formModal" role="dialog" aria-labelledby="formModalTitle" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header bg-light">
				<h5 class="modal-title" id="formModalTitle"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="form" class="form-horizontal r-separator" enctype="multipart/form-data" onkeydown="return event.key != 'Enter';">

					<!-- START OF FIRST INPUT GROUP -->
					<div>
						<div class="card-body">
							<table class="table table-striped" style="background:#fff;">
								<fieldset>
									<legend>INFORMASI <?=strtoupper($locType)?></legend>

									<div class="card-body">
										<input type="hidden" id="idLocation" name="location[1][idLocation]" value="0">
										<input type="hidden" id="locType" name="location[1][locType]" value="<?= strtoupper(uriSegment(3)) ?>">

										<div id="systemCodeDiv" class="form-group row align-items-center mb-0">
											<label class="col-3 control-label col-form-label">Kode Sistem</label>
											<div class="col-2 border-left pb-2 pt-2">
												<span id="systemCode" class="form-control" style="background:#eee"></span>
											</div>
										</div>

										<?php if (uriSegment(3) == 'room') { ?>
											<div class="form-group row align-items-center mb-0">
												<label class="col-3 control-label col-form-label"><?=$locType?></label>
												<div class="col-6 border-left pb-2 pt-2">
													<input id="parentLoc" name="location[1][parentLoc]" type="text" class="form-control" style="width:80%;float:left;background:#eee;" readonly>
													<a class="btn btn-info fancy-link" data-fancybox data-type="ajax" data-src="<?= site_url('picker/building') ?>" href="javascript:;" data-toggle="tooltip" data-placement="right" title="Pilih Building">
														<i class="mdi mdi-reorder-horizontal"></i>
													</a>
												</div>
											</div>
										<?php } ?>
										<?php
											input('text', 'Nama '.$locType, 'locName', 'location[1][locName]', '', '8', ' required ');
											input('text', 'Koordinat', 'locLonglat', 'location[1][locLonglat]', 'locLonglat', '8', ' required ');
											input('textarea', 'Deskripsi', 'locDesc', 'location[1][locDesc]', 'locDesc', '8', ' required ');
										?>
									</div>
								</fieldset>
							</table>
						</div>
					</div>
					<!-- END OF FIRST INPUT GROUP -->

			</div>
			<div class="modal-footer bg-light">
				<button type="submit" class="btn btn-info waves-effect waves-light">Save</button>
				<button type="reset" class="btn btn-secondary waves-effect waves-light">Clear</button>
				<button type="button" class="btn btn-dark waves-effect waves-light" data-dismiss="modal">Close</button>
			</div>
			</form>
		</div>
	</div>
</div>

<script>
	function setDefaultFormModalTitle() {
		$('#formModalTitle').html('Tambah <?=$locType?> Baru');
	}

	function initializeInput(res) {
		res = JSON.parse(res)[0];

		$('#formModalTitle').html('Ubah Informasi <?=$locType?> <b>' + res.locName + '</b>');

		$('#systemCode').html('<?=env('L_'.strtoupper(uriSegment(3)))?>-'+res.idLocation);
		for (var key in res) {
			if (key == 'parentLoc') {
				$('#form #parentLoc').val('BLD-'+res[key]);
			} else {
				$('#form #'+key).val(res[key]);
			}
		}
	}

	function setBuilding(selectedBox) {
		$.ajax({
			url: '<?= site_url(uriSegment(1) . '/getSingleData?id=') ?>' + (typeof selectedBox === 'object' ? selectedBox[0] : selectedBox),
			type: 'GET',
		}).then(res => {
			res = JSON.parse(res)[0];

			$('#parentLoc').val('<?=env('L_'.strtoupper('BUILDING'))?>' + '-' + res['idLocation'] + ' | ' + res['locName']);
		});
	}

</script>
