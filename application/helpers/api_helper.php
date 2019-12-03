<?php

/**
 * @param $apiURI
 * @param $method
 * @param null $parameters
 * @param null $data
 * @param null $additionalOptions
 * @param null $newToken
 * @return bool|mixed|string
 */
function runAPI($apiURI, $method, $parameters = NULL, $data = NULL, $additionalOptions = NULL, $newToken = NULL) {
	$rawData = $data;
    $rawParameters = $parameters;

    $baseAPIURL = env('API_URI');

    $url = $baseAPIURL . $apiURI;

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_USERAGENT, 'any');
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_AUTOREFERER, false);

    if (strstr($apiURI, 'file/download')) {
		// file detail
		$fileDetail = runAPI(str_replace('download', 'ByID', $apiURI), 'GET');
		$filename 	= $fileDetail['data']['fileName'];

		$fileLocation = env('TEMP_IMAGE_DIR').$filename;

		if (!file_exists($fileLocation)) {
			$headers[] = 'Authorization: Bearer ' . sessionData('token');
			$headers[] = 'Access-Control-Allow-Origin: *';
			$headers[] = 'X-HTTP-Method-Override: GET';
			$headers[] = 'Accept: application/json';
			$headers[] = 'Content-Type: application/json; charset=utf-8';

			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$result = curl_exec($ch);
			curl_close($ch);

			$fp = fopen($fileLocation, 'wb');
			fwrite($fp, $result);
			fclose($fp);
		}

		return $fileLocation;
	} else {
		$headers[] = 'Access-Control-Allow-Origin: *';
		$headers[] = 'X-HTTP-Method-Override: ' . $method;
		$headers[] = 'Accept: application/json';

		if (isLoggedIn())
			$headers[] = 'Authorization: Bearer ' . ($newToken ? $newToken : sessionData('token'));

		switch ($method) {
			case 'POST':
				// initialize post
				if ($data) {
					$data['qG'][0] = []; // BETWEEN
					$data['qG'][1] = []; // LIKEOR
					$data['qG'][2] = []; // LIKEAND
					$data['qG'][3] = []; // EXACTOR

					// initialize additional parameters
					if (isset($data['additional']) && !empty($data['additional'])) {
						foreach ($data['additional'] as $paramKey => $paramValue) {
							$data['qG'][3]['queryMethod'] = 'EXACTOR';
							$data['qG'][3]['queryParams'] = [
								[
									"column" => $paramKey,
									"value" => $paramValue
								]
							];
						}
						unset($data['additional']);
					}

					// initialize order parameters
					if (isset($data['order']) && !empty($data['order'])) {
						$order = explode('$', $data['order']);
						$data['sortingParams'][] = ['column' => $order[0],"value" => $order[1]];
						unset($data['order']);
					}

					// initialize datatable search
					if (isset($data['search']) && !empty($data['search'])) {
						$searchParams = [];
						foreach ($data['search'] as $paramKey => $paramValue) {
							if ($paramKey != 'method') {
								$searchParams[] = ["column" => $paramKey,"value" => $paramValue];
							}
						}

						if (!empty($searchParams)) {
							$data['qG'][1]['queryMethod'] = 'LIKEOR';
							$data['qG'][1]['queryParams'] = $searchParams;
						}

						unset($data['search']);
					}

					if (isset($data['directFilters']) && !empty($data['directFilters'])) {
						foreach ($data['directFilters'] as $key => $value) {
							if ($key == 'BETWEEN') {
								$data['qG'][0]['queryMethod'] = 'BETWEEN';
								$data['qG'][0]['queryParams'] = $value;
							} else if ($key == 'LIKEAND') {
								$data['qG'][1]['queryMethod'] = 'LIKEAND';
								$data['qG'][1]['queryParams'] = $value;
							} else if ($key == 'EXACTOR') {
								$data['qG'][2]['queryMethod'] = 'EXACTOR';
								$data['qG'][2]['queryParams'] = $value;
							}
						}
						unset($data['directFilters']);
					}
				}

				if (!empty($data['qG'])) {
					$data['queryGroups'] = [];
					foreach ($data['qG'] as $key => $value) {
						if (!empty($value)) {
							$data['queryGroups'][] = $value;
						}
					}
					unset($data['qG']);
				}

				if (!empty($data['queryGroups']))
					$data['queryGroupMethod'] = 'AND';
				else
					unset($data['queryGroups']);

				// page limitation required page number
				// if (isset($data['limit']) && !empty($data['limit']))
				//     $data['page'] = 1;

				if (isset($additionalOptions['withFile']) && $additionalOptions['withFile']) {
					$headers[] = 'Content-Type: multipart/form-data';

					$data = [
						'files' => new \CURLFile($data['files'], $data['type'], $data['filename'].'.'.explode('/', $data['type'])[1])
					];
				} else {
					$headers[] = 'Content-Type: application/json; charset=utf-8';
					$data = json_encode($data);
				}

				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				break;

			case 'GET':
				// initialize parameters
				if ($parameters) {
					$url .= '?';

					if (isset($additionalOptions['query']) && $additionalOptions['query']) {
						foreach ($parameters as $paramKey => $paramValue) {
							$url .= $paramKey . '=' . $paramValue;
						}
					} else {
						$url .= 'query=';

						// Pagination
						if (isset($parameters['page']) && isset($parameters['limit'])) {
							$url .= 'page=' . $parameters['page'] . '??' . 'limit=' . $parameters['limit'] . '??';
						}
					}
				}
				break;

			default:
				break;
		}

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		$result = json_decode($result, TRUE);
		$result['httpStatus'] = $http_status;

		if ($http_status == 401 && isLoggedIn()) {
			$newToken = refreshTokenifTokenExpired($baseAPIURL, $apiURI, $method, $rawParameters, $rawData, $http_status);
			return runAPI($apiURI, $method, $rawParameters, $rawData, NULL, $newToken);
		} else {
			return $result;
		}
	}
}

function downloadFile($apiURI, $method, $parameters = NULL, $data = NULL, $additionalOptions = NULL, $newToken = NULL) {

}

/**
 * @param $url
 * @param null $apiURI
 * @param null $method
 * @param null $parameters
 * @param null $data
 * @param null $previousHttpStatus
 * @return mixed
 */
function refreshTokenifTokenExpired($url, $apiURI = NULL, $method = NULL, $parameters = NULL, $data = NULL, $previousHttpStatus = NULL) {
    $refreshUrl = $url . 'user/reftok';
    
    $refreshTokenData = [
        'token'     => (sessionData('token') ? sessionData('token') : sessionData('token')),
        'refToken'  => (sessionData('refreshToken') ? sessionData('refreshToken') : sessionData('refreshToken'))
	];
    $refreshTokenData = json_encode($refreshTokenData);

    $headers[] = 'Access-Control-Allow-Origin: *';
    $headers[] = 'X-HTTP-Method-Override: POST';
    $headers[] = 'Accept: application/json';
    $headers[] = 'Content-Type: application/json; charset=utf-8';
    $headers[] = 'Authorization: Bearer ' . sessionData('token');

    $ref = curl_init($refreshUrl);
    curl_setopt($ref, CURLOPT_URL, $refreshUrl);
    curl_setopt($ref, CURLOPT_POST, true);
    curl_setopt($ref, CURLOPT_POSTFIELDS, $refreshTokenData);
    curl_setopt($ref, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ref, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ref, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ref, CURLOPT_RETURNTRANSFER, true);

    $resultRef = curl_exec($ref);
    curl_close($ref);

    $resultRef = json_decode($resultRef, TRUE);

    // Set new token & refreshToken cookie
    setUserCookie($resultRef, TRUE, FALSE);

    return $resultRef['token'];
}

/**
 * @param $selectedColumns
 * @param int $totalUnusedColumn
 * @return array
 */
function initParameters($selectedColumns, $totalUnusedColumn = 2) {
    $parameters = [];

    // init pagination and limitation
    $start = inputPost('start');
    $limit = inputPost('length');

    if ($start == 0) {
        $page = 1;
    } else {
        $page = ($start / $limit) + 1;
    }
    $parameters['page'] = $page;
    $parameters['limit'] = $limit;

    $usedColumns = [];

    // init quick search
    $search = inputPost('search')['value'];
    if ($search != '') {
		if (inputGet('catCode') && strstr($search, inputGet('catCode'))) {
			$parameters['directFilters']['EXACTOR'][] = [
				"column" => $selectedColumns[0]['column'],
				"value" => str_replace(inputGet('catCode').'-', '', $search)
			];
		} else {
			$parameters['search'] = [];
			foreach ($selectedColumns as $key => $value) {
				$parameters['search'][(isset(explode('.', $value['column'])[1]) ? explode('.', $value['column'])[1] : $value['column'])] = $search;
			}
		}
	}

    // init column order
    $order = inputPost('order')[0];
    if ($order['column'] != '') {
        foreach ($selectedColumns as $key => $value) {
            $filterColumns[] = (isset($value['replaceOrder']) ? $value['replaceOrder'] : $value['column']);
        }

        $selCol = array_values($filterColumns);
        $orderColumn = $selCol[($order['column']-$totalUnusedColumn)];
        $parameters['order'] = $orderColumn . '$' . $order['dir'];
    }

    return $parameters;
}

/**
 * @param null $selectedColumns
 * @param $result
 * @param null $deletedKey
 * @return array
 */
function generateData($selectedColumns = NULL, $result, $deletedKey = NULL) {
    $data = [];

    foreach ($selectedColumns as $key => $value) {
        if (is_array($value)) {
            if (!$value['hide']) {
                $usedColumns[$value['column']] = $value['alias'];
            }
        }
    }

    foreach ($result['data'] as $key => $value) {
        $collectedData[$key] = [];

        foreach ($value as $valKey => $valValue) {
            if (in_array($valKey, array_keys($usedColumns)))
                $collectedData[$key][$valKey] = $valValue;

            if (is_array($valValue)) {
                foreach ($valValue as $valValKey => $valValValue) {
                    if (in_array(strval($valKey.'.'.$valValKey), array_keys($usedColumns))) {
                        $collectedData[$key][$valKey.'.'.$valValKey] = $valValValue;
                    }
                }
            }
        }
    }

    if (isset($collectedData) && $collectedData) {
        $no = 0;
        foreach ($collectedData as $dataKey => $dataValue) {
            $data[$dataKey] = [];
            foreach ($usedColumns as $ucKey => $ucValue) {
                if (strstr($ucKey, 'dummy')) {
                    $column = explode('.', $ucKey)[1];
                    if ($column == 'containerAvailabilityStatus') {
                        $parameters[$no]['directFilters']['EXACTOR'][] = ["column" => "catCode", "value" => env('C_SET')];
                        $parameters[$no]['directFilters']['EXACTOR'][] = ["column" => "assetParent","value" => $dataValue['idAsset']];
                        $containSet[$no] = runAPI('asset/query', 'POST', NULL, $parameters[$no])['data'];

                        $data[$no][] = !empty($containSet[$no]) ? 'Filled' : 'Available';
                    } else if ($column == 'pieceInstrumentSet') {
                        $parameters[$no]['directFilters']['EXACTOR'][] = ["column" => "idAsset", "value" => $dataValue['assetParent']];
                        $set[$no] = runAPI('asset/query', 'POST', NULL, $parameters[$no])['data'];

                        $data[$no][] = !empty($set[$no]) ? $set[$no][0]['catCode'].'-'.$set[$no][0]['idAsset'] . ' | ' . $set[$no][0]['assetName'] : '-';
                    } else if ($column == 'pieceInstrumentContainer') {
                        $setParameters[$no]['directFilters']['EXACTOR'][] = ["column" => "idAsset", "value" => $dataValue['assetParent']];
                        $set[$no] = runAPI('asset/query', 'POST', NULL, $setParameters[$no])['data'];

                        if (!empty($set[$no])) {
                            $containerParameters[$no] = [];
                            $containerParameters[$no]['directFilters']['EXACTOR'][] = ["column" => "idAsset", "value" => $set[$no][0]['assetParent']];
                            $container[$no] = runAPI('asset/query', 'POST', NULL, $containerParameters[$no])['data'];

                            $data[$no][] = !empty($container[$no]) ? $container[$no][0]['catCode'].'-'.$container[$no][0]['idAsset'].' | '.$container[$no][0]['assetName'] : '-';
                        } else
                            $data[$no][] = '-';
                    } else if ($column == 'setContainer') {
                        $setParameters[$no]['directFilters']['EXACTOR'][] = ["column" => "idAsset", "value" => $dataValue['idAsset']];
                        $set[$no] = runAPI('asset/query', 'POST', NULL, $setParameters[$no])['data'];

                        if (!empty($set[$no])) {
                            $containerParameters[$no]['directFilters']['EXACTOR'][] = ["column" => "idAsset", "value" => $set[$no][0]['assetParent']];
                            $container[$no] = runAPI('asset/query', 'POST', NULL, $containerParameters[$no])['data'];

                            $data[$no][] = !empty($container[$no]) ? $container[$no][0]['catCode'].'-'.$container[$no][0]['idAsset'].' | '.$container[$no][0]['assetName'] : '-';
                        } else
                            $data[$no][] = '-';
                    } else if ($column == 'roomBuilding') {
						$parameters[$no]['directFilters']['EXACTOR'][] = ['column' => 'idLocation', 'value' => $dataValue['idLocation']];
						$parentLoc[$no] = runAPI('location/query', 'POST', NULL, $parameters[$no])['data'][0]['parentLoc'];

						if (!empty($parentLoc[$no])) {
							$buildingParameters[$no]['directFilters']['EXACTOR'][] = ["column" => "idLocation", "value" => $parentLoc[$no]];
							$building[$no] = runAPI('location/query', 'POST', NULL, $buildingParameters[$no])['data'][0];

							$data[$no][] = !empty($building[$no]) ? env('L_BUILDING') . '-' . $building[$no]['idLocation'] . ' | ' . $building[$no]['locName'] : '-';
						} else
							$data[$no][] = '-';
					} else if ($column == 'setBuilding') {
						$locParameters[$no]['directFilters']['EXACTOR'][] = ['column' => 'idAsset', 'value' => $dataValue['idAsset']];
						$loc[$no] = runAPI('asset/query', 'POST', NULL, $locParameters[$no])['data'][0]['propAdmin']['idLocation'];

						if (!empty($loc[$no]) && $loc[$no]) {
							$roomParameters[$no]['directFilters']['EXACTOR'][] = ['column' => 'idLocation', 'value' => $loc[$no]];
							$roomDetail[$no] = runAPI('location/query', 'POST', NULL, $roomParameters[$no])['data'][0];

							$buildingParameters[$no]['directFilters']['EXACTOR'][] = ['column' => 'idLocation', 'value' => $roomDetail[$no]['parentLoc']];
							$buildingDetail[$no] = runAPI('location/query', 'POST', NULL, $buildingParameters[$no])['data'][0];

							$data[$no][] = env('L_BUILDING') . '-' . $buildingDetail[$no]['idLocation'] . ' | ' . $buildingDetail[$no]['locName'];
						} else
							$data[$no][] = '-';
					} else if ($column == 'setRoom') {
						$locParameters[$no]['directFilters']['EXACTOR'][] = ['column' => 'idAsset', 'value' => $dataValue['idAsset']];
						$loc[$no] = runAPI('asset/query', 'POST', NULL, $locParameters[$no])['data'][0]['propAdmin']['idLocation'];

						if (!empty($loc[$no]) && $loc[$no]) {
							$roomParameters[$no]['directFilters']['EXACTOR'][] = ['column' => 'idLocation', 'value' => $loc[$no]];
							$roomDetail[$no] = runAPI('location/query', 'POST', NULL, $roomParameters[$no])['data'][0];

							$data[$no][] = env('L_ROOM') . '-' . $roomDetail[$no]['idLocation'] . ' | ' . $roomDetail[$no]['locName'];
						} else
							$data[$no][] = '-';
					}
                } else
                    $data[$no][] = $dataValue[$ucKey];
            }
            $no++;
        }
    }

    $finalData = [
        'draw'              => 0,
        'recordsTotal'      => $result['dataCount'],
        'recordsFiltered'   => $result['dataCount'],
        'data'              => $data,
        'allID'             => (isset($result['allID']) ? $result['allID'] : '')
	];

    jsonE($finalData);
}
