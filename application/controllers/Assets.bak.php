<?php

class Assets extends INS_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->catCode              = $this->catCode;
        $this->selectedColumns      = array('idAsset' => 'ID', 'assetParent' => 'KODE SISTEM', 'assetName' => 'NAMA ASET', 'propAdmin.condition' => 'KONDISI', 'propAdmin.status' => 'STATUS', 'propAdmin.idLocation' => 'LOKASI', 'propAdmin.priceBuy' => 'HARGA BELI', 'propAdmin.procureDate' => 'TANGGAL PENGADAAN');
        $this->width                = array(10, 10, 40, 15, 10, 30, 10, 15);
        $this->searchableColumns    = array('idAsset' => 'KODE SISTEM', 'assetName' => 'NAMA ASET', 'propAdmin.priceBuy' => 'HARGA BELI');
        $this->totalUnusedColumn    = $this->catCode == 'MIS' ? 3 : 2;
    }

    public function index() {
        $data['customDatatable']    = TRUE;
        $data['columnDefinition']   = array($this->selectedColumns, $this->searchableColumns, $this->totalUnusedColumn);
        $data['width']              = $this->width;

        render('module', $data);
    }

    public function data() {
        $url = 'asset/query';
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
            $parameters = initParameters($this->selectedColumns, $this->totalUnusedColumn);
            $parameters['additional']['catCode'] = inputGet('catCode');
        }

        // init filters
        $filters = inputPost('filters');
        if (is_string($filters)) {
            parse_str($filters, $filters);
        }

        $parameters['directFilters'] = array();

        if (isset($filters) && !empty($filters)) {
            if (isset($filters['searchKeyword1'])) {
                if ($filters['searchKeyword1'] != '') {
                    $parameters['directFilters']['LIKEAND'][] = array(
                        "column" => $filters['searchField1'],
                        "value" => $filters['searchKeyword1']
                    );
                }
                if ($filters['searchKeyword2'] != '') {
                    $parameters['directFilters']['LIKEAND'][] = array(
                        "column" => $filters['searchField2'],
                        "value" => $filters['searchKeyword2']
                    );
                }
            } else {
                foreach ($filters as $paramKey => $paramValue) {
                    if (isset($paramValue['value']) && $paramValue['value'] != '') {
                        if (in_array($paramValue['name'], array('firstProcureDate', 'lastProcureDate'))) {
                            $parameters['directFilters']['BETWEEN'][] = array(
                                "column" => "createDate",
                                "value" => date('Y-m-d', strtotime(str_replace('/', '-', $paramValue['value'])))
                            );
                        } else if ($paramValue['name'] == 'idAssetMaster') {
                            $parameters['directFilters']['LIKEAND'][] = array(
                                "column" => $paramValue['name'],
                                "value" => ltrim(str_replace($parameters['additional']['catCode'].'-', '', $paramValue['value']), '0')
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

        generateData($this->selectedColumns, $result);
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
                        'idAssetMaster' => $assetValue['idAssetMaster'],
                        'catCode' => $assetValue['catCode'],
                        'assetName' => (isset($assetValue['assetName']) ? $assetValue['assetName'] : $assetName[$i]['data'][0]['assetMasterName']),
                        'assetDesc' => (isset($assetValue['assetDesc']) ? $assetValue['assetDesc'] : null),
                        'assetParent' => (isset($assetValue['assetParent']) ? $assetValue['assetParent'] : 0),
                        'hasPropAdmin' => true,
                        'propAdmin' => array(
                            'idAsset' => ($pk ? $assetValue['idAsset'] : 0),
                            'riskLevel' => '',
                            'ownershipType' => '',
                            'condition' => '',
                            'status' => '',
                            'priceBuy' => ($assetValue['priceBuy'] ? str_replace('.', '', $assetValue['priceBuy']) : 0),
                            'procureDate' => date('Y-m-d', strtotime(str_replace('/', '-', $assetValue['procureDate'])))
                        ),
                    );
                }
            }

            $response = runAPI('asset/'.($pk ? 'update' : 'insert'), 'POST', NULL, $set);
            $parentID = ($pk ? $pk : $response['data'][0]);
            // END OF INSERT/UPDATE SET

            if (!inputPost('isPiece')) {
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
        }
    }

    public function importPreview() {
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
            $CurrentWorkSheetIndex = 1;
            $fileData = array();
            $fileDataOnly = array();

            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                $highestRow = $worksheet->getHighestDataRow();
                $highestColumn = $worksheet->getHighestDataColumn();
                $headings = $worksheet->rangeToArray('A2:' . $highestColumn . 2, NULL, TRUE, FALSE);

                $i = 1;
                for ($row = 3; $row <= $highestRow; $row++) {
                    $rowData = $worksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                    $rowData = array_combine($headings[0], $rowData[0]);
                    $fileData[$i][] = $rowData;
                    $i++;
                }
            }

            $inserted = 0;
            $updated = 0;
            $ignored = 0;

            // INITIALIZE TABLE HEADER
            $tableHeader = array('KODE SISTEM', 'KODE KATALOG', 'NAMA ALIAS ASET', 'KONDISI', 'STATUS', 'LEVEL RESIKO', 'KEPEMILIKAN', 'LOKASI', 'HARGA BELI', 'TANGGAL PENGADAAN', 'SUPPLIER', 'KETERANGAN');

            // INITIALIZE DATA
            $fileDataOnly   = array();
            $finalData      = array();
            foreach ($fileData as $key => $value) {
                if ($value[0]['MARK'] != '') {
                    // push to finalData var
                    $finalData[$key] = $value[0];
                    $finalData[$key]['catCode'] = inputGet('catCode');

                    if ($value[0]['KODE KATALOG'] == '' || $value[0]['NAMA ALIAS ASET'] == '' || $value[0]['KONDISI'] == '' || $value[0]['STATUS'] == '' || $value[0]['LEVEL RESIKO'] == '' || $value[0]['KEPEMILIKAN'] == '') {
                        $ignored += 1;
                        $reason = '<b style="color:red">Required field harus diisi!</b>';

                        $finalData[$key]['status'] = 'ignored';
                    } else {
                        if (in_array($value[0]['KODE SISTEM'], $DB_ID)) {
                            $updated += 1;
                            $reason = '<b style="color:orange">Kode Katalog sudah terdaftar, data akan diupdate!</b>';
                        
                            $finalData[$key]['status'] = 'updated';
                            $finalData[$key]['idAssetMaster'] = 'updated';
                        } else {
                            $inserted += 1;
                            $reason = '';   

                            $finalData[$key]['status'] = 'inserted';
                        }
                    }

                    foreach ($value as $v_key => $v_value) {
                        $fileDataOnly[$key]     = array_values($v_value);
                        $fileDataOnly[$key][]   = $reason;
                    }
                }
            }

            $finalData = json_encode($finalData);

            $note = $inserted . ' data akan diimport, ' . $updated . ' data akan diupdate, dan ' . $ignored . ' data akan diabaikan dari total ' . count($fileData) . ' baris data.';

            echo json_encode(
                array(
                    'header'    => $tableHeader,
                    'data'      => $fileDataOnly,
                    'raw'       => $finalData,
                    'note'      => $note
                )
            );
        }
    }

    public function delete() {
        $rowsSelected = inputPost('ids');

        if (inputGet('catCode') == 'MIS') {
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
                    #expandableTable'.$id.'_length, #expandableTable'.$id.'_info {
                        float:left;
                    }
                    #expandableTable'.$id.'_paginate {
                        padding-top: 5px !important;
                    }
                </style>
                <div style="width:100%;background-color:#fff;text-align:center;padding:0px 5px 5px 5px;margin-top:-2px;">
                <i class="fas fa-caret-up" style="color:#000;padding:0;border-bottom:solid 1px #ccc;width:100%;"></i>
                <br><br>
                <table id="expandableTable'.$id.'" class="table table-bordered" style="width:71%;text-align:left;background-color:#fff;">';

        if (count($pieces['data']) > 0) {
            $html .=    '<thead>
                            <tr style="background-color:#eee">
                                <th width="50">NO</th>
                                <th width="100">KODE SISTEM</th>
                                <th width="200">KODE KATALOG</th>
                                <th>NAMA ASET</th>
                                <th width="100">HARGA BELI</th>
                                <th width="120">TANGGAL PENGADAAN</th>
                            </tr>
                        </thead>
                        <tbody>';

            $no = 1;
            foreach ($pieces['data'] as $key => $value) {

                $pieceParameters = array();
                $pieceParameters['directFilters']['EXACTOR'][] = array(
                    'column' => 'assetMasterName',
                    'value' => $value['assetName']
                );

                $catalogueCode[$key] = runAPI('asset/masterquery', 'POST', NULL, $pieceParameters);

                $html .= '<tr>
                            <td>'.$no.'</td>
                            <td>'.'MIP-'.$id.'-'.$value['idAsset'].'</td>
                            <td>'.$catalogueCode[$key]['data'][0]['productCode'].'</td>
                            <td>'.$value['assetName'].'</td>
                            <td>'.($value['propAdmin']['priceBuy'] ? 'Rp '.rupiah($value['propAdmin']['priceBuy']) : '-').'</td>
                            <td>'.convertDate($value['propAdmin']['procureDate'], 'd/m/Y').'</td>
                          </tr>';
                $no++;
            }

            $html .= '</tbody>';
        } else {
            $html .=    '<tr style="text-align:center;margin-top:-100px;">
                            <th>This set doesn\'t have any instruments!</th>
                         </tr>';
        }

        $html .= '</thead></table></div>
                <script>
                    $(document).ready(function() {
                        $("#expandableTable'.$id.'").DataTable({
                            "aLengthMenu": [[5, 10, 25, 50, 75, -1], [5, 10, 25, 50, 75, "All"]],
                            "pageLength": 5,
                        });

                        $("#expandableTable'.$id.'_wrapper").css("width", "71.5%");
                        $("#expandableTable'.$id.'_wrapper").css("float", "left");
                    });
                </script>
        ';
        
        echo json_encode($html);
    }

    function generateBarcode($format = 'png', $symbology = 'dmtx', $option = '') {
        $code = inputPost('codes');

        require_once('./application/libraries/barcode.php');

        $generator = new barcode_generator();
        
        $barcodes = '';
        foreach ($code as $key => $value) {
            $parameters[$value]['directFilters']['EXACTOR'][] = array(
                'column' => 'idAsset',
                'value' => $value
            );
            $response[$value] = runAPI('asset/query', 'POST', NULL, $parameters[$value]);
            $response[$value] = $response[$value]['data'][0];

            $value = $response[$value]['catCode'].'-'.$response[$value]['idAsset'];

            $svg = $generator->render_svg($symbology, $value, $option);
            $barcodes .= $svg . '<br>';
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
        $code = inputGet('codes');
        $code = explode(',', $code);

        // Run API
        foreach ($code as $key => $idAsset) {
            $parameters['directFilters']['EXACTOR'][] = array(
                'column' => 'idAsset',
                'value' => $idAsset
            );
        }
        $response['asset'] = runAPI('asset/query', 'POST', NULL, $parameters);

        set_time_limit(0);
        ini_set('memory_limit', '99999999999999999M');
        ini_set('upload_max_filesize', '99999999999999999M');
        ini_set('post_max_size', '99999999999999999M');
        ini_set('max_input_time', '99999999999999999');
        ini_set('max_execution_time', '99999999999999999');

        $this->load->library('PHPExcel');
        include APPPATH . "libraries/PHPExcel/IOFactory.php";

        $filename = 'non_med_' . date('Y-m-d', time());
        $filename_exists = 0;

        if (!file_exists($filename)) {
            $objPHPExcel = new PHPExcel();
            ob_start(null, 4096);
        } else {
            $filename_exists = 1;
            $objPHPExcel = PHPExcel_IOFactory::load($filename);
        }
        
        // collect data
        $exportData = array();
        foreach ($response['asset']['data'] as $key => $value) {    
            $catalogueParameters[$key]['directFilters']['EXACTOR'][] = array(
                'column' => 'idAssetMaster',
                'value' => $value['idAssetMaster']
            );
            $response['catalogue'][$key] = runAPI('asset/masterquery', 'POST', NULL, $catalogueParameters[$key]);

            $exportData[] = array(
                $value['catCode'].'-'.$value['idAsset'], 
                $value['assetName'], 
                $response['catalogue'][$key]['data'][0]['productCode'], 
                ($value['propAdmin']['priceBuy'] ? $value['propAdmin']['priceBuy'] : ''), 
                ($value['propAdmin']['procureDate'] ? date('d/m/Y', strtotime($value['propAdmin']['procureDate'])) : '')
            );
        }

        $sheet_details = array(
                            0 => array(
                                'sheet_title'   => 'data',
                                'sheet_heading' => array('KODE SISTEM', 'NAMA ASSET', 'KODE KATALOG', 'HARGA BELI', 'TANGGAL PENGADAAN'),
                                'sheet_data'    => $exportData
                            )
                        );

        $columns = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $column = 0;

        $row = 1;
        for ($sheet_count=0; $sheet_count < count($sheet_details); $sheet_count++) { 
            $objWorkSheet = '';
            
            // sheet
            if ($sheet_count > 0) {
                $objWorkSheet = $objPHPExcel->createSheet($sheet_count);
            } else {
                $objWorkSheet = $objPHPExcel->getActiveSheet();
            }

            // sheet heading
            $row = 1;
            $column = 0;
            foreach ($sheet_details[$sheet_count]['sheet_heading'] as $head) {
                $objWorkSheet->setCellValue($columns[$column] . $row, $head);
                $column++;
            }

            // sheet data
            $row = $objWorkSheet->getHighestRow() + 1; //row count
            foreach ($sheet_details[$sheet_count]['sheet_data'] as $report_details) {
                $column = 0;
                foreach ($report_details as $response['asset']) {
                    $objWorkSheet->setCellValue($columns[$column] . $row, $response['asset']);
                    $column++;
                }
                $row++;
            }

            $objWorkSheet->setTitle($sheet_details[$sheet_count]['sheet_title']);
            // $sheet_count++;
        }

        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename=' . $filename . ".xlsx");
        header('Cache-Control: max-age=0');

        $objWriter->save('php://output');
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

                 <h2>DAFTAR ASSET INSTRUMENT ' . ($this->catCode == 'MIS' ? 'SET' : '') . '</h2>

                 <span id="total">Total data : '.count($data['data']).'</span>
                 <table cellspacing="0">
                    <thead>
                        <tr>
                            <th width="10">NO</th>
                            <th>KODE SISTEM</th>
                            <th>NAMA ASSET</th>
                            <th>HARGA BELI</th>
                            <th>TANGGAL PENGADAAN</th>
                        </tr>
                    </thead>
                 <tbody>';

        $no = 1;
        foreach ($data['data'] as $key => $value) {
            $html .= '<tr>
                        <td>'.$no.'</td>
                        <td>'.$value['catCode'].'-'.$value['idAsset'].'</td>
                        <td>'.$value['assetName'].'</td>
                        <td>'.rupiah($value['propAdmin']['priceBuy']).'</td>
                        <td>'.convertDate($value['propAdmin']['procureDate'], 'd/m/Y').'</td>
                      </tr>';
            $no++;
        }

        $html .= '</tbody></table>';
        
        echo json_encode($html);
    }

}
