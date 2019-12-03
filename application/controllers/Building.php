<?php

class Building extends INS_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->selectedColumns = array('idUser' => 'ID', 'userName' => 'Username', 'userFullName' => 'Nama', 'userMail' => 'Email', 'userPhone' => 'Phone', 'roleName' => 'Role');
        $this->searchableColumns = array('userName' => 'Username', 'userFullName' => 'Nama', 'userMail' => 'Email');
        $this->totalUnusedColumn = 3;
    }

    public function index() {
        $data['columns']            = $this->selectedColumns;
        $data['searchableColumns']  = $this->searchableColumns;
        $data['totalUnusedColumn'] = $this->totalUnusedColumn;

        render('module', $data);
    }

    public function data() {
        $parameters = initParameters($this->selectedColumns, $this->totalUnusedColumn);
        $result = runAPI('users', 'GET', $parameters);

        generateData($this->selectedColumns, $result);
    }

    public function getSingleData() {
        $pk = inputGet('id');

        $parameters = array();
        $parameters['idUser'] = $pk;
        
        $result = runAPI('users', 'GET', $parameters);
        jsonE($result['data']);
    }

    public function form() {
        $pk = inputPost('idUser');
        $data = inputPost();

        if ($pk)
            $response = runAPI('users/'.$pk, 'PATCH', NULL, $data);
        else {
            unset($data['idUser'], $data['oldPass']);
            $response = runAPI('users/create', 'POST', NULL, $data);
        }

        jsonE($response);
    }

}
