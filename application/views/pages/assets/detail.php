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
                        <?php loadView($modulePath.'/detail_'.uriSegment(3))?>
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

    function setDefaultDetailModalTitle() {
    }

    function initializeDetailInput(res) {
        $('#detailModalTitle').html('Detail Aset <b>' + res.set.data[0].catCode + '-' + (res.set.data[0].assetParent ? res.set.data[0].assetParent + '-' : '') + res.set.data[0].idAsset + ' | ' + res.set.data[0].assetName + '</b>');

        initializeDetailAdv(res);
    }

</script>