<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class INS_Controller extends CI_Controller {

    public function __construct() {
        ini_set('session.cookie_httponly', 1);
        parent::__construct();

        // Initiate default time & timezone
        date_default_timezone_set('Asia/Jakarta');
        $this->timestamp = date('Y-m-d H:i:s', time());

        // Load high level priority helper
        $this->load->helper(
            array(
                'primary_helper',
            )
        );

        // Load frequently used helpers
        loadHelper(
            array(
                'url',
                'form',
                'file',
                'cookie',
                'ins_helper',
                'api_helper'
            )
        );

        // Load frequently used models
        // loadModel(
        //     array(
        //         'Master_model',
        //         'Management_model',
        //         'Users_model',
        //     )
        // );

        // Load frequently used libraries
        loadLibrary(
            array(
                'form_validation',
                'session',
            )
        );

        $models = array(
            'authentication' => 'auth/Auth',
        );

        foreach ($models as $key => $value) {
            $this->{$key} = $value.'_model';
        }

        // Initial load database
        // $this->load->database();
    }
    
}
