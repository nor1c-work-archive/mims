<?php

class Index extends INS_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->selectedColumns = array('idUser' => 'ID', 'userName' => 'Username', 'userFullName' => 'Nama', 'userMail' => 'Email', 'userPhone' => 'Phone', 'roleName' => 'Role');
        $this->searchableColumns = array('userName' => 'Username', 'userFullName' => 'Nama', 'userMail' => 'Email');
        $this->totalUnusedColumn = 3;
    }

    public function index() {
        $page = '';
        
        if (uriSegment(1))
            $page = uriSegment(1);
        if (uriSegment(2))
            $page .= '/'.uriSegment(2);

        $data['columns']            = $this->selectedColumns;
        $data['searchableColumns']  = $this->searchableColumns;

        render($page, $data);
    }

    public function tracking() {
        render('tracking');
    }

}
