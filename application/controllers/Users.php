<?php

class Users extends INS_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->selectedColumns = array('idUser' => 'ID', 'userName' => 'Username', 'userFullName' => 'Nama', 'userMail' => 'Email', 'userPhone' => 'Phone', 'roleName' => 'Role');
        $this->searchableColumns = array('userName' => 'Username', 'userFullName' => 'Nama', 'userMail' => 'Email');
        $this->totalUnusedColumn = 2;
    }

    public function index() {
        $data['columnDefinition']   = array($this->selectedColumns, $this->searchableColumns, $this->totalUnusedColumn);

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
        echo json_encode($result['data']);
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

        echo json_encode($response);
    }

}
