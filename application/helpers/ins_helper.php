<?php

function render($page = '', $data = NULL, $other = FALSE) {

	$data = array_merge($data, initTitle());
	$data = array_merge($data, initWebTrack($data['pageTitle']));

    if (isLoggedIn()) {
        // store last visited page too cookie
        $lastVisitedPage = uriSegment(1).'/'.uriSegment(2).'/'.uriSegment(3);
        setSingleSession('lastVisitedPage', $lastVisitedPage);

        $data['module']     = uriSegment(1);
        $data['modulePath'] = 'pages/'.$data['module'];
        
        if (isset($data['columnDefinition'])) {
            foreach ($data['columnDefinition'] as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $columnKey => $keyValue) {
                        if (!$keyValue['hide']) {
                            $data['columns'][$keyValue['column']]               = $keyValue['alias'];
                            $data['width'][]                                    = $keyValue['width'];
                            $data['totalUnusedColumn']                          = $data['columnDefinition'][1];
                            if ($keyValue['searchable'])
                                $data['searchableColumns'][$keyValue['column']]     = $keyValue['alias'];
                        }
                    }
                }
            }
        }

        if ($other) {
            $data['page'] = 'pages/'.$page;
        } else {
            if ($page == 'module')
                $data['page'] = uriSegment(1);
            else if ($page && (strpos('pages/picker', $page) !== TRUE))
                return loadView($page, $data);
            else if ($page == 'tracking') {
                return loadView('pages/tracking/tracking', $data);
            } else if ($page) {
                if (strpos('/', $page) !== TRUE)
                    $data['page'] = 'pages/'.$page.'/'.$page;
            } 
            else
                $data['page'] = 'pages/dashboard';
        }
            
		return loadView('index', $data);
    } else {
        cookieDestroy();
        return loadView('auth/login');
    }
}

function initTitle() {
	$pageTitle = '';
	$mainPage = '';

	switch (uriSegment(1)) {
		case 'master':
				$mainPage = 'Katalog';
			break;
		case 'assets':
				$mainPage = 'Aset';
			break;
		case 'location':
				$mainPage = '';
			break;
	}

	switch (uriSegment(3)) {
		case env('C_PIECE'):
				$pageTitle = $mainPage . ' Instrument';
			break;
		case env('C_SET'):
				$pageTitle = $mainPage . ' Instrument Set/Kit';
			break;
		case env('C_CONTAINER'):
				$pageTitle = $mainPage . ' Container/Box';
			break;
		case env('URL_BUILDING'):
				$pageTitle = $mainPage . ' Building';
			break;
	}
	$data['pageTitle'] = $pageTitle;
	$data['title'] = ($pageTitle ? $pageTitle . ' | ' : '') .  env('APP_NAME') . ' v' . env('APP_VER');

	return $data;
}

function initWebTrack($pageTitle) {
	$data['webTrack'] = '<nav aria-label="breadcrumb" class="float-md-right float-left">
                            <ol class="breadcrumb mb-0 justify-content-end p-0 bg-white">
								<li class="breadcrumb-item"><a href="'.site_url().'">Home</a></li>';
	switch (uriSegment(1)) {
		case 'master':
				$data['webTrack'] .= '<li class="breadcrumb-item">Catalogue</li>';
			break;
		
		case 'assets':
				$data['webTrack'] .= '<li class="breadcrumb-item">Inventory</li>';
			break;

		case 'location':
				$data['webTrack'] .= '<li class="breadcrumb-item">Location</li>';
			break;
	}
	$data['webTrack'] .= 		'<li class="breadcrumb-item" style="font-weight:bold;color:#2cabe3;">'.$pageTitle.'</li>';
                                // <li class="breadcrumb-item active" aria-current="page">Material Icon</li>
    $data['webTrack'] .= 		'</ol>
							</nav>';
	
	return $data;
}

function isLoggedIn() {
    return sessionData('token') ? TRUE : FALSE;
}

function setUserCookie($result, $tokenOnly = FALSE, $needDecoded = FALSE) {
    if ($needDecoded) $result = json_decode($result, TRUE);

    $defaultLifeTime = env('COOKIE_DEFAULT_LIFETIME');
    $defaultPath = '/';

    if (!$tokenOnly) {
        // sessionDestroy();
        // user detail
        setSingleSession('idUser', $result['idUser']);
        setSingleSession('userName', $result['userName']);
        setSingleSession('userFullName', $result['userFullName']);
        setSingleSession('userMail', $result['userMail']);
    }
    
    // token    
    setSingleSession('token', $result['token']);
    setSingleSession('refreshToken', $result['refToken']);
}

function cookieDestroy($cookieKeys = NULL) {
    // if (!$cookieKeys) {
    //     $cookieKeys = array('userID', 'username', 'userFullName', 'userMail', 'token', 'refreshToken');
    // }

    // foreach ($cookieKeys as $cookieName) {
    //     delete_cookie($cookieName);
    // }
}

function convertDate($date, $toFormat, $fromFormat = null) {
    switch ($fromFormat) {
        case 'dd/mm/yyyy':
                $date = str_replace('/', '-', $date);
            break;
    }

    return $date ? date($toFormat, strtotime($date)) : '-';
}

function currentTime($format) {
    return date($format, time());
}

function needReauthenticate() {
    cookieDestroy();
    setFlashData('error', 'Session expire, please sign in again!');
    redirect();
}

function getAllID($data, $primaryKey) {
    $ids = array();
    foreach ($data['data'] as $key => $value) {
        $ids[] = $value[$primaryKey];
    }

    return $ids;
}

function rupiah($money) {
    return $money ? number_format($money, '0', '.', '.') : '-';
}

function zeroDefault($value) {
    return $value ? $value : 0;
}

function emptyDefault($value) {
    return $value ? $value : '';
}

function generateTableWidth($selectedColumns) {
    // initialize variable
    $collectedWidth = array();
    
    foreach ($selectedColumns as $key => $value) {
        if (is_array($value)) {
            foreach ($value as $columnKey => $keyValue) {
                if ($columnKey == 'width') { // select only width key
                    $collectedWidth[] = $keyValue; // push width to initialized variable
                }
            }
        }
    }

    return $collectedWidth; // return collected width
}

function instrumentCategory($category) {
    $categoryList = array('MIP' => 'Instrument', 'MIS' => 'Instrument Set', 'MIC' => 'Instrument Box', 'building' => 'Building', 'room' => 'Room');

    return $categoryList[$category];
}

function rekapExcel($html) {
    header("HTTP/1.1 200 OK");
    header("Pragma: public");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private", false);
    header('Content-Type: application/force-download');
    header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header("Content-Transfer-Encoding: binary");
    echo $html;
}

function rekapPdf($html = '', $filename = 'Rekap PDF', $paperSize = 'A4', $orientation = 'landscape') {
    loadLibrary('pdfgenerator');
    ci()->pdfgenerator->generatePdf($html, $paperSize, $orientation);

    $data['filename'] = $filename;

    echo json_encode($data);
}

function input($type = NULL, $title = '', $id = '', $inputName = '', $classes = '', $size = '4', $additionalHtml = '', $defaultOption = '-', $options = NULL, $selectedOption = NULL) {
    switch ($type) {
        case 'text':
                $html = '<div class="form-group row align-items-center mb-0">
                            <label class="col-3 control-label col-form-label">'.$title.'</label>
                            <div class="col-'.$size.' border-left pb-2 pt-2">
                                <input id="'.$id.'" type="text" class="form-control '.$classes.'" name="'.$inputName.'" '.$additionalHtml.'>
                            </div>
                        </div>';
            break;

        case 'textarea':
                $html = '<div class="form-group row align-items-center mb-0">
                            <label class="col-3 control-label col-form-label">'.$title.'</label>
                            <div class="col-'.$size.' border-left pb-2 pt-2">
                                <textarea id="'.$id.'" class="form-control '.$classes.'" rows="5" name="'.$inputName.'"></textarea>
                            </div>
                        </div>';
            break;

        case 'select_only':
                $html = '<select id="'.$id.'" name="'.$inputName.'" class="form-control '.$classes.' " '.$additionalHtml.'>
                            '.($defaultOption != 'firstOption' ? '<option value="" selected readonly disabled>'.$defaultOption.'</option>' : '');
                            foreach ($options as $key => $value) {
                                if (is_string($key))
                                    $html .= '<option value="'.$key.'" ' . ($selectedOption && $selectedOption == $key ? 'selected' : '') . ' >'.$value.'</option>';
                                else
                                    $html .= '<option value="'.$value.'" ' . ($selectedOption && $selectedOption == $value ? 'selected' : '') . ' >'.$value.'</option>';
                            }
                $html .= '</select>';
            break;

		case 'select2_automatic':
			$html = '<div class="form-group row align-items-center mb-0">
                            <label class="col-3 control-label col-form-label">'.$title.'</label>
                            <div class="col-'.$size.' border-left pb-2 pt-2">
                                <select id="'.$id.'" name="'.$inputName.'" class="form-control '.$classes.'" '.$additionalHtml.'></select>
                            </div>
                        </div>';
			break;

		case 'select2':
			$html = '<div class="form-group row align-items-center mb-0">
                            <label class="col-3 control-label col-form-label">'.$title.'</label>
                            <div class="col-'.$size.' border-left pb-2 pt-2">
                                <select id="'.$id.'" name="'.$inputName.'" class="form-control '.$classes.' " '.$additionalHtml.'>';
			if ($defaultOption) {
				$html .= '<option value="" selected readonly disabled>'.$defaultOption.'</option>';
			}
			if ($options) {
				foreach ($options as $key => $value) {
					$html .= '<option value="'.$value.'">'.$value.'</option>';
				}
			}
			$html .=        '</select>
                            </div>
                        </div>';
			break;
        
        default:
                $html = '';
            break;
    }

    echo $html;
}

function table($type = '', $headers = NULL, $tbodyID = '', $classes = '') {
    switch ($type) {
        case 'blank_tbody':
                $html = '<table class="table '.$classes.'">
                        <thead>
                            <tr>';
                            foreach ($headers as $key => $value) {
                                $html .= '<th style="'.$value['style'].'" id="'.$value['id'].'">'.$value['title'].'</th>';
                            }
                $html .=        '</tr>
                        </thead>
                        <tbody id="'.$tbodyID.'"></tbody>
                    </table>';
            break;
        
        default:
                $html = '';
            break;
    }

    echo $html;
}

function monthToInt($monthName) {
    $monthList = array(
        'Januari' => '01',
        'Februari' => '02',
        'Maret' => '03',
        'April' => '04',
        'Mei' => '05',
        'Juni' => '06',
        'Juli' => '07',
        'Agustus' => '08',
        'September' => '09',
        'Oktober' => '10',
        'November' => '11',
        'Desember' => '12'
    );
    
    return $monthList[$monthName];
}

function intToMonth($int) {
    $monthList = array(
        '01' => 'Januari',
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember'
    );

    return $monthList[$int];
}
