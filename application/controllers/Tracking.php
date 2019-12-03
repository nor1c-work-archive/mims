<?php

class Tracking extends INS_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->catCode              = uriSegment(3);
        $this->selectedColumns      = array();
        $this->width                = array();
        $this->searchableColumns    = array();
        $this->totalUnusedColumn    = $this->catCode == env('C_SET') ? 3 : 2;
    }

    public function index() {
        $data['customDatatable']    = TRUE;
        $data['columnDefinition']   = array($this->selectedColumns, $this->searchableColumns, $this->totalUnusedColumn);
        $data['width']              = $this->width;
    
        $data['withoutActButton']   = TRUE;
        $data['withoutForm']        = TRUE;
        $data['withoutDetail']      = TRUE;
        $data['withoutFilter']      = TRUE;
        $data['withoutAdvFilter']   = TRUE;
        $data['withoutImport']      = TRUE;

        render('module', $data);
    }

    public function scan() {
        $code = str_replace('MIP-', '', inputGet('code'));

        $parameters['directFilters']['EXACTOR'][] = array(
            'column' => 'idAsset',
            'value' => $code
        );
        $response['piece'] = runAPI('asset/query', 'POST', NULL, $parameters);

        if (count($response['piece']['data']) > 0) {
            // get set
            $setParameters['directFilters']['EXACTOR'][] = array(
                'column' => 'idAsset',
                'value' => $response['piece']['data'][0]['assetParent']
            );
            $response['set'] = runAPI('asset/query', 'POST', NULL, $setParameters);

            // get box
            if (isset($response['set']['data'][0])) {
                $boxParameters['directFilters']['EXACTOR'][] = array(
                    'column' => 'idAsset',
                    'value' => $response['set']['data'][0]['assetParent']
                );
                $response['box'] = runAPI('asset/query', 'POST', NULL, $boxParameters);
            }
            
            jsonE($response);
        }
    }

    public function getAllPieces() {
        $pieceCode = explode('-', inputGet('pieceCode'))[1];

        // get set information
        $pieceParameters['directFilters']['EXACTOR'][] = array(
            'column' => 'idAsset',
            'value' => $pieceCode
        );
        $setCode = runAPI('asset/query', 'POST', NULL, $pieceParameters)['data'][0]['assetParent'];

        // get all pieces from this Set
        $allSetPiecesParameters['directFilters']['EXACTOR'][] = [
            'column' => 'assetParent',
            'value' => $setCode
        ];
        $allSetPieces = runAPI('asset/query', 'POST', NULL, $allSetPiecesParameters);

        // return collected pieces
        jsonE($allSetPieces);
    }

}
