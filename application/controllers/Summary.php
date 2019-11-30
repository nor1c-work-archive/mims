<?php

class Summary extends INS_Controller {

    public function __contruct() {
        parent::__construct();
    }

    public function procurements() {
        $data['withoutTable']       = TRUE;
        $data['withoutActButton']   = TRUE;
        $data['withoutForm']        = TRUE;
        $data['withoutDetail']      = TRUE;
        $data['withoutFilter']      = TRUE;
        $data['withoutAdvFilter']   = TRUE;
        $data['withoutImport']      = TRUE;

        render('summary/procurementsGraph', $data, TRUE);
    }

    public function procurementsData() {
        $mode = inputGet('mode');

        if (inputPost()) {
            $parameters['directFilters']['EXACTOR'][] = array(
                'column' => 'catCode',
                'value' => inputPost('catCode')
            );

            $parameters['directFilters']['BETWEEN'][] = array(
                'column' => 'procureDate',
                'value' => inputPost('yearProcurement_first').'-'.monthToInt(inputPost('firstMonth')).'-01'
            );
            $parameters['directFilters']['BETWEEN'][] = array(
                'column' => 'procureDate',
                'value' => inputPost('yearProcurement_last').'-'.monthToInt(inputPost('lastMonth')).'-31'
            );

            $data = runAPI('asset/query', 'POST', NULL, $parameters);
            echo json_encode($data); die();
        } else
            $data = runAPI('asset/list', 'GET', )['data'];

        
        $collectedParameters = array();
        $collectedData = array();
        
        error_reporting(0);
        if ($mode == 'month') {
            $modeFormat = 'M Y';

            foreach ($data as $key => $value) {
                if (!in_array(date($modeFormat, strtotime($value['propAdmin']['procureDate'])), $collectedParameters)) {
                    $collectedParameters[] = date($modeFormat, strtotime($value['propAdmin']['procureDate']));
                }
    
                $collectedData[date($modeFormat, strtotime($value['propAdmin']['procureDate']))] +=1;
            }
    
            echo json_encode(
                array(
                    'categories' => $collectedParameters,
                    'data' => array_values(explode(',', env('MONTH_LIST'))),
                )
            );
        } else {
            $modeFormat = 'Y';

            foreach ($data as $key => $value) {
                if ($value['propAdmin']['procureDate']) {
                    if (!in_array(date($modeFormat, strtotime($value['propAdmin']['procureDate'])), $collectedParameters)) {
                        $collectedParameters[] = date($modeFormat, strtotime($value['propAdmin']['procureDate']));
                    }
        
                    $collectedData[date($modeFormat, strtotime($value['propAdmin']['procureDate']))] += 1;
                } else if ($value['propAdmin']['procureDate'] == null) {
                    if (!in_array('null', $collectedParameters)) {
                        $collectedParameters[] = 'null';
                    }
        
                    $collectedData['null'] += 1;
                }
            }
    
            echo json_encode(
                array(
                    'categories' => $collectedParameters,
                    'data' => array_values($collectedData),
                )
            );
        }
    }
}