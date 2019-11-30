<!-- GOOD LUCK ON UNDERSTANDING THE CODE!! -->
<?php $catCode = uriSegment(3); ?>
<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="icon" type="image/png" sizes="16x16" href="../../assets/images/favicon.png">
	<title><?= $title ?></title>
	<?= css(assets('design/ample/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')) ?>
	<?=css(assets('dist/css/gyrocode/dataTables.checkboxes.css'))?>
	<?=css(assets('dist/css/bootstrap/bootstrap-datepicker.min.css'))?>
	<?= css(assets('design/ample/assets/libs/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')) ?>
	<?= css(assets('design/ample/dist/css/style.min.css')) ?>
	<?=css(assets('dist/css/jquery/jquery-ui.min.css'))?>
	<?=css(assets('dist/css/jquery/jquery.fancybox.min.css'))?>
	<?= css(assets('design/ample-admin-bt4/assets/libs/select2/dist/css/select2.min.css')) ?>
	<?= css(assets('dist/css/ins.css')) ?>
	<style>
		table {
			table-layout: fixed;

			td,
			th {
				overflow: hidden;
				white-space: nowrap;
				-moz-text-overflow: ellipsis;
				-ms-text-overflow: ellipsis;
				-o-text-overflow: ellipsis;
				text-overflow: ellipsis;
			}
		}
	</style>
	<?= js(assets('design/ample/assets/libs/jquery/dist/jquery.min.js')) ?>
	<script>
		var allID = '';
		var filters = [];
		function zeroPad(num, numZeros) {
			var n = Math.abs(num);
			var zeros = Math.max(0, numZeros - Math.floor(n).toString().length);
			var zeroString = Math.pow(10, zeros).toString().substr(1);
			if (num < 0) {
				zeroString = '-' + zeroString;
			}
			return zeroString + n;
		}
		var table;
	</script>
</head>
<?php
	$withoutTable = isset($withoutTable) ? $withoutTable : FALSE;
	$withoutFilter = isset($withoutFilter) ? $withoutFilter : FALSE;
	$withoutForm = isset($withoutForm) ? $withoutForm : FALSE;
	$withoutDetail = isset($withoutDetail) ? $withoutDetail : FALSE;
	$withoutAdvFilter = isset($withoutAdvFilter) ? $withoutAdvFilter : FALSE;
	$withoutImport = isset($withoutImport) ? $withoutImport : FALSE;
	$withoutActButton = isset($withoutActButton) ? $withoutActButton : FALSE;
?>
<body>
	<div class="preloader">
		<div class="lds-ripple">
			<div class="lds-pos"></div>
			<div class="lds-pos"></div>
		</div>
	</div>

	<div id="main-wrapper">
		<?php loadView('components/header') ?>
		<?php loadView('components/sidebar-menu') ?>

		<div class="page-wrapper">
			<?php loadView('pages/prototype/header-sitemap') ?>
			<div class="page-content container-fluid">
				<div class="row">
					<div class="col-12">
						<?php
						if ($page == 'pages/dashboard') {
							loadView($page);
						} else { ?>
							<div class="material-card card">
								<div class="card-body">
									<div style="width:100%;">
										<span style="float:left">
											<h3><b><?= (isset($pageTitle) && $pageTitle ? $pageTitle : '') ?></b></h3>
										</span>
										<span style="float:right;width:40%;><?= (isset($webTrack) && $webTrack ? $webTrack : '') ?></span>
									</div>
									<br>
									<hr>
									<?php if (!$withoutActButton) loadView('pages/components/actionButton') ?>
									<?php if (!$withoutFilter) loadView('pages/components/filterButton') ?>
									<?php
										if (!$withoutTable) {
											if (isset($customDatatable) && $customDatatable)
												loadView('pages/' . strtolower(uriSegment(1)) . '/data');
											else
												loadView('pages/components/data');
										} else {
											loadView($page);
										}
										?>
								</div>
							</div>
							<?php if (!$withoutForm) loadView($modulePath . '/form') ?>
							<?php if (!$withoutDetail) loadView($modulePath . '/detail') ?>
							<?php if (!$withoutAdvFilter) loadView($modulePath . '/advancedFilter') ?>
							<?php if (!$withoutImport) loadView($modulePath . '/import') ?>
						<?php }
						?>
					</div>
				</div>
			</div>

			<div id=" excel" style="display:none"></div>

			<footer class="footer text-center">
				Developed by <a href="https://falitechno.com" style="color:#49d0f5">Falitechno Mandiri</a>
			</footer>
		</div>
	</div>

	<?=js(assets('dist/js/jquery/jquery-ui.min.js'))?>
	<?=js(assets('dist/js/jquery/jquery.fancybox.min.js'))?>
	<script>
		$('[data-fancybox]').fancybox({
			'onStart': function() {
				$("#fancybox-overlay").css({
					"position": "fixed",
					"width": "800px",
				});
			},
			autoSize: false,
			autoDimension: false,
			touch: false
		});

		function format(d) {
			// expandable data
			d = d.toString().split(',')[0];

			var div = $('<div/>')
				.addClass('loading')
				.text('Loading...');

			$.ajax({
				url: '<?= site_url(uriSegment(1) . '/expandableContent?id=') ?>' + d,
				dataType: 'json',
				success: function(json) {
					div
						.html(json)
						.removeClass('loading');
				}
			});

			return div;
		}

		function detailButton(id) {
			$.ajax({
				url: '<?= site_url(uriSegment(1) . '/getSingleData?id=') ?>' + id,
				type: 'GET',
			}).then(res => {
				res = JSON.parse(res);
				initializeDetailInput(res);
				$('#detailModal').modal('show');
			});
		}

		$(document).ready(function() {
			// hide sidebar menu onload page
			$('#main-wrapper').addClass('hide-sidebar');

			// expandable tr
			$('#example tbody').on('click', 'td.details-control', function() {
				var tr = $(this).closest('tr');
				var row = table.row(tr);
				var id = tr.find('.idValue').text();

				if (row.child.isShown()) {
					row.child.hide();
					tr.removeClass('shown');
				} else {
					row.child(format(row.data())).show();
					tr.addClass('shown');
				}
			});

			// table column resize
			$('th').mouseup(function() {
				$('.dataTables_scrollHeadInner').css('width', '100%');
				// $('.table').css('width','100%');

				setTimeout(function() {
					table.columns.adjust();
				}, 100);
			});

			$('#openMenu').click(function() {
				$('.dataTables_scrollHeadInner').css('width', '100%');
				// $('.table').css('width','100%');

				setTimeout(function() {
					table.columns.adjust();
				}, 300);
			});

			function reloadTable() {
				table.ajax.reload(null, false);
			}

			$('#selectAllButton').click(function() {
				// for (var key in allID) {
				// table.column(0).checkboxes.selected().s.push(allID[key]);
				// }

				var rows_selected = table.column(0).checkboxes.selected();
			});

			function deselectAll() {
				table.column(0).checkboxes.deselectAll();
			}

			function buildRequestStringData(form) {
				var select = form.find('select'),
					input = form.find('input'),
					requestString = '{';
				for (var i = 0; i < select.length; i++) {
					requestString += '"' + $(select[i]).attr('name') + '": "' + $(select[i]).val() + '",,';
				}
				if (select.length > 0) {
					requestString = requestString.substring(0, requestString.length - 1);
				}
				for (var i = 0; i < input.length; i++) {
					if ($(input[i]).attr('type') !== 'checkbox') {
						requestString += '"' + $(input[i]).attr('name') + '":"' + $(input[i]).val() + '",';
					} else {
						if ($(input[i]).attr('checked')) {
							requestString += '"' + $(input[i]).attr('name') + '":"' + $(input[i]).val() + '",';
						}
					}
				}
				if (input.length > 0) {
					requestString = requestString.substring(0, requestString.length - 1);
				}
				requestString += '}';
				return requestString;
			}

			$('#deselectButton').click(function() {
				table.column(0).checkboxes.deselectAll();
			});

			// ========= START OF ACTION BUTTON ============================================================
			$('#addButton').click(function() {
				setDefaultFormModalTitle();
				$('#form')[0].reset();
				$('.btn-scan').html('<i class="mr-2 mdi mdi-qrcode-scan"></i> SCAN');
			});

			$('#editButton').click(function() {
				var rows_selected = table.column(0).checkboxes.selected();

				if (rows_selected.length < 1)
					Swal.fire('Please select data first!');
				else
				if (rows_selected.length > 1)
					Swal.fire("You cannot update multiple data at once!");
				else {
					$.ajax({
						url: '<?= site_url(uriSegment(1) . '/getSingleData?id=') ?>' + rows_selected[0],
						type: 'GET',
					}).then(res => {
						console.log(res);
						initializeInput(res);
						$('#formModal').modal('show');
					});
				}
			});

			$('#detailButton').click(function() {
				var rows_selected = table.column(0).checkboxes.selected();

				if (rows_selected.length < 1)
					Swal.fire('Please select data first!');
				else
				if (rows_selected.length > 1)
					Swal.fire("You cannot view multiple data at once!");
				else {
					$.ajax({
						url: '<?= site_url(uriSegment(1) . '/getSingleData?id=') ?>' + rows_selected[0],
						type: 'GET',
					}).then(res => {
						res = JSON.parse(res);
						initializeDetailInput(res);
						$('#detailModal').modal('show');
					});
				}
			});

			// delete and hide button
			$('#hideButton').click(function() {
				var rows_selected = table.column(0).checkboxes.selected();

				if (rows_selected.length < 1) {
					Swal.fire('Please select data first!');
				} else {
					Swal.fire({
						title: 'Are you sure want to hide selected data?',
						text: "You can still view hidden data on the hidden list later.",
						type: 'warning',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'Yes, delete and hide it!'
					}).then((result) => {
						if (result.value) {
							Swal.fire(
								'Deleted!',
								'Your file has been deleted.',
								'success'
							)
						}
					});
				}
			});

			// delete forever
			$('#deleteButton').click(function() {
				var rows_selected = table.column(0).checkboxes.selected();

				selected = [];
				rows_selected.each(function(id) {
					selected.push(id);
				});

				if (rows_selected.length < 1) {
					Swal.fire('Please select data first!');
				} else {
					Swal.fire({
						title: 'Are you sure want to delete selected data?',
						text: "",
						type: 'warning',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'Yes, delete it!'
					}).then((result) => {
						if (result.value) {
							$.ajax({
								url: '<?= site_url(uriSegment(1) . '/delete?catCode=' . $catCode) ?>',
								type: 'POST',
								data: {
									ids: selected
								}
							}).then(res => {
								res = JSON.parse(res);
								
								if (res.queryResult) {
									<?php if (uriSegment(1) == 'location') { ?>
										if (res.data < 1) {
											Swal.fire('Warning!', 'Data gagal dihapus!', 'warning');
											reloadTable();
										} else {
											Swal.fire('Deleted!', 'Data berhasil dihapus!', 'success');
											reloadTable();
										}
										deselectAll();
									<?php } else {?>
										if (res.data.length > 0) {
											if (res.data.includes('0')) {
												Swal.fire('Warning!', 'Beberapa data gagal dihapus!', 'warning');
												reloadTable();
											} else {
												Swal.fire('Deleted!', 'Data berhasil dihapus!', 'success');
												reloadTable();
											}
											deselectAll();
										}
									<?php } ?>
								} else {
									Swal.fire('Error!', 'Something went wrong, please try again later!', 'error');
								}
							});
						}
					});
				}
			});

			$('#importButton').click(function() {
				setDefaultImportModalTitle();
				$('#form')[0].reset();
			});

			$('#exportButton').click(function() {
				var rows_selected = table.column(0).checkboxes.selected();

				selected = '';
				rows_selected.each(function(id) {
					selected += id + ',';
				});

				if (rows_selected.length < 1) {
					Swal.fire('Please select data first!');
				} else {
					<?php if (uriSegment(1) == 'master') { ?>
						var fileName = 'Catalogue <?= $catCode ?>, ' + moment().format('DD/MM/YY hh:mm:ss');
					<?php } else if (uriSegment(1) == 'assets') { ?>
						var fileName = 'Asset <?= $catCode ?>, ' + moment().format('DD/MM/YY hh:mm:ss');
					<?php } ?>
					var xhr = new XMLHttpRequest();
					xhr.open('GET', '<?= site_url(uriSegment(1) . '/export?catCode=' . $catCode . '&codes=') ?>' + selected, true);
					xhr.responseType = 'blob';

					xhr.onprogress = function(pe) {
						if (pe.lengthComputable) {
							// console.log((pe.loaded / pe.total) * 100);
						}
					};

					xhr.onload = function(e) {
						if (this.status == 200) {
							var blob = this.response;

							if (navigator.appVersion.toString().indexOf('.NET') > 0) {
								// IE 10+
								window.navigator.msSaveBlob(blob, fileName);
							} else {
								// Firefox, Chrome
								var a = document.createElement("a");
								var blobUrl = window.URL.createObjectURL(new Blob([blob], {
									type: blob.type
								}));
								document.body.appendChild(a);
								a.style = "display: none";
								a.href = blobUrl;
								a.download = fileName;
								a.click();
							}
						}
					};

					xhr.send();
				}
			});

			$('#rekapButton').click(function() {
				var rows_selected = table.column(0).checkboxes.selected();

				selected = [];
				rows_selected.each(function(id) {
					selected.push(id);
				});

				if (rows_selected.length < 1) {
					Swal.fire('Please select data first!');
				} else {
					$.ajax({
						url: '<?= site_url(uriSegment(1) . '/rekap') ?>',
						type: 'POST',
						data: {
							'catCode': '<?= $catCode ?>',
							'locType': '<?=uriSegment(3)?>',
							'codes': selected,
						},
					}).then(res => {
						res = JSON.parse(res);
						var w = window.open('', 'popupWindow', "width=1000, height=500, scrollbars=yes");
						var $w = $(w.document.body);
						$w.html(res);
					});
				}
			});

			$('#rekapExcelButton').click(function() {
				var rows_selected = table.column(0).checkboxes.selected();

				selected = [];
				rows_selected.each(function(id) {
					selected.push(id);
				});

				if (rows_selected.length < 1) {
					Swal.fire('Please select data first!');
				} else {
					var data = new FormData();
					data.append('catCode', '<?= $catCode ?>');
					data.append('locType', '<?= uriSegment(3) ?>');
					data.append('codes', selected);

					var xhr = new XMLHttpRequest();
					xhr.open('POST', '<?= site_url(uriSegment(1) . '/rekap?mode=xlsx') ?>', true);
					xhr.responseType = 'blob';

					xhr.onprogress = function(pe) {
						if (pe.lengthComputable) {
							// console.log((pe.loaded / pe.total) * 100);
						}
					};

					xhr.onload = function(e) {
						if (this.status == 200) {
							var blob = this.response;

							if (navigator.appVersion.toString().indexOf('.NET') > 0) {
								// IE 10+
								window.navigator.msSaveBlob(blob, fileName);
							} else {
								// Firefox, Chrome
								var a = document.createElement("a");
								var blobUrl = window.URL.createObjectURL(new Blob([blob], {
									type: blob.type
								}));
								document.body.appendChild(a);
								a.style = "display: none";
								a.href = blobUrl;
								a.download = 'Rekap Excel <?= ($catCode ? instrumentCategory($catCode) : '') ?>, per tgl ' + moment().locale('id').format('DD-MM-Y') + '.xlsx';
								a.click();
							}
						}
					};

					xhr.send(data);
				}
			});

			$('#rekapPDFButton').click(function() {
				var rows_selected = table.column(0).checkboxes.selected();

				selected = [];
				rows_selected.each(function(id) {
					selected.push(id);
				});

				if (rows_selected.length < 1) {
					Swal.fire('Please select data first!');
				} else {
					$.ajax({
						url: '<?= site_url(uriSegment(1) . '/rekap?mode=pdf') ?>',
						type: 'POST',
						data: {
							'catCode': '<?= $catCode ?>',
							'locType': '<?= uriSegment(3) ?>',
							'codes': selected
						},
					}).then(res => {
						res = JSON.parse(res);

						var link = document.createElement('a');
						link.href = window.URL = '<?= base_url() ?>' + "exportacion.pdf";
						link.download = res.filename;
						link.click();
					});
				}
			});

			$('#barcodeButton').click(function() {
				var rows_selected = table.column(0).checkboxes.selected();

				selected = [];
				rows_selected.each(function(id) {
					selected.push(id);
				});

				if (rows_selected.length < 1) {
					Swal.fire('Please select data first!');
				} else {
					$.ajax({
						url: '<?= site_url(uriSegment(1) . '/generateBarcode') ?>',
						type: 'POST',
						data: {
							'codes': selected
						},
					}).then(res => {
						var w = window.open('', 'popupWindow', "width=800, height=400, scrollbars=yes");
						var $w = $(w.document.body);
						$w.html(res);
					});
				}
			});

			$('#filterForm').submit(function(e) {
				e.preventDefault();

				filters.filters = $(this).serialize();
				reloadTable();
				$('#advancedFilterForm')[0].reset();
			});

			$('#advancedFilterForm').submit(function(e) {
				e.preventDefault();

				// refresh the table;
				filters.filters = $(this).serializeArray();
				reloadTable();
				$('#advancedFilterModal').modal('hide');
				$('#filterForm')[0].reset();
			});

			$('#clearFilterButton').click(function() {
				filters.filters = '';
				$('#filterForm')[0].reset();
				$('#advancedFilterForm')[0].reset();
				$('.select2').val(null).trigger('change');
				$('.searchField').val('all').trigger('change');
				table.search('').draw();
				reloadTable();
			});

			// $('#advancedFilterForm').bind('reset', function() {
			//     $('.select2').val(null).trigger('change');
			// });

			$('#reloadButton').click(function() {
				reloadTable();
			});
			// ========= END OF ACTION BUTTON ============================================================

			$('#form').submit(function(e) {
				e.preventDefault();

				$.ajax({
					url: '<?= site_url(uriSegment(1) . '/form') ?>',
					type: 'POST',
					data: new FormData($(this)[0]),
					contentType: false,
					processData: false,
					cache: false,
				}).then(res => {
					res = JSON.parse(res);

					<?php if (uriSegment(1) == 'location') { ?>
						if (res[0] !== undefined) {
							if (res[0].data > 0) Swal.fire('Success!', res.message, 'success');
							else Swal.fire('Error!', res.message, 'error');
						} else {
							if (res.data > 0) Swal.fire('Success!', res.message, 'success');
							else Swal.fire('Error!', res.message, 'error');
						}
					<?php } else { ?>
						if (res.data.length > 0) Swal.fire('Success!', res.message, 'success');
						else Swal.fire('Error!', res.message, 'error');
					<?php } ?>

					reloadTable();
					$('#formModal').modal('hide');
				});
			});
		});
	</script>

	<!-- Bootstrap tether Core JavaScript -->
	<?= js(assets('design/ample/assets/libs/popper.js/dist/umd/popper.min.js')) ?>
	<?= js(assets('design/ample/assets/libs/bootstrap/dist/js/bootstrap.min.js')) ?>
	<?= js(assets('design/ample/dist/js/app.min.js')) ?>
	<?= js(assets('design/ample/dist/js/app.init.minimal.js')) ?>
	<?= js(assets('design/ample/dist/js/app-style-switcher.js')) ?>
	<?= js(assets('design/ample/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js')) ?>
	<?= js(assets('design/ample/assets/extra-libs/sparkline/sparkline.js')) ?>
	<?= js(assets('design/ample/dist/js/waves.js')) ?>
	<?= js(assets('design/ample/dist/js/sidebarmenu.js')) ?>
	<?= js(assets('design/ample/dist/js/custom.min.js')) ?>
	<?=js(assets('dist/js/bootstrap/bootstrap-datepicker.min.js'))?>
	<script>
		// Date Picker
		jQuery('.mydatepicker, #datepicker, .input-group.date').datepicker();
		jQuery('#datepicker-autoclose').datepicker({
			autoclose: true,
			todayHighlight: true
		});
		jQuery('#date-range').datepicker({
			toggleActive: true
		});
		jQuery('#datepicker-inline').datepicker({
			todayHighlight: true
		});
	</script>
	<?= js(assets('design/ample/assets/libs/jquery.repeater/jquery.repeater.min.js')) ?>
	<?= js(assets('design/ample/assets/extra-libs/jquery.repeater/repeater-init.js')) ?>
	<?= js(assets('design/ample/assets/extra-libs/jquery.repeater/dff.js')) ?>
	<?= js(assets('design/ample/assets/extra-libs/DataTables/datatables.min.js')) ?>
	<?=js(assets('dist/js/datatables/dataTables.fixedHeader.min.js'))?>
	<?= js(assets('design/ample/dist/js/pages/datatable/datatable-basic.init.js')) ?>
	<?=js(assets('dist/js/datatables/ColReorderWithResize.js'))?>
	<?=js(assets('dist/js/datatables/dataTables.select.min.js'))?>
	<script src="http://gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/js/dataTables.checkboxes.min.js"></script>
	<?=js(assets('dist/js/sweetalert/sweetalert2.js'))?>
	<?= js(assets('design/ample/assets/libs/moment/moment.js')) ?>
	<?=js(assets('dist/js/moment/id.js'))?>
	<?= js(assets('design/ample/assets/libs/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker-custom.js')) ?>
	<script>
		$(document).on('click', '.mydatepicker', function() {
			$(this).datepicker({
				changeMonth: true,
				changeYear: true,
			}).focus();
			$(this).removeClass('datepicker');
		});

		$(document).on('click', '.bootstrapMaterialDatePicker', function() {
			$(this).bootstrapMaterialDatePicker({
				weekStart: 0,
				format: 'DD/MM/YYYY',
				time: false,
			}).focus();
			$(this).removeClass('bootstrapMaterialDatePicker');
		});
	</script>
	<?= js(assets('design/ample/assets/libs/select2/dist/js/select2.full.min.js')) ?>
	<?= js(assets('design/ample/assets/libs/select2/dist/js/select2.min.js')) ?>
	<?= js(assets('design/ample/dist/js/pages/forms/select2/select2.init.js')) ?>
	<?= js(assets('dist/js/autocomplete-combobox.js')) ?>
	<?=js(assets('dist/js/number-divider/number-divider.js'))?>
	<script>
		$('.currency').keyup(function() {
			$(this).val(formatRupiah(this.value));
		})

		function formatRupiah(angka, prefix) {
			var number_string = angka.replace(/[^,\d]/g, '').toString(),
				split = number_string.split(','),
				sisa = split[0].length % 3,
				rupiah = split[0].substr(0, sisa),
				ribuan = split[0].substr(sisa).match(/\d{3}/gi);

			if (ribuan) {
				separator = sisa ? '.' : '';
				rupiah += separator + ribuan.join('.');
			}

			rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
			return rupiah;
		}

		$.fn.dataTableExt.sErrMode = 'none'
	</script>
</body>
</html>
