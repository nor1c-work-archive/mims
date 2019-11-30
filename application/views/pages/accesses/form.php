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
                         )
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
        <div class="modal-body bg-light">
            <form id="form" class="form-horizontal striped-rows b-form">
                <div class="card-body">
                    <!-- <h4 class="card-title"></h4> -->
                    
                    <input type="hidden" name="idUser" value="0">

                    <div class="form-group row">
                        <div class="col-sm-2">
                            <div class="b-label">
                                <label for="inputEmail3" class="control-label col-form-label">Role Name</label>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="" name="userName" placeholder="">
                        </div>
                    </div>

                    <?php
                        foreach ($menu as $menuName => $menuButton) {
                            echo    '<div class="form-group row">
                                        <div class="col-sm-2">
                                            <div class="b-label">
                                                <label for="inputEmail3" class="control-label col-form-label">'.$menuName.'</label>
                                            </div>
                                        </div>';
                            $no = 1;
                            foreach ($menuButton as $buttonName => $buttonUrl) {
                                $class[$no] = strpos($buttonUrl, '/') !== FALSE ? str_replace(' ', '-', strtolower($menuName)) . '-slashes' : str_replace(' ', '-', strtolower($menuName));
                                echo    '<div class="custom-control custom-checkbox" style="padding-right:20px;margin-top:5px;">
                                            <input type="checkbox" class="custom-control-input '.$class[$no].'" id="'.$buttonUrl.'" value="'.$buttonUrl.'" onClick="disableRelated(\''.str_replace(' ', '-', strtolower($menuName)).'\')">
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

<script>

    function setDefaultModalTitle() {
        $('#oldPass').hide();
        $('#modalTitle').html('Tambah User');
    }

    function initializeInput(res) {
        $('#oldPass').show();
        $('#modalTitle').html('Edit User <b>' + res.userFullName + '</b>');

        $('#form input[name=idUser]').val(res.idUser);
        $('#form input[name=userName]').val(res.userName);
        $('#form input[name=userFullName]').val(res.userFullName);
        $('#form input[name=userMail]').val(res.userMail);
    }

    function disableRelated(menu) {
        if($('.'+menu).is(':checked')) {
            $('.'+menu+'-slashes').removeAttr('disabled', true);
        } else {
            $('.'+menu+'-slashes').prop('checked', false);
            $('.'+menu+'-slashes').attr('disabled', true);
        }
    }

</script>