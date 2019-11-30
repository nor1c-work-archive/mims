<?php

class Accesses extends INS_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->selectedColumns = array('idRole' => 'ID', 'roleName' => 'Role', 'roleDesc' => 'Description');
        $this->searchableColumns = array('roleName' => 'Role', 'roleDesc' => 'Description');
        $this->totalUnusedColumn = 2;
    }

    public function index() {
        $data['columnDefinition']   = array($this->selectedColumns, $this->searchableColumns, $this->totalUnusedColumn);

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
        echo json_encode($result['data']);
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

        echo json_encode($response);
    }

}
