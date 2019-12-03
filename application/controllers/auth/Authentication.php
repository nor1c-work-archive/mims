<?php

class Authentication extends INS_Controller {
    
    public function __construct() {
        parent::__construct();
        loadModel($this->authentication);
    }

    public function signIn() {
    	$data = ['umail'     => inputPost('umail'), 'pwd'       => inputPost('pwd')];

        $result = runAPI('user/login', 'POST', NULL, $data);

        if ($result['queryResult']) setUserCookie($result);
        else setFlashData('error', 'Gagal Login, Silahkan coba lagi!');

        if (sessionData('lastVisitedPage') !== null)
            redirect(html_entity_decode(sessionData('lastVisitedPage')));
        else redirect();
    }

    public function signOut() {
        // cookieDestroy();
        sessionDestroy();
        redirect();
    }

    public function refreshToken() {
        $result = refreshTokenifTokenExpired('https://miis-api.samrs.cloud/');
    }

    public function currentToken() {
        $token = ['token' => sessionData('token'), 'refToken' => sessionData('refreshToken')];
        jsonE($token);
    }

}
