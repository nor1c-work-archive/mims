<?php

class Auth_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function refreshToken($result) {
        $sessions = [
            'userID'        => sessionData('idUser'),
            'username'      => sessionData('userName'),
            'userFullName'  => sessionData('userFullName'),
            'userMail'      => sessionData('userMail'),
            'token'         => $result['token'],
            'refreshToken'  => $result['refToken'],
            'AUTHENTICATED' => TRUE,
        ];

        setSession($sessions);
    }

}
