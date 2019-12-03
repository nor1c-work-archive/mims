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
				<form id="form" class="form-horizontal r-separator" enctype="multipart/form-data" onkeydown="return event.key !== 'Enter';">

					<!-- START OF FIRST INPUT GROUP -->
					<div>
						<div class="card-body">
							<table class="table table-striped" style="background:#fff;">
								<fieldset>
									<legend>INFORMASI USER</legend>

									<div class="card-body">
										<input type="hidden" id="idUser" name="user[1][idUser]" value="0">

										<?php
										input('text', 'Username', 'userName', 'user[1][userName]', '', '8', ' required ');
										input('text', 'Nama Lengkap', 'userFullName', 'user[1][userFullName]', '', '8', ' required ');
										input('email', 'Email Address', 'userMail', 'user[1][userMail]', '', '8', ' required ');
										input('text', 'Phone Number', 'userPhone', 'user[1][userPhone]', '', '8', ' required ');
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
		$('#formModalTitle').html('Tambah User Baru');
	}

	function initializeInput(res) {
		res = JSON.parse(res)[0];

		$('#formModalTitle').html('Ubah Informasi <b>' + res.userFullName + '</b>');

		for (var key in res) {
			$('#form #'+key).val(res[key]);
		}
	}

</script>
