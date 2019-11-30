<div class="button-group">
    <button id="reloadButton" type="button" class="btn waves-effect waves-light btn-dark" data-toggle="tooltip" data-placement="bottom" title="Reload Data"><i class="fas fa-undo"></i></button>
    <button id="selectAllButton" type="button" class="btn waves-effect waves-light btn-dark" data-toggle="tooltip" data-placement="bottom" title="Select All"><i class="fas fa-square"></i></button>
    <button id="deselectButton" type="button" class="btn waves-effect waves-light btn-dark" data-toggle="tooltip" data-placement="bottom" title="Deselect all selected checkbox"><i class="fas fa-minus-square"></i></button>
    <br>
    <button id="addButton" type="button" class="btn waves-effect waves-light btn-info" data-toggle="modal" data-target="#formModal">
        <i class="fas fa-plus" style="padding-right:8px;"></i>ADD
    </button>
    <button id="editButton" type="button" class="btn waves-effect waves-light btn-info"><i class="far fa-edit" style="padding-right:8px;"></i>EDIT/UPDATE</button>
    <!-- <button id="approveButton" type="button" class="btn waves-effect waves-light btn-primary"><i class="mdi mdi-file-check" style="padding-right:8px;"></i>APPROVE</button>
    <button id="returnButton" type="button" class="btn waves-effect waves-light btn-primary"><i class="mdi mdi-keyboard-return" style="padding-right:8px;"></i>RETURN</button>
    <button id="confirmButton" type="button" class="btn waves-effect waves-light btn-primary"><i class="mdi mdi-account-check" style="padding-right:8px;"></i>CONFIRM</button> -->
    <button id="detailButton" type="button" class="btn waves-effect waves-light btn-info"><i class="fas fa-search-plus" style="padding-right:8px;"></i>DETAIL</button>
    <!-- <div class="btn-group">
        <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-trash" style="padding-right:8px;"></i>DELETE/NON-ACTIVATE
        </button>
        <div class="dropdown-menu">
            <a id="deleteButton" class="dropdown-item" href="javascript:void(0)"><i class="fas fa-trash-alt" style="padding-right:8px;"></i>DELETE</a>
            <a id="hideButton" class="dropdown-item" href="javascript:void(0)"><i class="fas fa-eye-slash" style="padding-right:8px;"></i>NON-ACTIVATE</a>
        </div>
    </div> -->
    <button id="deleteButton" type="button" class="btn waves-effect waves-light btn-danger"><i class="fas fa-trash-alt" style="padding-right:8px;"></i>DELETE</button>
    <div class="btn-group">
        <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-print" style="padding-right:8px;"></i>CETAK
        </button>
        <div class="dropdown-menu">
            <a id="rekapButton" class="dropdown-item" href="javascript:void(0)"><i class="fas fa-list" style="padding-right:8px;"></i>REKAP</a>
            <!-- <a id="rekapExcelButton" class="dropdown-item" href="javascript:void(0)"><i class="fas fa-file-excel" style="padding-right:8px;"></i>REKAP (EXCEL)</a> -->
            <a id="rekapPDFButton" class="dropdown-item" href="javascript:void(0)"><i class="fas fa-file-pdf" style="padding-right:8px;"></i>REKAP (PDF)</a>
            <div class="dropdown-divider"></div>
            <a id="barcodeButton" class="dropdown-item" href="javascript:void(0)"><i class="fas fa-qrcode" style="padding-right:8px;"></i>LABEL/BARCODE</a>
        </div>
    </div>
    <div class="btn-group">
        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-file-excel" style="padding-right:8px;"></i>IMPORT/EXPORT
        </button>
        <div class="dropdown-menu">
            <a id="importButton" class="dropdown-item" href="javascript:void(0)" data-toggle="modal" data-target="#importModal"><i class="far fa-arrow-alt-circle-up" style="padding-right:8px;"></i>IMPORT</a>
            <a id="exportButton" class="dropdown-item" href="javascript:void(0)" data-toggle="modal" data-target="#exportModal"><i class="far fa-arrow-alt-circle-down" style="padding-right:8px;"></i>EXPORT</a>
        </div>
    </div>
</div>
