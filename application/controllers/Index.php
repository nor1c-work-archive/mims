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
        $data['all']				= runAPI('asset/list', 'GET');

        $data['countMIS'] = 0;
        $data['countMIP'] = 0;
        $data['countMIC'] = 0;
        foreach ($data['all']['data'] as $key => $asset) {
        	if ($asset['catCode'] == env('C_CONTAINER'))
        		$data['countMIC'] += 1;
			else if ($asset['catCode'] == env('C_SET'))
				$data['countMIS'] += 1;
			else if ($asset['catCode'] == env('C_PIECE'))
				$data['countMIP'] += 1;
		}

        render($page, $data);
    }

    public function tracking() {
        render('tracking');
    }

}
