<?php

class Auth_model extends CI_Model {

    public function __construct() {
        parent::__construct();

        // superuser account
        // $this->username = 'admin';
        // $this->password = 'admin';
    }

    // public function signIn($data) {
    //     if ($data['username'] == $this->username && $data['password'] == $this->password) {
    //         $this->setSession($data);
    //         return TRUE;
    //     } else {
    //         return FALSE;
    //     }
    // }

    // public function setSession($data) {
    //     $sessionData = array(
    //         'username'      => $data['username'],
    //         'AUTHENTICATED' => TRUE
    //     );

    //     setSession($sessionData);
    // }

    public function refreshToken($result) {
        $sessions = array(
            'userID'        => sessionData('idUser'),
            'username'      => sessionData('userName'),
            'userFullName'  => sessionData('userFullName'),
            'userMail'      => sessionData('userMail'),
            'token'         => $result['token'],
            'refreshToken'  => $result['refToken'],
            'AUTHENTICATED' => TRUE,
        );   

        setSession($sessions);
    }

}