<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="advancedFilterModal" role="dialog" aria-labelledby="advancedFilterModalTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header bg-light">
				<h5 class="modal-title" id="modalTitle">Advanced Filters</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="advancedFilterForm" class="form-horizontal r-separator" onkeydown="return event.key != 'Enter';" method="post" action="#">
					<div class="card-body">

						<div class="form-group row align-items-center mb-0">
							<label class="col-4 control-label col-form-label">KODE SISTEM</label>
							<div class="col-2 border-left pb-2 pt-2">
								<input type="text" class="form-control" name="idAssetMaster">
							</div>
						</div>
						<div class="form-group row align-items-center mb-0">
							<label class="col-4 control-label col-form-label">KATALOG</label>
							<div class="col-8 border-left pb-2 pt-2">
								<select name="filter_idAssetMaster" class="form-control select2 setMasterSelector" style="width:100%;"></select>
							</div>
						</div>
						<div class="form-group row align-items-center mb-0">
							<label class="col-4 control-label col-form-label">NAMA ASET</label>
							<div class="col-8 border-left pb-2 pt-2">
								<input type="text" class="form-control" name="assetName">
							</div>
						</div>
						<div class="form-group row align-items-center mb-0">
							<label for="" class="col-4 control-label col-form-label">RUANGAN</label>
							<div class="col-4 border-left pb-2 pt-2">
								<select name="filter_locationRoom" id="filter_locationRoom" class="form-control filter_locationRoom" style="width:100%"></select>
							</div>
						</div>
<!--						<div class="form-group row align-items-center mb-0">-->
<!--							<label class="col-4 control-label col-form-label">KATEGORI ASET</label>-->
<!--							<div class="col-4 border-left pb-2 pt-2">-->
<!--								<select id="filter_insCategory" name="insCategory" class="form-control select2" style="width:100%">-->
<!--									<option value="">- Semua Kategori -</option>-->
<!--								</select>-->
<!--							</div>-->
<!--						</div>-->
						<div class="form-group row align-items-center mb-0">
							<label class="col-4 control-label col-form-label">HARGA BELI</label>
							<div class="col-3 border-left pb-2 pt-2">
								<input type="text" class="form-control currency" name="priceBuy">
							</div>
						</div>
						<div class="form-group row align-items-center mb-0">
							<label class="col-4 control-label col-form-label">TAHUN PEROLEHAN</label>
							<div class="col-5 border-left pb-2 pt-2" style="display:inline-block">
								<input type="number" class="form-control" style="width:40%;float:left;" name="yearProcurement_first" placeholder="Tahun" min="1945">
								<span style="float:left;margin-top:3px;padding:5px;">sampai</span>
								<input type="number" class="form-control" style="width:40%;" name="yearProcurement_last" placeholder="Tahun" min="1945">
							</div>
						</div>
						<div class="form-group row align-items-center mb-0">
							<label class="col-4 control-label col-form-label">TANGGAL PEROLEHAN</label>
							<div class="col-8 border-left pb-2 pt-2" style="display:inline-block">
								<input type="text" style="width:40%;float:left;" class="form-control bootstrapMaterialDatePicker" name="procureDate_first" placeholder="Tanggal Awal" autocomplete="off">
								<span style="float:left;margin-top:3px;padding:5px;">sampai</span>
								<input type="text" style="width:40%;" class="form-control bootstrapMaterialDatePicker" name="procureDate_last" placeholder="Tanggal Akhir" autocomplete="off">
							</div>
						</div>
						<!-- <div class="form-group row align-items-center mb-0">
							<label class="col-4 control-label col-form-label">SUPPLIER</label>
							<div class="col-6 border-left pb-2 pt-2">
								<select id="filter_supplier" name="supplier" class="form-control select2" style="width:100%">
									<option value="" selected>- Semua Supplier -</option>
								</select>
							</div>
						</div> -->
						<div class="form-group row align-items-center mb-0">
							<label class="col-4 control-label col-form-label">MERK</label>
							<div class="col-4 border-left pb-2 pt-2">
								<select name="merk" id="filter_merk" class="form-control select2" style="width:100%;">
									<option value="" selected>- Semua Merk -</option>
								</select>
							</div>
						</div>
						<div class="form-group row align-items-center mb-0">
							<label class="col-4 control-label col-form-label">KONDISI</label>
							<div class="col-4 border-left pb-2 pt-2">
								<select id="filter_condition" name="condition" class="form-control select2" style="width:100%">
									<option value="" selected>- Semua Kondisi -</option>
									<?php
									foreach (explode(',', env('KONDISI')) as $key => $value) {
										echo '<option value="' . $value . '">' . $value . '</option>';
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group row align-items-center mb-0">
							<label class="col-4 control-label col-form-label">STATUS</label>
							<div class="col-4 border-left pb-2 pt-2">
								<select id="filter_status" name="status" class="form-control select2" style="width:100%">
									<option value="" selected>- Semua Status -</option>
									<?php
									foreach (explode(',', env('STATUS')) as $key => $value) {
										echo '<option value="' . $value . '">' . $value . '</option>';
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group row align-items-center mb-0">
							<label class="col-4 control-label col-form-label">LEVEL RESIKO</label>
							<div class="col-4 border-left pb-2 pt-2">
								<select id="filter_riskLevel" name="riskLevel" class="form-control select2" style="width:100%">
									<option value="" selected>- Semua Level Resiko -</option>
									<?php
									foreach (explode(',', env('LEVEL_RESIKO')) as $key => $value) {
										echo '<option value="' . $value . '">' . $value . '</option>';
									}
									?>
								</select>
							</div>
						</div>
					</div>
			</div>
			<div class="modal-footer bg-light">
				<button type="submit" id="filterButton" class="btn btn-info waves-effect waves-light">Filter</button>
				<button type="reset" class="btn btn-secondary waves-effect waves-light">Clear</button>
				<button type="button" class="btn btn-dark waves-effect waves-light" data-dismiss="modal">Close</button>
			</div>
			</form>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		// Default option Kategori Instrument
		$.ajax({
			'url': '<?= site_url('ajax/instrumentCategories') ?>',
		}).then(res => {
			res = JSON.parse(res);

			var Opt = '';
			for (key in res) {
				Opt += '<option value="' + res[key] + '">' + res[key] + '</option>';
			}

			$('select[name="insCategory"]').append(Opt);
		});

		// Default Option Merk
		$.ajax({
			'url': '<?= site_url('ajax/merk') ?>',
		}).then(res => {
			res = JSON.parse(res);

			var Opt = '';
			for (key in res) {
				Opt += '<option value="' + res[key] + '">' + res[key] + '</option>';
			}

			$('select[name="merk"]').append(Opt);
		});

		// Default Option Location Room
		$.ajax({
			'url': '<?= site_url('ajax/room') ?>',
		}).then(res => {
			res = JSON.parse(res);

			var Opt = '';
			for (key in res) {
				Opt += '<option value="<?=env('L_ROOM')?>' + res[key].split('|')[0].split('-')[1].replace(' ', '') + '">' + res[key] + '</option>';
			}

			console.log(Opt);

			$('select[name="filter_locationRoom"]').append(Opt);
		});
		$('.filter_locationRoom').select2();
	});
</script>
