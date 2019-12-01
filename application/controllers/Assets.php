<?php

class Assets extends INS_Controller {
    
    public function __construct() {
        parent::__construct();

        $this->catCode              = uriSegment(3);
        $this->totalUnusedColumn    = $this->catCode == env('C_SET') ? '3' : '2';
    }

    public function selectedColumns() {
        if ($this->catCode == env('C_CONTAINER') || inputGet('catCode') == env('C_CONTAINER')) {
            $selectedColumns = array(
                                        array('column' => 'idAsset', 'alias' => 'ID', 'width' => 10, 'searchable' => FALSE, 'hide' => FALSE),
                                        array('column' => 'assetParent', 'alias' => 'KODE SISTEM', 'width' => 10, 'searchable' => TRUE, 'hide' => FALSE),
                                        array('column' => 'assetName', 'alias' => 'NAMA CONTAINER/BOX', 'width' => 30, 'searchable' => TRUE, 'hide' => FALSE),
                                        array('column' => 'propAdmin.priceBuy', 'alias' => 'HARGA BELI', 'width' => 10, 'searchable' => TRUE, 'hide' => FALSE),
                                        array('column' => 'propAdmin.procureDate', 'alias' => 'TANGGAL PEROLEHAN', 'width' => 15, 'searchable' => FALSE, 'hide' => FALSE),
                                        array('column' => 'dummy.containerAvailabilityStatus', 'alias' => 'AVAILABILITY STATUS', 'width' => 15, 'searchable' => FALSE, 'hide' => FALSE),
                                        array('column' => 'propAdmin.ownershipType', 'alias' => 'KEPEMILIKAN', 'width' => 15, 'searchable' => FALSE, 'hide' => FALSE),
                                    );
        } else if ($this->catCode == env('C_PIECE') || inputGet('catCode') == env('C_PIECE')) {
            $selectedColumns = array(
                                        array('column' => 'idAsset', 'alias' => 'ID', 'width' => 10, 'searchable' => FALSE, 'hide' => FALSE),
                                        array('column' => 'assetParent', 'alias' => 'KODE SISTEM', 'width' => 10, 'searchable' => TRUE, 'hide' => FALSE),
                                        array('column' => 'assetName', 'alias' => 'NAMA INSTRUMENT', 'width' => 30, 'searchable' => TRUE, 'hide' => FALSE),
                                        array('column' => 'dummy.pieceInstrumentSet', 'alias' => 'INSTRUMENT SET', 'width' => 25, 'searchable' => FALSE, 'hide' => FALSE),
                                        array('column' => 'dummy.pieceInstrumentContainer', 'alias' => 'INSTRUMENT CONTAINER/BOX', 'width' => 25, 'searchable' => FALSE, 'hide' => FALSE),
                                        array('column' => 'propAdmin.idLocation', 'alias' => 'LOKASI', 'width' => 30, 'searchable' => TRUE, 'hide' => TRUE),
                                        array('column' => 'propAdmin.condition', 'alias' => 'KONDISI', 'width' => 10, 'searchable' => FALSE, 'hide' => FALSE),
                                        array('column' => 'propAdmin.status', 'alias' => 'STATUS', 'width' => 10, 'searchable' => FALSE, 'hide' => FALSE),
                                        array('column' => 'propAdmin.priceBuy', 'alias' => 'HARGA BELI', 'width' => 10, 'searchable' => TRUE, 'hide' => FALSE),
                                        array('column' => 'propAdmin.procureDate', 'alias' => 'TANGGAL PEROLEHAN', 'width' => 15, 'searchable' => FALSE, 'hide' => FALSE),
                                    );
        } else {
            $selectedColumns = array(
                                        array('column' => 'idAsset', 'alias' => 'ID', 'width' => 10, 'searchable' => FALSE, 'hide' => FALSE),
                                        array('column' => 'assetParent', 'alias' => 'KODE SISTEM', 'width' => 10, 'searchable' => TRUE, 'hide' => FALSE),
                                        array('column' => 'assetName', 'alias' => ($this->catCode == env('C_PIECE') ? 'NAMA INSTRUMENT' : 'NAMA ASET'), 'width' => 30, 'searchable' => TRUE, 'hide' => FALSE),
                                        array('column' => 'dummy.setContainer', 'alias' => 'CONTAINER/BOX', 'width' => 30, 'searchable' => FALSE, 'hide' => FALSE),
                                        array('column' => 'propAdmin.idLocation', 'alias' => 'LOKASI', 'width' => 10, 'searchable' => FALSE, 'hide' => FALSE),
                                        array('column' => 'propAdmin.condition', 'alias' => 'KONDISI', 'width' => 10, 'searchable' => FALSE, 'hide' => FALSE),
                                        array('column' => 'propAdmin.status', 'alias' => 'STATUS', 'width' => 10, 'searchable' => FALSE, 'hide' => FALSE),
                                        array('column' => 'propAdmin.priceBuy', 'alias' => 'HARGA BELI', 'width' => 10, 'searchable' => TRUE, 'hide' => FALSE),
                                        array('column' => 'propAdmin.procureDate', 'alias' => 'TANGGAL PEROLEHAN', 'width' => 15, 'searchable' => FALSE, 'hide' => FALSE),
                                        // array('column' => 'propInstrument.insCategory', 'alias' => 'KATEGORI', 'width' => 20, 'searchable' => FALSE, 'hide' => ($this->catCode == env('C_SET') ? FALSE : TRUE))
                                    );
        }

        return $selectedColumns;
    }

    public function index() {
        $data['customDatatable']    = TRUE;
        $data['columnDefinition']   = array($this->selectedColumns(), $this->totalUnusedColumn);
        $data['withExtraFilter']    = TRUE;
        $data['withFilterMode']     = TRUE;

        render('module', $data);
    }

    public function data() {
        $url = 'asset/query';

        // quick filter
        if (inputGet('query')) {
            $query = explode('&', inputGet('query'));

            $collectedQuery = array();
            foreach ($query as $queryKey => $queryValue) {
                $key = explode('=', $queryValue)[0];
                $value = explode('=', $queryValue)[1];
                
                $collectedQuery[$key] = $value;
            }

            $column = explode('|', $collectedQuery['column']);
            foreach ($column as $cKey => $cValue) {
                $parameters['search'][$cValue] = $collectedQuery['keyword'];
            }

            $parameters['additional']['catCode'] = $collectedQuery['catCode'];
        } else {
            $parameters = initParameters($this->selectedColumns(), $this->totalUnusedColumn);
            $parameters['additional']['catCode'] = inputGet('catCode');
        }

        // init filters
        $filters = inputPost('filters');
        if (is_string($filters)) {
            parse_str($filters, $filters);
        }

        $parameters['directFilters'] = array();

        if (isset($filters) && !empty($filters)) {
			// simple filters
            if (isset($filters['searchKeyword1']) || isset($filters['searchKeyword2'])) {
				// check first search field
                if ($filters['searchKeyword1'] != '') {
					if ($filters['searchField1'] == 'all') {
						foreach ($this->selectedColumns() as $allColKey => $selectedColumn) {
							if (strstr($selectedColumn['column'], '.')) {
								$parameters['search'][explode('.', $selectedColumn['column'])[1]] = $filters['searchKeyword1'];
							} else {
								$parameters['search'][$selectedColumn['column']] = $filters['searchKeyword1'];
							}
						}
					} else {
                        $parameters['directFilters']['LIKEAND'][] = array(
                            "column" => ($filters['searchField1'] == 'assetParent' ? 'idAsset' : $filters['searchField1']),
                            "value" => ($filters['searchField1'] == 'assetParent' ? str_replace(inputGet('catCode').'-', '', $filters['searchKeyword1']) : $filters['searchKeyword1'])
						);
					}
				}
				// check second search field
                if ($filters['searchKeyword2'] != '') {
					if ($filters['searchField2'] == 'all') {
						foreach ($this->selectedColumns() as $allColKey => $selectedColumn) {
							if (strstr($selectedColumn['column'], '.')) {
								$parameters['search'][explode('.', $selectedColumn['column'])[1]] = $filters['searchKeyword2'];
							} else {
								$parameters['search'][$selectedColumn['column']] = $filters['searchKeyword2'];
							}
						}
					} else {
                        $parameters['directFilters']['LIKEAND'][] = array(
                            "column" => ($filters['searchField2'] == 'assetParent' ? 'idAsset' : $filters['searchField2']),
                            "value" => ($filters['searchField1'] == 'assetParent' ? str_replace(inputGet('catCode').'-', '', $filters['searchKeyword2']) : $filters['searchKeyword2'])
                        );
                    }
				}
                // if ($filters['searchInsCategory'] != '') {
                //     $parameters['directFilters']['EXACTOR'][] = array(
                //         "column" => 'insCategory',
                //         "value" => $filters['searchInsCategory']
                //     );
                // }
            } else {
				// advanced filters
                foreach ($filters as $paramKey => $paramValue) {
                    if (isset($paramValue['value']) && $paramValue['value'] != '') {
                        if (in_array($paramValue['name'], array('firstProcureDate', 'lastProcureDate'))) {
                            $parameters['directFilters']['BETWEEN'][] = array(
                                "column" => "createDate",
                                "value" => date('Y-m-d', strtotime(str_replace('/', '-', $paramValue['value'])))
                            );
                        } else if ($paramValue['name'] == 'idAssetMaster') {
                            $parameters['directFilters']['LIKEAND'][] = array(
                                "column" => 'idAsset',
                                "value" => ltrim(str_replace($parameters['additional']['catCode'].'-', '', $paramValue['value']), '0')
                            );
                        } else if ($paramValue['name'] == 'priceBuy') {
                            $parameters['directFilters']['LIKEAND'][] = array(
                                "column" => $paramValue['name'],
                                "value" => str_replace('.', '', $paramValue['value'])
                            );
                        } else if ($paramValue['name'] == 'status') {
                            $parameters['directFilters']['EXACTOR'][] = array(
                                "column" => $paramValue['name'],
                                "value" => $paramValue['value']
                            );
                        } else {
                            $parameters['directFilters']['LIKEAND'][] = array(
                                "column" => $paramValue['name'],
                                "value" => $paramValue['value']
                            );
                        }
                    }
                }
            }
        }

        if (isset($parameters['search']) && $parameters['search'] != '') {
            $url = 'asset/query';
        }

        $result = runAPI($url, 'POST', NULL, $parameters);

        generateData($this->selectedColumns(), $result);
    }

    public function getSingleData() {
        $pk = inputGet('id');

        // get set detail
        $parameters['directFilters']['EXACTOR'][] = array(
            'column' => 'idAsset',
            'value' => $pk
        );
        $result['set'] = runAPI('asset/query', 'POST', NULL, $parameters);

        if ($result['set']['data'][0]['assetParent']) {
            $parameters['directFilters']['EXACTOR'][] = array(
                'column' => 'idAsset',
                'value' => $result['set']['data'][0]['assetParent']
            );
            $result['parent'] = runAPI('asset/query', 'POST', NULL, $parameters);
            
            $setCatalogueParameters['directFilters']['EXACTOR'][] = array(
                'column' => 'idAssetMaster',
                'value' => $result['parent']['data'][0]['idAssetMaster']
            );
            $result['parentCatalogue'] = runAPI('asset/masterquery', 'POST', NULL, $setCatalogueParameters);
        }

        // set catalogue
        $pieceCatalogueParameters['directFilters']['EXACTOR'][] = array(
            'column' => 'idAssetMaster',
            'value' => $result['set']['data'][0]['idAssetMaster']
        );
        $result['setCatalogue'] = runAPI('asset/masterquery', 'POST', NULL, $pieceCatalogueParameters);

        // get set pieces
        $pieceParameters['directFilters']['EXACTOR'][] = array(
            'column' => 'assetParent',
            'value' => $pk
        );
        $result['pieces'] = runAPI('asset/query', 'POST', NULL, $pieceParameters);

        echo json_encode($result);
    }

    public function form() {
        $pk = (!in_array(inputPost('isPiece'), array('1')) ? inputPost('asset')[1]['idAsset'] : '');
        $data = inputPost();

        $importMode = FALSE;

        if (isset($data['asset'])) {
            // insert from form
			// START OF INSERT/UPDATE SET
			echo json_encode($data); die();

            foreach ($data['asset'] as $assetKey => $assetValue) {
                $qty = (isset($assetValue['quantity']) ? $assetValue['quantity'] : 1);
                for ($i=1; $i <= $qty; $i++) { // kalau langsung input piece

                    $assetNameParameters = array();
                    $assetNameParameters['directFilters']['EXACTOR'][] = array(
                        'column' => 'idAssetMaster',
                        'value' => $assetValue['idAssetMaster']
                    );

                    $assetName[$i] = runAPI('asset/masterquery', 'POST', NULL, $assetNameParameters);

                    $set[] = array(
                        'idAsset' => ($pk ? $assetValue['idAsset'] : 0),
                        'qrCode' => isset($assetValue['qrCode']) ? emptyDefault($assetValue['qrCode']) : '',
                        'catCode' => $assetValue['catCode'],
                        'idAssetMaster' => isset($assetValue['idAssetMaster']) ? zeroDefault($assetValue['idAssetMaster']) : 0,
                        'assetParent' => isset($assetValue['assetParent']) ? (strpos('MIC', $assetValue['assetParent']) !== TRUE ? str_replace('MIC-','',explode('|',$assetValue['assetParent'])[0]) : zeroDefault($assetValue['assetParent'])) : 0,
                        'assetName' => (isset($assetValue['assetName']) ? $assetValue['assetName'] : $assetName[$i]['data'][0]['assetMasterName']),
                        'assetDesc' => isset($assetValue['assetDesc']) ? emptyDefault($assetValue['assetDesc']) : '',
                        'hasPropAdmin' => true,
                        'hasPropFile' => true,
                        'hasPropInstrument' => true,
                        'hasPropTax' => true,
                        'propAdmin' => array(
                            'idAsset' => ($pk ? $assetValue['idAsset'] : 0),
                            'idLocation' => isset($assetValue['idLocation']) ? zeroDefault($assetValue['idLocation']) : 0,
                            'riskLevel' => isset($assetValue['riskLevel']) ? emptyDefault($assetValue['riskLevel']) : '',
                            'ownershipType' => isset($assetValue['ownershipType']) ? emptyDefault($assetValue['ownershipType']) : '',
                            'condition' => isset($assetValue['condition']) ? emptyDefault($assetValue['condition']) : '',
                            'status' => isset($assetValue['status']) ? emptyDefault($assetValue['status']) : '',
                            'inactive_date' => isset($assetValue['inactive_date']) ? emptyDefault($assetValue['inactive_date']) : '',
                            'yearProcurement' => ($assetValue['procureDate'] ? date('Y', strtotime(str_replace('/', '-', $assetValue['procureDate']))) : NULL),
                            'procureDate' => ($assetValue['procureDate'] ? date('Y-m-d', strtotime(str_replace('/', '-', $assetValue['procureDate']))) : NULL),
                            'receivedDate' => isset($assetValue['receivedDate']) ? emptyDefault($assetValue['receivedDate']) : '',
                            'reff' => isset($assetValue['reff']) ? emptyDefault($assetValue['reff']) : '',
                            'poNumb' => isset($assetValue['poNumb']) ? emptyDefault($assetValue['poNumb']) : '',
                            'priceBuy' => ($assetValue['priceBuy'] ? str_replace('.', '', $assetValue['priceBuy']) : 0),
                            'depreciationMode' => isset($assetValue['depreciationMode']) ? emptyDefault($assetValue['depreciationMode']) : '',
                            'keterangan' => isset($assetValue['keterangan']) ? emptyDefault($assetValue['keterangan']) : '',
                        ),
                        'propFiles' => array(
                            // array(
                            //     'idAsset' => ($pk ? $assetValue['idAsset'] : 0),
                            //     'idFile' => isset($assetValue['idFile']) ? zeroDefault($assetValue['idFile']) : 0,
                            //     'propFileName' => isset($assetValue['propFileName']) ? emptyDefault($assetValue['propFileName']) : '',
                            //     'propFileDesc' => isset($assetValue['propFileDesc']) ? emptyDefault($assetValue['propFileDesc']) : '',
                            // ),
                        ),
                        'propInstrument' => array(
                            'idAsset' => ($pk ? $assetValue['idAsset'] : 0),
                            'insCategory' => isset($assetValue['insCategory']) ? emptyDefault($assetValue['insCategory']) : '',
                            'insStatus' => isset($assetValue['insStatus']) ? emptyDefault($assetValue['insStatus']) : '',
                            'merk' => isset($assetValue['merk']) ? emptyDefault($assetValue['merk']) : '',
                            'isSet' => true,
                        ),
                        'propTax' => array(
                            'idAsset' => ($pk ? $assetValue['idAsset'] : 0),
                            'taxCategory' => isset($assetValue['taxCategory']) ? emptyDefault($assetValue['taxCategory']) : '',
                            'expectedLifeTime' => isset($assetValue['expectedLifeTime']) ? zeroDefault($assetValue['expectedLifeTime']) : 0,
                            'lifeTimeUnit' => isset($assetValue['lifeTimeUnit']) ? emptyDefault($assetValue['lifeTimeUnit']) : '',
                            'cost' => isset($assetValue['cost']) ? zeroDefault($assetValue['cost']) : 0,
                            'residuVal' => isset($assetValue['residuVal']) ? zeroDefault($assetValue['residuVal']) : 0,
                            'bookVal' => isset($assetValue['bookVal']) ? zeroDefault($assetValue['bookVal']) : 0,
                            'currentLifeTime' => isset($assetValue['currentLifeTime']) ? zeroDefault($assetValue['currentLifeTime']) : 0,
                            'percentLifeTime' => isset($assetValue['percentLifeTime']) ? zeroDefault($assetValue['percentLifeTime']) : 0,
                            'presentDate' => isset($assetValue['presentDate']) ? emptyDefault($assetValue['presentDate']) : '',
                            'calcStart' => isset($assetValue['calcStart']) ? emptyDefault($assetValue['calcStart']) : '',
                        ),
                    );
                }
            }

            $response = runAPI('asset/'.($pk ? 'update' : 'insert'), 'POST', NULL, $set);
            // END OF INSERT/UPDATE SET
            
            if (!inputPost('isPiece')) {
                $parentID = ($pk ? $pk : $response['data'][0]);
                // START OF DELETE ASSET
                if (!empty($data['deletedPieces'])) {
                    $collectedDeletedData = array();
                    foreach ($data['deletedPieces'] as $key => $value) {
                        $collectedDeletedData[] = (int)$value;
                    }
                    $response = runAPI('asset/delete', 'POST', NULL, $collectedDeletedData);
                }
                // END OF DELETE ASSET

                // START INSERT/UPDATE PIECES
                $collectedData = array();
                $collectedUpdatedData = array();
                if (isset($data['instruments'])) {
                    foreach ($data['instruments'] as $insKey => $insValue) {
                        for ($i=1; $i <= $insValue['quantity']; $i++) { 
                            $assetNameParameters = array();
                            $assetNameParameters['directFilters']['EXACTOR'][] = array(
                                'column' => 'idAssetMaster',
                                'value' => $insValue['idAssetMaster']
                            );
    
                            $assetName[$insKey] = runAPI('asset/masterquery', 'POST', NULL, $assetNameParameters);
    
                            if ($pk) {
                                if ($insValue['idAsset'] != '') {
                                    $collectedUpdatedData[] = array(
                                        'idAsset' => $insValue['idAsset'],
                                        'idAssetMaster' => $insValue['idAssetMaster'],
                                        'assetParent' => $parentID,
                                        'catCode' => $insValue['catCode'],
                                        'assetName' => $assetName[$insKey]['data'][0]['assetMasterName']
                                    );
                                } else {
                                    $collectedData[] = array(
                                        'idAssetMaster' => $insValue['idAssetMaster'],
                                        'assetParent' => $parentID,
                                        'catCode' => $insValue['catCode'],
                                        'assetName' => $assetName[$insKey]['data'][0]['assetMasterName'],
                                        'hasPropAdmin' => true,
                                        'propAdmin' => array(
                                            'idAsset' => $parentID,
                                            'riskLevel' => '',
                                            'ownershipType' => '',
                                            'condition' => '',
                                            'status' => '',
                                            'priceBuy' => $insValue['priceBuy'],
                                            'procureDate' => date('Y-m-d', strtotime(str_replace('/', '-', $insValue['procureDate'])))
                                        ),
                                    );
                                }
                            } else {
                                $collectedData[] = array(
                                    'idAssetMaster' => $insValue['idAssetMaster'],
                                    'assetParent' => $parentID,
                                    'catCode' => $insValue['catCode'],
                                    'assetName' => $assetName[$insKey]['data'][0]['assetMasterName']
                                );
                            }
                        }
                    }
                }

                // update pieces
                if (!empty($collectedUpdatedData)) {
                    $response = runAPI('asset/update', 'POST', NULL, $collectedUpdatedData);
                }
                
                // insert pieces
                if (!empty($collectedData)) {
                    $response = runAPI('asset/insert', 'POST', NULL, $collectedData);
                }
                // // END OF INSERT/UPDATE PIECES
            }
            
            echo json_encode($response);
        } else {
            // import from excel
            $importMode = TRUE;
            $catCode = inputGet('catCode');

            $inserted = array();
            $updated = array();
            $ignored = array();
            
            $data = json_decode($data['data'], TRUE);
            
            if ($catCode == 'MIP')
                $data = $data[0];

            foreach ($data as $key => $value) { // start process each set
                if ($catCode == 'MIP')
                    $value[0] = $value;

                if ($value[0]['importStatus'] != 'ignored') {
                    $set = array();

                    // collect set first
                    if ($value[0]['KODE KATALOG']) {
                        $parametersSetCatalogue['directFilters']['EXACTOR'][] = array(
                            'column' => 'productCode',
                            'value' => $value[0]['KODE KATALOG']
                        );
                        $setCatalogue = runAPI('asset/masterquery', 'POST', NULL, $parametersSetCatalogue)['data'][0];
                        $set_idAssetMaster = $setCatalogue['idAssetMaster'];
                    } else
                        $set_idAssetMaster = 0;

                    $set_idAsset = ($value[0]['KODE SISTEM'] ? $value[0]['KODE SISTEM'] : 0);
                    $set_Container = 0;
                    $set_Location = 0;

                    // set up set data
                    $set[] = array(
                        'idAsset' => $set_idAsset,
                        'catCode' => $catCode,
                        'idAssetMaster' => $set_idAssetMaster,
                        'assetParent' => $set_Container,
                        'assetName' => emptyDefault($value[0]['NAMA ALIAS ASET']),
                        'assetDesc' => emptyDefault($value[0]['KETERANGAN']),
                        'hasPropAdmin' => true,
                        'hasPropFile' => true,
                        'hasPropInstrument' => true,
                        'hasPropTax' => true,
                        'propAdmin' => array(
                            'idAsset' => $set_idAsset,
                            'idLocation' => $set_Location,
                            'riskLevel' => emptyDefault($value[0]['LEVEL RESIKO']),
                            'ownershipType' => emptyDefault($value[0]['KEPEMILIKAN']),
                            'condition' => emptyDefault($value[0]['KONDISI']),
                            'status' => emptyDefault($value[0]['STATUS']),
                            'yearProcurement' => ($value[0]['TANGGAL PEROLEHAN'] ? date('Y', strtotime(str_replace('/', '-', $value[0]['TANGGAL PEROLEHAN']))) : NULL),
                            'procureDate' => ($value[0]['TANGGAL PEROLEHAN'] ? date('Y-m-d', strtotime(str_replace('/', '-', $value[0]['TANGGAL PEROLEHAN']))) : NULL),
                            'priceBuy' => zeroDefault($value[0]['HARGA BELI']),
                        ),
                        'propFiles' => array(),
                        'propInstrument' => array(
                            'idAsset' => $set_idAsset,
                            'insCategory' => (isset($value[0]['KATEGORI INSTRUMENT']) ? emptyDefault(strtoupper($value[0]['KATEGORI INSTRUMENT'])) : ''),
                            'isSet' => true,
                        ),
                        'propTax' => array(
                            'idAsset' => $set_idAsset
                        ),
                    );
                    $response['set'] = runAPI('asset/insert', 'POST', NULL, $set);
                    $response['final'][$key] = $response['set'];

                    if ($catCode != 'MIP') {
                        $set_idAsset = $response['set']['data'][0];

                        // set up piece data
                        $piece = array();
                        foreach ($value as $pieceKey => $pieceValue) {
                            if ($pieceValue['importStatus'] != 'ignored') {
                                // get catalogue detail
                                if ($value[0]['KODE KATALOG']) {
                                    $parametersPieceCatalogue[$pieceKey]['directFilters']['EXACTOR'][] = array(
                                        'column' => 'productCode',
                                        'value' => $pieceValue['KODE KATALOG']
                                    );
                                    $pieceCatalogue = runAPI('asset/masterquery', 'POST', NULL, $parametersPieceCatalogue[$pieceKey])['data'][0];
                                    $piece_idAssetMaster[$pieceKey] = $pieceCatalogue['idAssetMaster'];
                                } else
                                    $piece_idAssetMaster[$pieceKey] = 0;

                                $piece_idAsset = ($pieceValue['KODE SISTEM'] ? $pieceValue['KODE SISTEM'] : 0);

                                // transform data
                                if ($pieceKey != 0) {
                                    $piece[] = array(
                                        'idAsset' => $piece_idAsset,
                                        'catCode' => 'MIP',
                                        'idAssetMaster' => $piece_idAssetMaster[$pieceKey],
                                        'assetParent' => $set_idAsset,
                                        'assetName' => emptyDefault($pieceValue['NAMA ALIAS ASET']),
                                        'assetDesc' => emptyDefault($pieceValue['KETERANGAN']),
                                        'hasPropAdmin' => true,
                                        'hasPropFile' => true,
                                        'hasPropInstrument' => true,
                                        'hasPropTax' => true,
                                        'propAdmin' => array(
                                            'idAsset' => $piece_idAsset,
                                            'idLocation' => $set_Location,
                                            'riskLevel' => emptyDefault($pieceValue['LEVEL RESIKO']),
                                            'ownershipType' => emptyDefault($pieceValue['KEPEMILIKAN']),
                                            'condition' => emptyDefault($pieceValue['KONDISI']),
                                            'status' => emptyDefault($pieceValue['STATUS']),
                                            'yearProcurement' => ($pieceValue['TANGGAL PEROLEHAN'] ? date('Y', strtotime(str_replace('/', '-', $pieceValue['TANGGAL PEROLEHAN']))) : NULL),
                                            'procureDate' => ($pieceValue['TANGGAL PEROLEHAN'] ? date('Y-m-d', strtotime(str_replace('/', '-', $pieceValue['TANGGAL PEROLEHAN']))) : NULL),
                                            'priceBuy' => zeroDefault($pieceValue['HARGA BELI']),
                                        ),
                                        'propFiles' => array(),
                                        'propInstrument' => array(
                                            'idAsset' => $piece_idAsset,
                                            'insCategory' => emptyDefault(strtoupper($pieceValue['KATEGORI INSTRUMENT'])),
                                            'isSet' => false,
                                        ),
                                        'propTax' => array(
                                            'idAsset' => $piece_idAsset,
                                        ),
                                    );
                                }
                            }
                        }
                        $response['piece'] = runAPI('asset/insert', 'POST', NULL, $piece);
                        $response['final'][$key]['piece'] = $response['piece'];
                    }
                }
            }

            echo json_encode($response['final']);
            // echo json_encode($repsonse);
            // END OF IMPORT
        }
    }

    public function importPreview() {
        $catCode = inputGet('catCode');

        set_time_limit(0);
        ini_set('memory_limit', '99999999999999999M');
        ini_set('upload_max_filesize', '99999999999999999M');
        ini_set('post_max_size', '99999999999999999M');
        ini_set('max_input_time', '99999999999999999');
        ini_set('max_execution_time', '99999999999999999');

        if (isset($_FILES['file'])) {

            $result = runAPI('asset/list', 'GET');

            $DB_ID = array();
            foreach ($result['data'] as $value) {
                $DB_ID[] = $value['idAsset'];
            }
            
            loadLibrary('PHPExcel');
            $file = $_FILES['file']['tmp_name'];
            $inputFileType = PHPExcel_IOFactory::identify($file);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objReader->setReadDataOnly(true);
            $objPHPExcel = $objReader->load($file);
            
            $objWorksheet = $objPHPExcel->getActiveSheet();
            
            $fileData = array();
            $fileDataOnly = array();

            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                if ($objPHPExcel->getIndex($worksheet) == 0) {
                    $highestRow = $worksheet->getHighestDataRow();
                    $highestColumn = $worksheet->getHighestDataColumn();
                    $headings = $worksheet->rangeToArray('A2:' . $highestColumn . 2, NULL, TRUE, FALSE);

                    $i = 1;
                    for ($row = 3; $row <= $highestRow; $row++) {
                        $rowData = $worksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                        $rowData = array_combine($headings[0], $rowData[0]);
                        $rowData['TANGGAL PEROLEHAN'] = ($rowData['TANGGAL PEROLEHAN'] ? date('Y-m-d', ($rowData['TANGGAL PEROLEHAN'] - 25569) * 86400) : '');
                        if ($catCode == 'MIP') {
                            if ($rowData['KODE SISTEM'] == '::end::') 
                                break;
                        } else {
                            if ($rowData['MARK'] == '::end::') 
                                break;
                        }
                        $fileData[$i][] = $rowData;
                        $i++;
                    }
                }
            }

            $inserted = 0;
            $updated = 0;
            $ignored = 0;
            $set = 0;
            $piece = 0;

            // INITIALIZE TABLE HEADER
            if ($catCode == 'MIP') {
                $tableHeader = array('', 'KODE SISTEM', 'KODE SET', 'KODE KATALOG', 'NAMA ALIAS ASET', 'KONDISI', 'STATUS', 'LEVEL RESIKO', 'KEPEMILIKAN', 'LOKASI', 'HARGA BELI', 'TANGGAL PEROLEHAN', 'SUPPLIER', 'KETERANGAN', 'MARK');
                $requiredField = array('KODE KATALOG', 'NAMA ALIAS ASET', 'KONDISI', 'STATUS', 'LEVEL RESIKO', 'KEPEMILIKAN');
            } else {
                $tableHeader = array('', 'KODE SISTEM', 'KODE SISTEM BOX', 'KODE KATALOG BOX', 'KATEGORI INSTRUMENT', 'KODE KATALOG', 'NAMA ALIAS ASET', 'KONDISI', 'STATUS', 'LEVEL RESIKO', 'KEPEMILIKAN', 'LOKASI', 'HARGA BELI', 'TANGGAL PEROLEHAN', 'SUPPLIER', 'KETERANGAN', 'MARK');
                $requiredField = array('KODE KATALOG', 'NAMA ALIAS ASET', 'KONDISI', 'STATUS', 'LEVEL RESIKO', 'KEPEMILIKAN');
            }

            // INITIALIZE DATA
            $fileDataOnlySet    = array();
            $fileDataOnlyPiece  = array();
            $finalData          = array();
            $no                 = 0;
            $noSet = 0; $noPiece = 0;
            $setSeparator = 0;
            
            foreach ($fileData as $key => $value) {
                if ((isset($value[0]['MARK']) && $value[0]['MARK'] != '') || $catCode == 'MIP') {

                    if (isset($value[0]['MARK']) && $value[0]['MARK'] == '*') {
                        $set += 1;
                    } else if (isset($value[0]['MARK']) && $value[0]['MARK'] == '**') {
                        $piece += 1;
                    }

                    if (isset($value[0]['MARK']) && $value[0]['MARK'] == '*') {
                        $setSeparator += 1; $no = 0;
                    }

                    // push to finalData var
                    $finalData[$setSeparator][$no] = $value[0];
                    $finalData[$setSeparator][$no]['catCode'] = inputGet('catCode');

                    if ($value[0]['KODE KATALOG'] == '' || $value[0]['NAMA ALIAS ASET'] == '' || $value[0]['KONDISI'] == '' || $value[0]['STATUS'] == '' || $value[0]['LEVEL RESIKO'] == '' || $value[0]['KEPEMILIKAN'] == '') {
                        $ignored += 1;
                        $reason = '<b style="color:red">Required field harus diisi!</b>';

                        $finalData[$setSeparator][$no]['importStatus'] = 'ignored';
                    } else {
                        if (in_array($value[0]['KODE SISTEM'], $DB_ID)) {
                            $updated += 1;
                            $reason = '<b style="color:orange">Alat sudah terdaftar, data akan diupdate!</b>';
                        
                            $finalData[$setSeparator][$no]['importStatus'] = 'updated';
                            $finalData[$setSeparator][$no]['idAssetMaster'] = 'updated';
                        } else {
                            $inserted += 1;
                            $reason = '-';   

                            $finalData[$setSeparator][$no]['importStatus'] = 'inserted';
                        }
                    }
                    
                    foreach ($value as $v_key => $v_value) {

                        $fileDataOnly[$no] = array();
                        $fileDataOnly[$no][] = $v_value;
                        
                        if (isset($v_value['MARK']) && $v_value['MARK'] == '*') {
                            $fileDataOnlySet[$noSet]       = array();
                            $fileDataOnlySet[$noSet][]     = $noSet+1;
                        } else {
                            $fileDataOnlyPiece[$noPiece]         = array();
                            $fileDataOnlyPiece[$noPiece][]       = $noSet;
                        }
                        
                        foreach ($tableHeader as $header) {
                            if (isset($v_value['MARK']) && $v_value['MARK'] == '*') {
                                foreach ($v_value as $v_valueKey => $v_valueVal) {
                                    if ($v_valueKey == $header) {
                                        if ($header != 'MARK') {
                                            if (in_array($header, $requiredField) && $v_valueVal == '') {
                                                $fileDataOnlySet[$noSet][] = '<b style="color:red">REQUIRED</b>';
                                            } else {
                                                if ($header == 'TANGGAL PEROLEHAN')
                                                    $fileDataOnlySet[$noSet][] = ($v_valueVal ? date('d/m/Y', strtotime($v_valueVal)) : '-');
                                                else if ($header == 'HARGA BELI')
                                                    $fileDataOnlySet[$noSet][] = ($v_valueVal ? number_format($v_valueVal, '0', '.', '.') : '-');
                                                else
                                                    $fileDataOnlySet[$noSet][] = ($v_valueVal ? $v_valueVal : '-');
                                            }
                                        }
                                    }
                                }   
                            } else {
                                foreach ($v_value as $v_valueKey => $v_valueVal) {
                                    if ($v_valueKey == $header) {
                                        if ($header != 'MARK') {
                                            if (in_array($header, $requiredField) && $v_valueVal == '') {
                                                $fileDataOnlyPiece[$noPiece][] = '<b style="color:red">REQUIRED</b>';
                                            } else {
                                                if ($header == 'TANGGAL PEROLEHAN')
                                                    $fileDataOnlyPiece[$noPiece][] = ($v_valueVal ? date('d/m/Y', strtotime($v_valueVal)) : '-');
                                                else if ($header == 'HARGA BELI')
                                                    $fileDataOnlyPiece[$noPiece][] = ($v_valueVal ? number_format($v_valueVal, '0', '.', '.') : '-');
                                                else
                                                    $fileDataOnlyPiece[$noPiece][] = ($v_valueVal ? $v_valueVal : '-');
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        if (isset($v_value['MARK']) && $v_value['MARK'] == '*') {
                            $fileDataOnlySet[$noSet][] = $reason;
                            $noSet++;
                        } else {
                            $fileDataOnlyPiece[$noPiece][] = $reason;
                            $noPiece++;
                        }
                        $no++;
                    }
                }
            }

            // echo json_encode($finalData); die();
            $finalData = json_encode($finalData);

            if ($catCode == 'MIP') {
                $note = $inserted . ' data akan diimport, ' . $updated . ' data akan diupdate, dan ' . $ignored . ' data akan diabaikan dari total ' . count($fileData) . ' baris data.';
            } else {
                $note = $inserted . ' data akan diimport ('.$set.' set dan '.$piece.' piece), ' . $updated . ' data akan diupdate, dan ' . $ignored . ' data akan diabaikan dari total ' . count($fileData) . ' baris data.';
            }

            echo json_encode(
                array(
                    'header'    => $tableHeader,
                    'data'      => $fileDataOnly,
                    'dataSet'   => ($catCode == 'MIP' ? $fileDataOnlyPiece : $fileDataOnlySet),
                    'dataPiece' => $fileDataOnlyPiece,
                    'raw'       => $finalData,
                    'note'      => $note
                )
            );
        }
    }

    public function delete() {
        $rowsSelected = inputPost('ids');

        if (inputGet('catCode') == env('C_SET')) {
            foreach ($rowsSelected as $idKey => $idValue) {
                $deleteParams['directFilters']['EXACTOR'][] = array(
                    'column' => 'assetParent',
                    'value' => $idValue
                );
                $pieces = runAPI('asset/query', 'POST', NULL, $deleteParams);

                $deletePiecesID = array();
                foreach ($pieces['data'] as $key => $value) {
                    $deletePiecesID[] = $value['idAsset'];
                }
    
                $response = runAPI('asset/delete', 'POST', NULL, $deletePiecesID);
            }
        }

        $response = runAPI('asset/delete', 'POST', NULL, $rowsSelected);

        echo json_encode($response);
    }

    public function expandableContent() {
        $id = inputGet('id');

        $parameters['additional']['catCode'] = 'MIP';
        $parameters['directFilters']['EXACTOR'][] = array(
            'column' => 'assetParent',
            'value' => $id
        );

        $pieces = runAPI('asset/query', 'POST', NULL, $parameters);

        $html = '
                <style>
                    #expandableTable'.$id.'_length, #expandableTable'.$id.'_info { float:left; }
                    #expandableTable'.$id.'_paginate { padding-top: 5px !important; }
                </style>

                <div style="width:100%;background-color:#fff !important;text-align:center;padding:0px 5px 5px 5px;margin-top:-2px;">
                <i class="fas fa-caret-up" style="color:#000;padding:0;border-bottom:solid 1px #ccc;width:100%;"></i>
                <br><br>
                <table id="expandableTable'.$id.'" class="table table-bordered table-child" style="width:100%;text-align:left;background-color:#fff;">';
        
        if (count($pieces['data']) > 0) {
            $html .=    '<thead>
                            <tr style="background-color:#eee">
                                <th width="50">NO</th>
                                <th width="100">KODE SISTEM</th>
                                <th width="200">KODE KATALOG</th>
                                <th width="500">NAMA ASET</th>
                                <th width="100">KONDISI</th>
                                <th width="100">STATUS</th>
                                <th width="100">HARGA BELI</th>
                                <th width="120">TANGGAL PEROLEHAN</th>
                            </tr>
                        </thead>
                        <tbody>';
            
            $no = 1;
            foreach ($pieces['data'] as $key => $value) {
                $pieceParameters = array();
                $pieceParameters['directFilters']['EXACTOR'][] = array(
                    'column' => 'idAssetMaster',
                    'value' => $value['idAssetMaster']
                );
                
                $catalogueCode[$key] = runAPI('asset/masterquery', 'POST', NULL, $pieceParameters);
                
                $html .= '<tr>
                            <td>'.$no.'</td>
                            <td>'.'MIP-'.$id.'-'.$value['idAsset'].'</td>
                            <td>'.$catalogueCode[$key]['data'][0]['productCode'].'</td>
                            <td>'.$value['assetName'].'</td>
                            <td>'.$value['propAdmin']['condition'].'</td>
                            <td>'.$value['propAdmin']['status'].'</td>
                            <td>'.($value['propAdmin']['priceBuy'] ? 'Rp '.rupiah($value['propAdmin']['priceBuy']) : '-').'</td>
                            <td>'.convertDate($value['propAdmin']['procureDate'], 'd/m/Y').'</td>
                          </tr>';
                $no++;
            }

            $html .= '</tbody>';
        } else {
            $html .=    '<tr style="text-align:center;margin-top:-100px;">
                            <th>This set doesn\'t contain any instruments!</th>
                         </tr>';
        }

        $html .= '</thead></table></div>
                <script>
                    $(document).ready(function() {
                        $("#expandableTable'.$id.'").DataTable({
                            "aLengthMenu": [[5, 10, 25, 25, 25, 50, 75, -1], [5, 10, 25, 25, 25, 50, 75, "All"]],
                            "pageLength": 5,
                        });

                        $("#expandableTable'.$id.'_wrapper").css("width", "100%");
                        $("#expandableTable'.$id.'_wrapper").css("float", "left");
                    });
                </script>
        ';
        
        echo json_encode($html);
    }

    function generateBarcode($format = 'png', $symbology = 'qr', $option = '') {
        $code = inputPost('codes');

        require_once('./application/libraries/barcode.php');

        $generator = new barcode_generator();
        
        $barcodes = '';
        foreach ($code as $key => $value) {
            $parameters[$value]['directFilters']['EXACTOR'][] = array(
                'column' => 'assetParent',
                'value' => $value
            );
            $response[$value] = runAPI('asset/query', 'POST', NULL, $parameters[$value]);
            $response[$value] = $response[$value]['data'];

            foreach ($response[$value] as $piece) {
				$value = $piece['catCode'] . '-' . $piece['idAsset'];

				$svg = $generator->render_svg($symbology, $value, $option);
				$barcodes .= $svg . '<br><span style="font-size:12px;font-family:Arial;margin-left:32px;font-weight:bold;">' . $piece['catCode'].'-'.$piece['idAsset'] . '</span><br>';
			}
        }

        echo $barcodes;
    }
    

    function regenerateBarcode($format = 'png', $symbology = 'dmtx', $option = '') {
        $code = inputGet('codes');
        $code = explode(',', $code);

        require_once('./application/libraries/barcode.php');

        $generator = new barcode_generator();
        
        $barcodes = '';
        foreach ($code as $key => $value) {
            $value = uriSegment(1).'-'.sprintf('%05d', $value);

            $svg = $generator->render_svg($symbology, $value, $option);
            echo $svg;
        }
    }

    public function export() {
        $catCode = inputGet('catCode');
        $code = inputGet('codes');
        $code = explode(',', $code);

        // INITIALIZING
        error_reporting(E_ALL);
        set_time_limit(0);
        ini_set('memory_limit', '99999999999999999M');
        ini_set('upload_max_filesize', '99999999999999999M');
        ini_set('post_max_size', '99999999999999999M');
        ini_set('max_input_time', '99999999999999999');
        ini_set('max_execution_time', '99999999999999999');

        $this->load->library('PHPExcel');
        include APPPATH . "libraries/PHPExcel2/PHPExcel/IOFactory.php";

        $filename = 'Asset';
        $filename_exists = 0;
        
        if ($catCode == 'MIP')
            $templateName = 'Format Import Instrument Piece';
        else
            $templateName = 'Format Import Set';

        $inputFileName = 'assets/format/import/'.$templateName.'.xlsx';

        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($inputFileName);
        // END OF INITIALIZATION

        // START OF DATA PROCESSING
        // Run API
        foreach ($code as $key => $idAsset) {
            $parameters['directFilters']['EXACTOR'][] = array(
                'column' => 'idAsset',
                'value' => $idAsset
            );
        }
        $response['set'] = runAPI('asset/query', 'POST', NULL, $parameters)['data'];

        $start = 3;
        $set = array();
        $piece = array();
        $colour = array();
        
        foreach ($response['set'] as $key => $value) {
            if ($catCode == env('C_SET')) {
                $setCatalogueCodeParameters[$key]['directFilters']['EXACTOR'][] = array(
                    'column' => 'idAssetMaster',
                    'value' => $value['idAssetMaster']
                );
                $setCatalogueCode[$key] = runAPI('asset/masterquery', 'POST', NULL, $setCatalogueCodeParameters[$key])['data'][0]['productCode'];

                // start of set data processing
                $set[$start]["A$start"] = '*';
                $set[$start]["B$start"] = $catCode.'-'.$value['idAsset'];
                $set[$start]["C$start"] = '';
                $set[$start]["D$start"] = '';
                $set[$start]["E$start"] = $value['propInstrument']['insCategory'];
                $set[$start]["F$start"] = $setCatalogueCode[$key];
                $set[$start]["G$start"] = $value['assetName'];
                $set[$start]["H$start"] = $value['propAdmin']['condition'];
                $set[$start]["I$start"] = $value['propAdmin']['status'];
                $set[$start]["J$start"] = $value['propAdmin']['riskLevel'];
                $set[$start]["K$start"] = $value['propAdmin']['ownershipType'];
                $set[$start]["L$start"] = '';
                $set[$start]["M$start"] = emptyDefault($value['propAdmin']['priceBuy']);
                $set[$start]["N$start"] = $value['propAdmin']['procureDate'] ? date('d/m/Y', strtotime($value['propAdmin']['procureDate'])) : '';
                $set[$start]["O$start"] = '';
                $set[$start]["P$start"] = $value['assetDesc'];
                $colour[$start]["A$start:P$start"] = 'EEEEEE';
                // end of set data processing
                
                // start of piece data processing
                $pieceParameters[$key]['directFilters']['EXACTOR'][] = array(
                    'column' => 'assetParent',
                    'value' => $value['idAsset']
                );
                $response['piece'][$key] = runAPI('asset/query', 'POST', NULL, $pieceParameters[$key])['data'];
                $start+=1;

                foreach ($response['piece'][$key] as $pieceKey => $pieceValue) {    
                    $pieceCatalogueCodeParameters[$pieceKey]['directFilters']['EXACTOR'][] = array(
                        'column' => 'idAssetMaster',
                        'value' => $pieceValue['idAssetMaster']
                    );
                    $pieceCatalogueCode[$pieceKey] = runAPI('asset/masterquery', 'POST', NULL, $pieceCatalogueCodeParameters[$pieceKey])['data'][0]['productCode'];

                    $piece[$start]["A$start"] = '**';
                    $piece[$start]["B$start"] = 'MIP-'.$value['idAsset'].'-'.$pieceValue['idAsset'];
                    $piece[$start]["C$start"] = '';
                    $piece[$start]["D$start"] = '';
                    $piece[$start]["E$start"] = $pieceValue['propInstrument']['insCategory'];
                    $piece[$start]["F$start"] = $pieceCatalogueCode[$pieceKey];
                    $piece[$start]["G$start"] = $pieceValue['assetName'];
                    $piece[$start]["H$start"] = $pieceValue['propAdmin']['condition'];
                    $piece[$start]["I$start"] = $pieceValue['propAdmin']['status'];
                    $piece[$start]["J$start"] = $pieceValue['propAdmin']['riskLevel'];
                    $piece[$start]["K$start"] = $pieceValue['propAdmin']['ownershipType'];
                    $piece[$start]["L$start"] = '';
                    $piece[$start]["M$start"] = emptyDefault($pieceValue['propAdmin']['priceBuy']);
                    $piece[$start]["N$start"] = $pieceValue['propAdmin']['procureDate'] ? date('d/m/Y', strtotime($pieceValue['propAdmin']['procureDate'])) : '';
                    $piece[$start]["O$start"] = '';
                    $piece[$start]["P$start"] = $pieceValue['assetDesc'];
                    $start+=1;
                }
                // end of piece data processing
            } else if ($catCode == 'MIP') {
                $setCatalogueCodeParameters[$key]['directFilters']['EXACTOR'][] = array(
                    'column' => 'idAssetMaster',
                    'value' => $value['idAssetMaster']
                );
                $setCatalogueCode[$key] = runAPI('asset/masterquery', 'POST', NULL, $setCatalogueCodeParameters[$key])['data'][0]['productCode'];

                // start of set data processing
                $set[$start]["A$start"] = $catCode.'-'.$value['idAsset'];
                $set[$start]["B$start"] = ($value['assetParent'] ? 'MIS-'.$value['assetParent'] : '');
                $set[$start]["C$start"] = $setCatalogue[$key];
                $set[$start]["D$start"] = $value['assetName'];
                $set[$start]["E$start"] = $value['propAdmin']['condition'];
                $set[$start]["F$start"] = $value['propAdmin']['status'];
                $set[$start]["G$start"] = $value['propAdmin']['riskLevel'];
                $set[$start]["H$start"] = $value['propAdmin']['ownershipType'];
                $set[$start]["I$start"] = '';
                $set[$start]["J$start"] = emptyDefault($value['propAdmin']['priceBuy']);
                $set[$start]["K$start"] = $value['propAdmin']['procureDate'] ? date('d/m/Y', strtotime($value['propAdmin']['procureDate'])) : '';
                $set[$start]["L$start"] = '';
                $set[$start]["M$start"] = $value['assetDesc'];
                $start+=1;
                // end of set data processing
            }
        }
        $end[$start]["A$start"] = '::end::';
        $colour[$start]["A$start"] = 'FFECB8';

        $finalData = array_merge($set, $piece, $end);

        foreach ($finalData as $value) {
            foreach ($value as $cell => $cellValue) {
                $objPHPExcel->getActiveSheet()->setCellValue($cell, $cellValue);
            }
        }

        // colorize
        foreach ($colour as $value) {
            foreach ($value as $cell => $color) {
                $this->cellColor($objPHPExcel, $cell, $color);
            }
        }
        // END OF DATA PROCESSING

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        // Start Export
        $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
        ob_end_clean();
        $objWriter->save('php://output');
    }

    function cellColor($objPHPExcel, $cells, $color){
        $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(
            array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array(
                    'rgb' => $color
                )
            )
        );
    }

    function rekap() {
        $code = inputPost('codes');

        foreach ($code as $key => $idAsset) {
            $parameters['directFilters']['EXACTOR'][] = array(
                'column' => 'idAsset',
                'value' => $idAsset
            );
        }

        $data = runAPI('asset/query', 'POST', NULL, $parameters);

        $html = '<style>
                    * {
                        font-size: 11px;
                        font-family: Arial;
                    }
                    h2 {
                        margin: 0 auto;
                        width: 100%;
                        text-align:center;
                        margin-top: 20px;
                        margin-bottom: 20px;
                        font-size: 14px;
                    }
                    #total {
                        float: right;
                        text-align:right;
                        margin-bottom:1px;
                    }
                    table {
                        width:100%;
                        margin: 0 auto;
                        border:solid 0.5px #ccc;
                    }
                    td, th {
                        border:solid 0.5px #ccc;
                        padding: 5px;
                    }
                    th {
                        background-color: #eee;
                    }
                 </style>

                 <h2>DAFTAR ASSET INSTRUMENT ' . ($this->catCode == env('C_SET') ? 'SET' : '') . '</h2>

                 <span id="total">Total data : '.count($data['data']).'</span>
                 <table cellspacing="0">
                    <thead>
                        <tr>
                            <th width="10">NO</th>
                            <th>KODE SISTEM</th>
                            <th>NAMA ASSET</th>
                            <th>HARGA BELI</th>
                            <th>TANGGAL PEROLEHAN</th>
                        </tr>
                    </thead>
                 <tbody>';

        $no = 1;
        foreach ($data['data'] as $key => $value) {
            $html .= '<tr>
                        <td>'.$no.'</td>
                        <td>'.$value['catCode'].'-'.$value['idAsset'].'</td>
                        <td>'.$value['assetName'].'</td>
                        <td>Rp '.rupiah($value['propAdmin']['priceBuy']).'</td>
                        <td>'.convertDate($value['propAdmin']['procureDate'], 'd/m/Y').'</td>
                      </tr>';
            $no++;
        }

        $html .= '</tbody></table>';
        
        echo json_encode($html);
    }

    public function getContainerPieces() {
        $containerCode = inputGet('id');

        // get set using this box
        $parameters['directFilters']['EXACTOR'][] = array(
            'column' => 'assetParent',
            'value' => $containerCode
        );
        $response['set'] = runAPI('asset/query', 'POST', NULL, $parameters);

        // get the set pieces
        $pieceParameters['directFilters']['EXACTOR'][] = array(
            'column' => 'assetParent',
            'value' => $response['set']['data'][0]['idAsset'],
        );
        $response['pieces'] = runAPI('asset/query', 'POST', NULL, $pieceParameters);

        echo json_encode($response);
    }

}
