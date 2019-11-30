<?php

class Authentication extends INS_Controller {
    
    public function __construct() {
        parent::__construct();
        loadModel($this->authentication);
    }

    public function signIn() {
        // $data = array(
        //     'umail'     => inputPost('umail'),
        //     'pwd'       => inputPost('pwd'),
        // );

        $data = array(
            'umail'     => 'workmail.fauzi@gmail.com',
            'pwd'       => 'fauzi123',
        );

        $result = runAPI('user/login', 'POST', NULL, $data);

        if ($result['queryResult']) {
            setUserCookie($result);
        } else {
            setFlashData('error', 'Gagal Login, Silahkan coba lagi!');
        }

        if (sessionData('lastVisitedPage') !== null)
            redirect(html_entity_decode(sessionData('lastVisitedPage')));
        else
            redirect();
    }

    public function signOut() {
        // cookieDestroy();
        sessionDestroy();
        redirect();
    }

    public function refreshToken() {
        $result = refreshTokenifTokenExpired('https://miis-api.samrs.cloud/');
        // echo json_encode($result);
    }

    public function currentToken() {
        $token = array(
            'token' => sessionData('token'),
            'refToken' => sessionData('refreshToken')
        );
        echo json_encode($token);
    }

}
