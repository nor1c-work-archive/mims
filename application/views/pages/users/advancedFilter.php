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

						<!-- <div class="form-group row align-items-center mb-0">
							<label for="inputEmail3" class="col-4 control-label col-form-label">KODE SISTEM</label>
							<div class="col-2 border-left pb-2 pt-2">
								<input type="text" class="form-control" name="idAssetMaster">
							</div>
						</div>
						<div class="form-group row align-items-center mb-0">
							<label for="inputEmail3" class="col-4 control-label col-form-label">KODE KATALOG</label>
							<div class="col-4 border-left pb-2 pt-2">
								<input type="text" class="form-control" name="productCode">
							</div>
						</div>
						<div class="form-group row align-items-center mb-0">
							<label for="inputEmail3" class="col-4 control-label col-form-label">NAMA KATALOG</label>
							<div class="col-8 border-left pb-2 pt-2">
								<input type="text" class="form-control" name="assetMasterName">
							</div>
						</div>
						<div class="form-group row align-items-center mb-0">
							<label for="inputEmail3" class="col-4 control-label col-form-label">MERK</label>
							<div class="col-6 border-left pb-2 pt-2">
								<select name="merk" id="" class="form-control select2 selectMerk" style="width:100%;">
									<option value="" selected>- Semua Merk -</option>
								</select>
							</div>
						</div> -->

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

		// $('.selectMerk').select2({
		//     minimumInputLength: 3,
		//     allowClear: true,
		//     placeholder: 'Merk',
		//     ajax: {
		//         dataType: 'json',
		//         url: '<?= site_url('master/merk') ?>',
		//         delay: 800,
		//         data: function(params) {
		//             return {
		//                 query: 'keyword='+params.term+'&catCode=<?= uriSegment(3) ?>&column=merk',
		//             }
		//         },
		//         processResults: function (data, page) {
		//             var options = [];
		//             for (var list in data) {
		//                 options = [{
		//                     'id' : data[list],
		//                     'text' : data[list]
		//                 }];
		//             }

		//             return {
		//                 results: options,
		//             };
		//         },
		//     }
		// }).on('select2:select', function (evt) {
		//     var data = $(".instrumentSelector option:selected").val();
		//     console.log(data);
		// });
	});
</script>
