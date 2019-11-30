<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;
class Pdfgenerator extends Dompdf{
  
    public $filename;
    public function __construct(){
        parent::__construct();
        $this->filename = "file.pdf";
    }

    protected function ci() {
        return get_instance();
    }

    public function generatePdf($data, $paperSize = 'A4', $orientation = 'landscape'){
        $html = $this->ci()->load->view('components/pdf-download', array('data' => $data), TRUE);
        $this->loadHtml($html);
        $this->setPaper($paperSize, $orientation);
        $this->render();
        $output = $this->output();
        return $output;
    }

}