<?php

class Picker extends INS_Controller {

    public function __construct() {
        parent::__construct();
        $this->mode = inputGet('mode');
    }

    public function boxContainer() {
        // initialize
        $data['totalUnusedColumn']  = '2';
        $data['selectedColumns']    = array(
                                        array('column' => 'idAsset', 'alias' => 'ID', 'width' => 10, 'searchable' => FALSE, 'hide' => FALSE),
                                        array('column' => 'assetParent', 'alias' => 'KODE SISTEM', 'width' => 10, 'searchable' => TRUE, 'hide' => FALSE),
                                        array('column' => 'assetName', 'alias' => 'NAMA ASET', 'width' => 30, 'searchable' => TRUE, 'hide' => FALSE),
                                        array('column' => 'propAdmin.idLocation', 'alias' => 'LOKASI', 'width' => 30, 'searchable' => TRUE, 'hide' => TRUE),
                                        array('column' => 'propAdmin.condition', 'alias' => 'KONDISI', 'width' => 10, 'searchable' => FALSE, 'hide' => FALSE),
                                        array('column' => 'dummy.containerAvailabilityStatus', 'alias' => 'AVAILABILITY STATUS', 'width' => 15, 'searchable' => FALSE, 'hide' => FALSE),
                                        // array('column' => 'propAdmin.priceBuy', 'alias' => 'HARGA BELI', 'width' => 10, 'searchable' => TRUE, 'hide' => FALSE),
                                        // array('column' => 'propAdmin.procureDate', 'alias' => 'TANGGAL PEROLEHAN', 'width' => 15, 'searchable' => FALSE, 'hide' => FALSE),
                                        // array('column' => 'propInstrument.insCategory', 'alias' => 'KATEGORI', 'width' => 20, 'searchable' => FALSE, 'hide' => FALSE)
                                      );
        
        if ($this->mode == 'data') {
            // list of all used container
            $allAssets = runAPI('asset/list', 'GET')['data'];

            $assetHasChild = array();
            foreach ($allAssets as $key => $value) {
                $assetHasChild[] = $value['assetParent'];
            }

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
                $parameters = initParameters($data['selectedColumns'], $data['totalUnusedColumn']);
                $parameters['additional']['catCode'] = 'MIC';
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
                        $filterMode1 = /*$filters['searchMode1']*/ 'LIKEAND';

                        if ($filterMode1 == 'LESSTHAN') {
                            $parameters['directFilters']['BETWEEN'][] = array(
                                "column" => ($filters['searchField1'] == 'assetParent' ? 'idAsset' : $filters['searchField1']),
                                "value" => ''
                            );
                            $parameters['directFilters']['BETWEEN'][] = array(
                                "column" => ($filters['searchField1'] == 'assetParent' ? 'idAsset' : $filters['searchField1']),
                                "value" => (strpos(inputGet('catCode').'-', $filters['searchKeyword1']) !== TRUE ? str_replace(inputGet('catCode').'-', '', $filters['searchKeyword1']) : $filters['searchKeyword1'])
                            );
                        } else if ($filterMode1 == 'MORETHAN') {
                            $parameters['directFilters']['BETWEEN'][] = array(
                                "column" => ($filters['searchField1'] == 'assetParent' ? 'idAsset' : $filters['searchField1']),
                                "value" => (strpos(inputGet('catCode').'-', $filters['searchKeyword1']) !== TRUE ? str_replace(inputGet('catCode').'-', '', $filters['searchKeyword1']) : $filters['searchKeyword1'])
                            );
                            $parameters['directFilters']['BETWEEN'][] = array(
                                "column" => ($filters['searchField1'] == 'assetParent' ? 'idAsset' : $filters['searchField1']),
                                "value" => '999999999999999999999999999999999999999999999999'
                            );
                        } else {
                            $parameters['directFilters'][$filterMode1][] = array(
                                "column" => ($filters['searchField1'] == 'assetParent' ? 'idAsset' : $filters['searchField1']),
                                "value" => ($filters['searchField1'] == 'assetParent' ? str_replace(inputGet('catCode').'-', '', $filters['searchKeyword1']) : $filters['searchKeyword1'])
                            );
                        }
                    }
                    if ($filters['searchKeyword2'] != '') {
                        $filterMode2 = $filters['searchMode2'];

                        if ($filterMode2 == 'LESSTHAN') {
                            $parameters['directFilters']['BETWEEN'][] = array(
                                "column" => ($filters['searchField2'] == 'assetParent' ? 'idAsset' : $filters['searchField2']),
                                "value" => ''
                            );
                            $parameters['directFilters']['BETWEEN'][] = array(
                                "column" => ($filters['searchField2'] == 'assetParent' ? 'idAsset' : $filters['searchField2']),
                                "value" => (strpos(inputGet('catCode').'-', $filters['searchKeyword2']) !== TRUE ? str_replace(inputGet('catCode').'-', '', $filters['searchKeyword2']) : $filters['searchKeyword2'])
                            );
                        } else if ($filterMode2 == 'MORETHAN') {
                            $parameters['directFilters']['BETWEEN'][] = array(
                                "column" => ($filters['searchField2'] == 'assetParent' ? 'idAsset' : $filters['searchField2']),
                                "value" => (strpos(inputGet('catCode').'-', $filters['searchKeyword2']) !== TRUE ? str_replace(inputGet('catCode').'-', '', $filters['searchKeyword2']) : $filters['searchKeyword2'])
                            );
                            $parameters['directFilters']['BETWEEN'][] = array(
                                "column" => ($filters['searchField2'] == 'assetParent' ? 'idAsset' : $filters['searchField2']),
                                "value" => '999999999999999999999999999999999999999999999999'
                            );
                        } else {
                            $parameters['directFilters'][$filterMode2][] = array(
                                "column" => ($filters['searchField2'] == 'assetParent' ? 'idAsset' : $filters['searchField2']),
                                "value" => ($filters['searchField1'] == 'assetParent' ? str_replace(inputGet('catCode').'-', '', $filters['searchKeyword2']) : $filters['searchKeyword2'])
                            );
                        }
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

            // $inUsedContainer = array();
            // foreach ($result['data'] as $key => $value) {
            //     if (in_array($value['idAsset'], $assetHasChild)) {
            //         unset($result['data'][$key]);
            //     }
            // }

            generateData($data['selectedColumns'], $result);
        } else {
            $data['customDatatable']    = TRUE;
            $data['columnDefinition']   = array($data['selectedColumns'], $data['totalUnusedColumn']);
            $data['withExtraFilter']    = TRUE;
            $data['withFilterMode']     = TRUE;
            $data['withoutActButton']   = TRUE;
            $data['withoutForm']        = TRUE;
            $data['withoutDetail']      = TRUE;
            $data['withoutFilter']      = TRUE;
            $data['withoutAdvFilter']   = TRUE;
            $data['withoutImport']      = TRUE;

            render('pages/pickers/boxPicker', $data);
        }
	}

	// building picker
	public function building() {
		// initialize
		$data['totalUnusedColumn']  = '2';
		$data['selectedColumns']    = [
			['column' => 'idLocation', 'alias' => 'ID', 'width' => 5, 'searchable' => FALSE, 'hide' => FALSE],
			['column' => 'locType', 'alias' => 'LOCATION CODE', 'width' => 8, 'searchable' => TRUE, 'hide' => FALSE, 'replaceOrder' => 'idLocation'],
			['column' => 'locName', 'alias' => 'LOCATION NAME', 'width' => 10, 'searchable' => TRUE, 'hide' => FALSE],
			['column' => 'locLonglat', 'alias' => 'COORDINATE', 'width' => 15, 'searchable' => TRUE, 'hide' => FALSE],
			['column' => 'locDesc', 'alias' => 'DESCRIPTION', 'width' => 30, 'searchable' => TRUE, 'hide' => FALSE],
		];

		if ($this->mode == 'data') {
			// list of all used container
			$allAssets = runAPI('location/list', 'GET')['data'];

			$assetHasChild = array();
			foreach ($allAssets as $key => $value) {
				$assetHasChild[] = $value['parentLoc'];
			}

			$url = 'location/query';

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

				$parameters['additional']['locType'] = $collectedQuery['locType'];
			} else {
				$parameters = initParameters($data['selectedColumns'], $data['totalUnusedColumn']);
				$parameters['additional']['locType'] = 'BUILDING';
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
						$filterMode1 = /*$filters['searchMode1']*/ 'LIKEAND';

						if ($filterMode1 == 'LESSTHAN') {
							$parameters['directFilters']['BETWEEN'][] = array(
								"column" => ($filters['searchField1'] == 'parentLoc' ? 'idLocation' : $filters['searchField1']),
								"value" => ''
							);
							$parameters['directFilters']['BETWEEN'][] = array(
								"column" => ($filters['searchField1'] == 'parentLoc' ? 'idLocation' : $filters['searchField1']),
								"value" => (strpos(inputGet('locType') . '-', $filters['searchKeyword1']) !== TRUE ? str_replace(inputGet('locType') . '-', '', $filters['searchKeyword1']) : $filters['searchKeyword1'])
							);
						} else if ($filterMode1 == 'MORETHAN') {
							$parameters['directFilters']['BETWEEN'][] = array(
								"column" => ($filters['searchField1'] == 'parentLoc' ? 'idLocation' : $filters['searchField1']),
								"value" => (strpos(inputGet('locType') . '-', $filters['searchKeyword1']) !== TRUE ? str_replace(inputGet('locType') . '-', '', $filters['searchKeyword1']) : $filters['searchKeyword1'])
							);
							$parameters['directFilters']['BETWEEN'][] = array(
								"column" => ($filters['searchField1'] == 'parentLoc' ? 'idLocation' : $filters['searchField1']),
								"value" => '999999999999999999999999999999999999999999999999'
							);
						} else {
							$parameters['directFilters'][$filterMode1][] = array(
								"column" => ($filters['searchField1'] == 'parentLoc' ? 'idLocation' : $filters['searchField1']),
								"value" => ($filters['searchField1'] == 'parentLoc' ? str_replace(inputGet('locType') . '-', '', $filters['searchKeyword1']) : $filters['searchKeyword1'])
							);
						}
					}
					if ($filters['searchKeyword2'] != '') {
						$filterMode2 = $filters['searchMode2'];

						if ($filterMode2 == 'LESSTHAN') {
							$parameters['directFilters']['BETWEEN'][] = array(
								"column" => ($filters['searchField2'] == 'parentLoc' ? 'idLocation' : $filters['searchField2']),
								"value" => ''
							);
							$parameters['directFilters']['BETWEEN'][] = array(
								"column" => ($filters['searchField2'] == 'parentLoc' ? 'idLocation' : $filters['searchField2']),
								"value" => (strpos(inputGet('locType') . '-', $filters['searchKeyword2']) !== TRUE ? str_replace(inputGet('locType') . '-', '', $filters['searchKeyword2']) : $filters['searchKeyword2'])
							);
						} else if ($filterMode2 == 'MORETHAN') {
							$parameters['directFilters']['BETWEEN'][] = array(
								"column" => ($filters['searchField2'] == 'parentLoc' ? 'idLocation' : $filters['searchField2']),
								"value" => (strpos(inputGet('locType') . '-', $filters['searchKeyword2']) !== TRUE ? str_replace(inputGet('locType') . '-', '', $filters['searchKeyword2']) : $filters['searchKeyword2'])
							);
							$parameters['directFilters']['BETWEEN'][] = array(
								"column" => ($filters['searchField2'] == 'parentLoc' ? 'idLocation' : $filters['searchField2']),
								"value" => '999999999999999999999999999999999999999999999999'
							);
						} else {
							$parameters['directFilters'][$filterMode2][] = array(
								"column" => ($filters['searchField2'] == 'parentLoc' ? 'idLocation' : $filters['searchField2']),
								"value" => ($filters['searchField1'] == 'parentLoc' ? str_replace(inputGet('locType') . '-', '', $filters['searchKeyword2']) : $filters['searchKeyword2'])
							);
						}
					}
				} else {
					foreach ($filters as $paramKey => $paramValue) {
						if (isset($paramValue['value']) && $paramValue['value'] != '') {
							if (in_array($paramValue['name'], array('firstProcureDate', 'lastProcureDate'))) {
								$parameters['directFilters']['BETWEEN'][] = array(
									"column" => "createDate",
									"value" => date('Y-m-d', strtotime(str_replace('/', '-', $paramValue['value'])))
								);
							} else if ($paramValue['name'] == 'idLocationMaster') {
								$parameters['directFilters']['LIKEAND'][] = array(
									"column" => $paramValue['name'],
									"value" => ltrim(str_replace($parameters['additional']['locType'] . '-', '', $paramValue['value']), '0')
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

			// $inUsedContainer = array();
			// foreach ($result['data'] as $key => $value) {
			//     if (in_array($value['idLocation'], $assetHasChild)) {
			//         unset($result['data'][$key]);
			//     }
			// }

			generateData($data['selectedColumns'], $result);
		} else {
			$data['customDatatable']    = TRUE;
			$data['columnDefinition']   = array($data['selectedColumns'], $data['totalUnusedColumn']);
			$data['withExtraFilter']    = TRUE;
			$data['withFilterMode']     = TRUE;
			$data['withoutActButton']   = TRUE;
			$data['withoutForm']        = TRUE;
			$data['withoutDetail']      = TRUE;
			$data['withoutFilter']      = TRUE;
			$data['withoutAdvFilter']   = TRUE;
			$data['withoutImport']      = TRUE;

			render('pages/pickers/buildingPicker', $data);
		}
	}

}
