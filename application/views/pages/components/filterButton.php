<div class="button-group">
	<div class="form-inline">
		<form id="filterForm" class="form-horizontal r-separator" onkeydown="return event.key != 'Enter';" method="post" action="#">
			<span id="simpleFilterArea">
				<?php if (isset($withFilterMode) && $withFilterMode) { ?>
					<!-- <select name="searchMode1" id="" class="form-control">
                        <option value="LIKEAND">%</option>
                        <option value="EXACTOR">=</option>
                        <option value="LESSTHAN"><</option>
                        <option value="MORETHAN">></option>
                    </select> -->
				<?php } ?>
				<select class="form-control select2 searchField" name="searchField1" id="searchField1" style="width:180px">
					<option value="all" selected>All</option>
					<?php
					foreach ($searchableColumns as $key => $value) {
						echo "<option value='" . (isset(explode('.', $key)[1]) ? explode('.', $key)[1] : $key) . "'>" . ucwords(strtolower($value)) . "</option>";
					}
					?>
				</select>
				<input type="text" name="searchKeyword1" class="form-control" id="placeholder" placeholder="Filter Keyword">

				<?php if (isset($withFilterMode) && $withFilterMode) { ?>
					<!-- <select name="searchMode2" id="" class="form-control">
                        <option value="LIKEAND">%</option>
                        <option value="EXACTOR">=</option>
                        <option value="LESSTHAN"><</option>
                        <option value="MORETHAN">></option>
                    </select> -->
				<?php } ?>
				<select class="form-control select2 searchField" name="searchField2" id="searchField2" style="width:180px">
					<option value="all" selected >All</option>
					<?php
					foreach ($searchableColumns as $key => $value) {
						echo "<option value='" . (isset(explode('.', $key)[1]) ? explode('.', $key)[1] : $key) . "'>" . ucwords(strtolower($value)) . "</option>";
					}
					?>
				</select>
				<input type="text" name="searchKeyword2" class="form-control" id="placeholder" placeholder="Filter Keyword">

				<?php if (isset($withExtraFilter) && $withExtraFilter) {
					loadView('pages/' . uriSegment(1) . '/extraFilters');
				} ?>

				<button id="filterButton" type="submit" class="btn waves-effect waves-light btn-info" style="margin-top:5px;margin-right:5px;"><i class="fas fa-search" style="padding-right:8px;"></i>FILTER</button>
			</span>
			<span id="advancedFilterArea"></span>
			<button id="advancedFilterButton" type="button" class="btn waves-effect waves-light btn-info" style="margin-top:5px;margin-right:5px;padding-right:0 !important;padding-left:0 !important;" data-toggle="modal" data-target="#advancedFilterModal">
				<span style="width:100%;padding:15px;" data-toggle="tooltip" data-placement="bottom" title="Show advanced filter">
					<i class="fas fa-list"></i>
				</span>
			</button>
			<button type="button" class="btn btn-warning" id="clearFilterButton" style="margin-top:5px;"><i class="mdi mdi-filter-remove" style="padding-right:8px;"></i>Clear Filters</button>
		</form>
	</div>
</div>
<script>
	$(document).ready(function() {
		$(".searchField").select2({
			minimumResultsForSearch: -1
		});
	});
</script>
