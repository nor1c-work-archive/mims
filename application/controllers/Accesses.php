<?php

class Accesses extends INS_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->selectedColumns 		= ['idRole' => 'ID', 'roleName' => 'Role', 'roleDesc' => 'Description'];
        $this->searchableColumns 	= ['roleName' => 'Role', 'roleDesc' => 'Description'];
        $this->totalUnusedColumn 	= 2;
    }

    public function index() {
        $data['columnDefinition']   = [$this->selectedColumns, $this->searchableColumns, $this->totalUnusedColumn];

        render('module', $data);
    }

    public function data() {
        $parameters = initParameters($this->selectedColumns, $this->totalUnusedColumn);
        $result = runAPI('role', 'GET', $parameters);

        generateData($this->selectedColumns, $result);
    }

    public function getSingleData() {
        $pk = inputGet('id');

        $parameters = array();
        $parameters['idRole'] = $pk;
        
        $result = runAPI('role', 'GET', $parameters);
        jsonE($result['data']);
    }

    public function form() {
        $pk = inputPost('idRole');
        $data = inputPost();

        if ($pk)
            $response = runAPI('role/'.$pk, 'PATCH', NULL, $data);
        else {
            unset($data['idRole'], $data['oldPass']);
            $response = runAPI('role/create', 'POST', NULL, $data);
        }

        jsonE($response);
    }

}
