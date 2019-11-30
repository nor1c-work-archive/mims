<?php
    $menu = array(
        'Role Access' => array(
            'Enable Menu'   => 'roles',
            'Add'           => 'roles/add',
            'Edit'          => 'roles/edit',
            'Delete'        => 'roles/delete',
            'Detail'        => 'roles/detail',
            'Print'         => 'roles/print',
            'Import'        => 'roles/import',
            'Export'        => 'roles/export',
        ),
    );
?>

<!-- Modal -->
<div class="modal fade bd-example-modal-xl" id="formModal" role="dialog" aria-labelledby="formModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalTitle"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="form" class="form-horizontal r-separator">
                <div class="card-body">
                    <input type="hidden" id="idRecord" name="idRole" value="0">
                    <input type="hidden" name="idRs" value="<?=sessionData('hospitalID')?>">

                    <h6 class="card-title card-title-top">Role Detail</h6>
                    <div class="form-group row align-items-center mb-0">
                        <label for="inputEmail3" class="col-2 control-label col-form-label">Role Name</label>
                        <div class="col-4 border-left pb-2 pt-2">
                            <input type="text" class="form-control" id="inputEmail3" name="roleName">
                        </div>
                    </div>
                    <div class="form-group row align-items-center mb-0">
                        <label for="inputEmail3" class="col-2 control-label col-form-label">Description</label>
                        <div class="col-9 border-left pb-2 pt-2">
                            <input type="text" class="form-control" id="inputEmail3" name="roleDesc">
                        </div>
                    </div>

                    <h6 class="card-title">Access</h6>
                    <?php
                        foreach ($menu as $menuName => $menuButton) {
                            echo    '<div class="form-group row align-items-center mb-0">
                                        <label for="inputEmail3" class="col-2 control-label col-form-label">'.$menuName.'</label>';
                            $no = 1;
                            foreach ($menuButton as $buttonName => $buttonUrl) {
                                $class[$no] = strpos($buttonUrl, '/') !== FALSE ? str_replace(' ', '-', strtolower($menuName)) . '-slashes' : str_replace(' ', '-', strtolower($menuName));
                                echo    '<div class="custom-control custom-checkbox" style="margin-left:15px;padding-right:20px;margin-top:5px;">
                                            <input type="checkbox" class="custom-control-input '.$class[$no].'" id="'.$buttonUrl.'" value="'.$buttonUrl.'" name="access[]">
                                            <label class="custom-control-label" for="'.$buttonUrl.'">'.$buttonName.'</label>
                                        </div>';
                                $no++;
                            }
                            echo '</div>';
                        }
                    ?>

                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-info waves-effect waves-light">Save</button>
                <button type="reset" class="btn btn-secondary waves-effect waves-light">Clear</button>
                <button type="button" class="btn btn-dark waves-effect waves-light" data-dismiss="modal">Close</button>
            </div>
        </form>
        </div>
    </div>
</div>
<!-- onClick="disableRelated(\''.str_replace(' ', '-', strtolower($menuName)).'\')" -->
<script>

    function setDefaultModalTitle() {
        $('#oldPass').hide();
        $('#modalTitle').html('Add New Role');
    }

    function initializeInput(res) {
        $('#oldPass').show();
        $('#modalTitle').html('Edit Role <b>' + res.userFullName + '</b>');

        $('#form #idRecord').val(res.idRole);
        for(var key in res) {
            $('#form input[name="'+key+'"]').val(res[key]);
        }
    }

    // function disableRelated(menu) {
    //     if($('.'+menu).is(':checked')) {
    //         $('.'+menu+'-slashes').removeAttr('disabled', true);
    //     } else {
    //         $('.'+menu+'-slashes').prop('checked', false);
    //         $('.'+menu+'-slashes').attr('disabled', true);
    //     }
    // }

</script>