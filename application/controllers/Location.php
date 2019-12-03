<?php

class Location extends INS_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->locationType         = uriSegment(3);
        $this->totalUnusedColumn    = 2;
	}
	
	public function selectedColumns() {
		return [
			['column' => 'idLocation', 'alias' => 'ID', 'width' => 5, 'searchable' => FALSE, 'hide' => FALSE],
			['column' => 'locType', 'alias' => 'LOCATION CODE', 'width' => 8, 'searchable' => TRUE, 'hide' => FALSE, 'replaceOrder' => 'idLocation'],
			['column' => 'locName', 'alias' => 'LOCATION NAME', 'width' => 10, 'searchable' => TRUE, 'hide' => FALSE],
			// ['column' => 'dummy.roomBuilding', 'alias' => 'LOKASI GEDUNG', 'width' => 10, 'searchable' => FALSE, 'hide' => (uriSegment(3) == env('URL_BUILDING') ? TRUE : FALSE)],
			['column' => 'locLonglat', 'alias' => 'COORDINATE', 'width' => 15, 'searchable' => TRUE, 'hide' => FALSE],
			['column' => 'locDesc', 'alias' => 'DESCRIPTION', 'width' => 25, 'searchable' => TRUE, 'hide' => FALSE],
		];
	}

    public function index() {
        $data['customDatatable']    = TRUE;
		$data['columnDefinition']   = [$this->selectedColumns(), $this->totalUnusedColumn];

        render('module', $data);
    }

    public function data() {
        $url = 'location/query';

        // quick filter
        if (inputGet('query')) {
            $query = explode('&', inputGet('query'));

            $collectedQuery = [];
            foreach ($query as $queryKey => $queryValue) {
                $key = explode('=', $queryValue)[0];
                $value = explode('=', $queryValue)[1];
                
                $collectedQuery[$key] = $value;
            }

            $column = explode('|', $collectedQuery['column']);
			foreach ($column as $cKey => $cValue) {
				$parameters['search'][$cValue] = $collectedQuery['keyword'];
			}

            $parameters['additional']['locType'] = $collectedQuery['locType'];
        } else {
            $parameters = initParameters($this->selectedColumns(), $this->totalUnusedColumn);
            $parameters['additional']['locType'] = inputGet('locType');
        }

        // filters
        $filters = inputPost('filters');
        if (is_string($filters)) {
            parse_str($filters, $filters);
        }

        $parameters['directFilters'] = [];
		
        if (isset($filters) && !empty($filters)) {
			// simple filters
            if (isset($filters['searchKeyword1']) || isset($filters['searchKeyword2'])) {
				// check first search field
                if ($filters['searchKeyword1'] != '') {
                    if ($filters['searchField1'] == 'all') {
						foreach ($this->selectedColumns() as $allColKey => $selectedColumn) {
							$parameters['search'][$selectedColumn['column']] = $filters['searchKeyword1'];
						}
					} else {
						$parameters['directFilters']['LIKEAND'][] = [
							"column" => ($filters['searchField1'] == 'locType' ? 'idLocation' : $filters['searchField1']),
							"value" => ($filters['searchField1'] == 'locType' ? explode('-', $filters['searchField1'])[1] : $filters['searchKeyword1'])
						];
					}
				}
				// check second search field
                if ($filters['searchKeyword2'] != '') {
                    if ($filters['searchField2'] == 'all') {
						foreach ($this->selectedColumns() as $allColKey => $selectedColumn) {
							$parameters['search'][$selectedColumn['column']] = $filters['searchKeyword2'];
						}
					} else {
						$parameters['directFilters']['LIKEAND'][] = [
							"column" => ($filters['searchField2'] == 'locType' ? 'idLocation' : $filters['searchField2']),
							"value" => ($filters['searchField2'] == 'locType' ? explode('-', $filters['searchField2'])[1] : $filters['searchKeyword2'])
						];
					}
				}
            } else {
				// advanced filters
                foreach ($filters as $paramKey => $paramValue) {
                    if (isset($paramValue['value']) && $paramValue['value'] != '') {
                        if ($paramValue['name'] == 'idLocation') {
                            $parameters['directFilters']['LIKEAND'][] = array(
                                "column" => $paramValue['name'],
                                "value" => ltrim(str_replace($parameters['additional']['locType'].'-', '', $paramValue['value']), '0')
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
            $url = 'location/query';
        }

		$result = runAPI($url, 'POST', NULL, $parameters);
		// jsonE([$url, $parameters]); die();
		
        unset($parameters['limit'], $parameters['page']);
        // $allID = runAPI($url, 'POST', NULL, $parameters)['data'];
        // foreach ($allID as $key => $value) {
        //     $result['allID'][] = $value['idLocation'];
        // }

        generateData($this->selectedColumns(), $result);
    }

    public function getSingleData() {
        $pk = inputGet('id');

        $parameters = [];
        $parameters['directFilters']['EXACTOR'][] = array(
            "column" => 'idLocation',
            "value" => $pk
        );
        
        $result = runAPI('location/query', 'POST', NULL, $parameters);
        jsonE($result['data']);
    }

    public function getImage() {
        $pk = inputGet('id');

        $parameters['reqID'] = $pk;

        $result = runAPI('file/download', 'GET', $parameters, NULL, array('query' => TRUE));
        echo $result;
    }

    public function form() {
        $pk = inputPost('location')[1]['idLocation'];

        $importMode = FALSE;
		$data = inputPost();

        $uploadedFiles = $_FILES;

        
        if (isset($data['location'])) {
			// insert from form
            // transform data
			if ($data['location'][1]['idLocation']) {
				$data = $data['location'][1];
				$collectedData = array(
					'idLocation' => ($pk ? $data['idLocation'] : 0),
					'parentLoc' => explode('-', explode('|', $data['parentLoc'])[0])[1],
					'locType' => $data['locType'],
					'locName' => $data['locName'],
					'locLonglat' => $data['locLonglat'],
					'locDesc' => $data['locDesc'],	
				);
				(object)$collectedData;
			} else {
				$collectedData = [];
				$no = 0;
				foreach ($data['location'] as $key => $value) {
					$collectedData[$no] = array(
						'idLocation' => ($pk ? $value['idLocation'] : 0),
						'parentLoc' => explode('-', explode('|', $data['parentLoc'])[0])[1],
						'locType' => $value['locType'],
						'locName' => $value['locName'],
						'locLonglat' => $value['locLonglat'],
						'locDesc' => $value['locDesc'],
					);
					$no++;
				}
			}
        } else {
            // insert from file import (excel)
            $importMode = TRUE;

            $inserted = [];
            $updated = [];
            $ignored = [];

            $data = json_decode($data['data'], true);
            foreach ($data as $key => $value) {
                if ($value['status'] == 'inserted') {
                    $inserted[] = array(
                        'idLocation' => 0,
                        'locType' => $value['locType'],
						'locName' => $value['locName'],
						'locLonglat' => $value['locLonglat'],
						'locDesc' => $value['locDesc'],
                    );
                } else if ($value['status'] == 'updated') {
                    if ($value['KODE SISTEM'])
                        $idLocation[$key] = explode('-', $value['KODE SISTEM'])[1];
                    else {
                        $parameters[$key]['directFilters']['EXACTOR'][] = array(
                            'column' => 'idLocation',
                            'value' => $value['LOCATION CODE']
                        );
                        $idLocation[$key] = runAPI('location/query', 'POST', NULL, $parameters[$key])['data'][0]['idLocation'];
                    }

                    $updated[] = array(
                        'idLocation' => (int)$idLocation[$key],
                        'locType' => $value['locType'],
                        'locName' => $value['LOCATION NAME'],
                        'locLonglat' => $value['LOCATION COORDINATE'],
                        'locDesc' => $value['DESCRIPTION'],
                    );
                }
            }
        }

        // Run API
        if ($importMode) {
            $response['insertResponse'] = runAPI('location/update', 'POST', NULL, $inserted);
            $response['updateResponse'] = runAPI('location/update', 'POST', NULL, $updated);
        } else {
            $url = $pk ? 'update' : 'BulkInsert';
            $response = runAPI('location/'.$url, 'POST', NULL, $collectedData);
        }
        
        jsonE($response); die();
    }

    public function importPreview() {
        set_time_limit(0);
        ini_set('memory_limit', '99999999999999999M');
        ini_set('upload_max_filesize', '99999999999999999M');
        ini_set('post_max_size', '99999999999999999M');
        ini_set('max_input_time', '99999999999999999');
        ini_set('max_execution_time', '99999999999999999');

        if (isset($_FILES['file'])) {

            $result = runAPI('asset/masterlist', 'GET');

            $DB_ID = [];
            foreach ($result['data'] as $value) {
                $DB_ID[] = $value['productCode'];
            }
            
            loadLibrary('PHPExcel');
            $file = $_FILES['file']['tmp_name'];
            $inputFileType = PHPExcel_IOFactory::identify($file);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objReader->setReadDataOnly(true);
            $objPHPExcel = $objReader->load($file);
            
            $objWorksheet = $objPHPExcel->getActiveSheet();
            
            $fileData = [];
            $fileDataOnly = [];

            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                if ($objPHPExcel->getIndex($worksheet) == 0) {
                    $highestRow = $worksheet->getHighestDataRow();
                    $highestColumn = $worksheet->getHighestDataColumn();
                    $headings = $worksheet->rangeToArray('A2:' . $highestColumn . 2, NULL, TRUE, FALSE);
    
                    $i = 0;
                    for ($row = 3; $row <= $highestRow; $row++) {
                        $rowData = $worksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                        $rowData = array_combine($headings[0], $rowData[0]);
                        
                        if ($rowData['KODE SISTEM'] == '::end::')
                            break;

                        $fileData[$i][] = $rowData;
                        $i++;
                    }
                }
            }

            $inserted = 0;
            $updated = 0;
            $ignored = 0;

            // INITIALIZE TABLE HEADER
            $tableHeader = array('KODE SISTEM', 'KODE KATALOG', 'NAMA KATALOG', 'MERK', 'FOTO', 'MARK');

            // INITIALIZE DATA
            $fileDataOnly   = [];
            $finalData      = [];
            foreach ($fileData as $key => $value) {
                
                // push to finalData var
                $finalData[$key] = $value[0];
                $finalData[$key]['locType'] = inputGet('locType');

                if ($value[0]['KODE KATALOG'] == '' || $value[0]['NAMA KATALOG'] == '') {
                    $ignored += 1;
                    $reason = '<b style="color:red">Required field harus diisi!</b>';

                    $finalData[$key]['status'] = 'ignored';
                } else {
                    if (in_array($value[0]['KODE KATALOG'], $DB_ID) || $value[0]['KODE SISTEM']) {
                        $updated += 1;
                        $reason = '<b style="color:orange">Kode Katalog sudah terdaftar, data akan diupdate!</b>';
                    
                        $finalData[$key]['status'] = 'updated';
                        $finalData[$key]['idLocation'] = 'updated';
                    } else {
                        $inserted += 1;
                        $reason = '-';   

                        $finalData[$key]['status'] = 'inserted';
                    }
                }

                foreach ($value as $v_key => $v_value) {
                    $fileDataOnly[$key]     = array_values($v_value);
                    $fileDataOnly[$key][]   = $reason;
                }
            }

            $finalData = json_encode($finalData);

            $note = $inserted . ' data akan diimport, ' . $updated . ' data akan diupdate, dan ' . $ignored . ' data akan diabaikan dari total ' . count($fileData) . ' baris data.';

            jsonE(
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

        $response = runAPI('location/delete?idLocation='.$rowsSelected[0], 'POST');
        jsonE($response);
    }

    public function expandableContent() {
        $id = inputGet('id');

        $html = '<div style="width:100%;background-color:#fff;text-align:center;padding:0px 5px 5px 5px;margin-top:-2px;">
                <i class="fas fa-caret-up" style="color:#000;padding:0;border-bottom:solid 1px #ccc;width:100%;"></i>
                <table id="child" class="table table-striped border child-table" style="width:100%">';
        $data = 1;
        if ($data) {
            $html .= '<thead>
                        <tr>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>No Data Found!</td>
                        </tr>
                    </tbody>';
        } else {
            $html .= '<tbody>
                        <tr>
                            <td>No Data Found!</td>
                        </tr>
                    </tbody>';
        }
        $html .= '</table></div>';
        
        jsonE($html);
    }

    function rekap() {
        $locType = inputPost('locType');
        $code = is_array(inputPost('codes')) ? inputPost('codes') : explode(',', inputPost('codes'));
        $mode = inputGet('mode') ? inputGet('mode') : '';
        
        foreach ($code as $key => $idLocation) {
            $parameters['directFilters']['EXACTOR'][] = array(
                'column' => 'idLocation',
                'value' => $idLocation
            );
        }
        $data = runAPI('location/query', 'POST', NULL, $parameters);
        
        $html = '<style type="text/css" media="all">
                    @import url("https://fonts.googleapis.com/css?family=Roboto&display=swap");
                    * {
                        font-size: 11px;
                        font-family: Roboto;
                    }
                    h2 {
                        margin: 0 auto;
                        width: 100%;
                        text-align:center;
                        //margin-top: 20px;
                        //margin-bottom: 20px;
                        font-size: 14px;
                        font-family: Roboto !important;
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
                        font-family: Roboto !important;
                    }
                    th {
                        background-color: #eee;
                    }
                 </style>

                 <br>
                 <h2>
                    DAFTAR ' . strtoupper($locType) . ' 
                    <br>PER TGL: '.date('d/m/Y', time()) 
                 .'</h2>
                 <br><br>

                 <span id="total">Total data : '.count($data['data']).'</span>
                 <br><br>
                 <table cellspacing="0">
                    <thead>
                        <tr>
                            <th width="10">NO</th>
                            <th>KODE SISTEM</th>
                            <th>NAMA '.strtoupper($locType).'</th>
                            <th>KOORDINAT</th>
                            <th>DESKRIPSI</th>
                        </tr>
                    </thead>
                 <tbody>';

        $no = 1;
        foreach ($data['data'] as $key => $value) {
            $html .= '<tr>
                        <td>'.$no.'</td>
                        <td>'.env('L_'.strtoupper($value['locType'])).'-'.$value['idLocation'].'</td>
                        <td>'.$value['locName'].'</td>
                        <td>'.$value['locLonglat'].'</td>
                        <td>'.$value['locDesc'].'</td>
                      </tr>';
            $no++;
        }

        $html .= '</tbody></table>';
        
        if ($mode == 'pdf') {
            $filename = 'Rekap Daftar ' . ucfirst($locType) . ', per tgl ' . date('d-M-Y', time()) .'.pdf';
            rekapPdf($html, $filename);
        } else if ($mode == 'xlsx')
            rekapExcel($html);
        else
            jsonE($html);
    }

    function generateBarcode($format = 'png', $symbology = 'dmtx', $option = '') {
        $code = inputPost('codes');

        require_once('./application/libraries/barcode.php');

        $generator = new barcode_generator();
        
        $barcodes = '';
        foreach ($code as $key => $value) {
            $value = uriSegment(1).'-'.$value;

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
            $value = uriSegment(1).'-'.$value;

            $svg = $generator->render_svg($symbology, $value, $option);
            echo $svg;
        }
    }

    public function export() {
        $code = inputGet('codes');

        $code = explode(',', $code);

        // Run API
        foreach ($code as $key => $idLocation) {
            $parameters['directFilters']['EXACTOR'][] = array(
                'column' => 'idLocation',
                'value' => $idLocation
            );
        }
        $data = runAPI('location/query', 'POST', NULL, $parameters);

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
        $exportData = [];
        foreach ($data['data'] as $key => $value) {
            $exportData[] = array(
                $value['productCode'], $value['assetMasterName'], $value['merk']
            );
        }

        $sheet_details = array(
                            0 => array(
                                'sheet_title'   => 'data',
                                'sheet_heading' => array('KODE KATALOG', 'NAMA KATALOG', 'MERK'),
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
                foreach ($report_details as $data) {
                    $objWorkSheet->setCellValue($columns[$column] . $row, $data);
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

    public function merk() {
        $url = 'location/query';

        if (inputGet('query')) {
            $query = explode('&', inputGet('query'));

            $collectedQuery = [];
            foreach ($query as $queryKey => $queryValue) {
                $key = explode('=', $queryValue)[0];
                $value = explode('=', $queryValue)[1];
                
                $collectedQuery[$key] = $value;
            }

            $column = explode('|', $collectedQuery['column']);
            foreach ($column as $cKey => $cValue) {
                $parameters['search'][$cValue] = $collectedQuery['keyword'];
            }

            $parameters['additional']['locType'] = $collectedQuery['locType'];
        } else {
            $parameters = initParameters($this->selectedColumns(), $this->totalUnusedColumn);
            $parameters['additional']['locType'] = inputGet('locType');
        }

        $response = runAPI($url, 'POST', NULL, $parameters);

        $collectedMerk = [];
        foreach ($response['data'] as $key => $value) {
            if (!in_array($value['merk'], $collectedMerk)) {
                $collectedMerk[] = $value['merk'];
            }
        }

        jsonE($collectedMerk);
    }

}
