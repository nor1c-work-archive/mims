<!-- Modal -->
<div class="modal fade bd-example-modal-xl" id="exampleModalLong" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Forms</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body bg-light">
            <form class="form-horizontal striped-rows b-form">
                <div class="card-body">
                    <h4 class="card-title">Asset Information</h4>
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <div class="b-label">
                                <label for="inputEmail3" class="control-label col-form-label">Input Text</label>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="inputEmail3" placeholder="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <div class="b-label">
                                <label for="inputEmail3" class="control-label col-form-label">Textarea</label>
                            </div>
                        </div>
                        <div class="col-sm-6">
                                <div class="form-group">
                                    <textarea class="form-control" rows="3"></textarea>
                                </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <div class="b-label">
                                <label for="inputEmail3" class="control-label col-form-label">Item Modal Selector</label>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="inputEmail3" placeholder="Click on the button on the right">
                        </div>
                        <a class="btn btn-info" style="color:#fff;" data-fancybox data-type="ajax" data-src="<?=site_url('pickSomethingDummy')?>" href="javascript:;" data-toggle="tooltip" data-placement="right" title="Pilih Kode SIMAK">
                            <i class="fas fa-table"></i>
                        </a>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <div class="b-label">
                                <label for="inputEmail3" class="control-label col-form-label">DatePicker</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="icon-calender"></i></span>
                                </div>
                                <input type="text" class="form-control" id="datepicker-autoclose" placeholder="mm/dd/yyyy">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <div class="b-label">
                                <label for="inputEmail3" class="control-label col-form-label">CheckBoxes</label>
                            </div>
                        </div>
                        <div class="col-sm-9">
                            <div class="form-check form-check-inline">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="customCheck1">
                                    <label class="custom-control-label" for="customCheck1">CheckBox 1</label>
                                </div>
                                
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="customCheck2">
                                    <label class="custom-control-label" for="customCheck2">CheckBox 2</label>
                                </div>
                                
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="customCheck3">
                                    <label class="custom-control-label" for="customCheck3">CheckBox 3</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <div class="b-label">
                                <label for="inputEmail3" class="control-label col-form-label">Select Option</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <select class="form-control" id="exampleFormControlSelect1">
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <div class="b-label">
                                <label for="inputEmail3" class="control-label col-form-label">File Input</label>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="inputGroupFile04">
                                    <label class="custom-file-label" for="inputGroupFile04">Choose file</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <div class="b-label">
                                <label for="inputEmail3" class="control-label col-form-label">Input Success</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control is-valid" id="inputSuccess1">
                            <div class="valid-feedback">
                                Message
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <div class="b-label">
                                <label for="inputEmail3" class="control-label col-form-label">Input Danger</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control is-invalid" id="inputDanger1">
                            <div class="invalid-feedback">
                                Message
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <div class="b-label">
                                <label for="inputEmail3" class="control-label col-form-label">Currency</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <div class="b-label">
                                <label for="inputEmail3" class="control-label col-form-label">Multiple File Input</label>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="email-repeater form-group">
                                <div data-repeater-list="repeater-group">
                                    <div data-repeater-item="" class="row mb-3">
                                        <div class="col-md-10">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="customFile">
                                                <label class="custom-file-label" for="customFile">Choose file</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <button data-repeater-delete="" class="btn btn-danger waves-effect waves-light" type="button"><i class="ti-close"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" data-repeater-create="" class="btn btn-info waves-effect waves-light">Add More File
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group row" style="margin-bottom:60px;">
                        <div class="col-sm-3">
                            <div class="b-label">
                                <label for="inputEmail3" class="control-label col-form-label">Text Editor</label>
                            </div>
                        </div>
                        <div class="col-sm-9">
                            <div id="editor"></div>
                        </div>
                    </div>
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