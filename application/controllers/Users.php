<?php

class Users extends INS_Controller {
    
    public function __construct() {
		parent::__construct();
		$this->totalUnusedColumn	= 2;
		$this->selectedColumns      = [
			['column' => 'idUser', 'alias' => 'ID', 'width' => 1, 'searchable' => FALSE, 'hide' => FALSE],
			['column' => 'userName', 'alias' => 'USERNAME', 'width' => 20, 'searchable' => TRUE, 'hide' => FALSE],
			['column' => 'userFullName', 'alias' => 'NAME', 'width' => 20, 'searchable' => TRUE, 'hide' => FALSE],
			['column' => 'userMail', 'alias' => 'EMAIL ADDRESS', 'width' => 20, 'searchable' => TRUE, 'hide' => FALSE],
			['column' => 'userPhone', 'alias' => 'PHONE NUMBER', 'width' => 20, 'searchable' => TRUE, 'hide' => FALSE],
		];
	}

	public function index() {
		$data['customDatatable']    = TRUE;
		$data['columnDefinition']   = [$this->selectedColumns, $this->totalUnusedColumn];

		render('module', $data);
	}

	public function data() {
		$url = 'GrpUser/query';

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
		} else {
			$parameters = initParameters($this->selectedColumns, $this->totalUnusedColumn);
		}

		// filters
		$filters = inputPost('filters');
		if (is_string($filters))
			parse_str($filters, $filters);

		$parameters['directFilters'] = [];

		if (isset($filters) && !empty($filters)) {
			// simple filters
			if (isset($filters['searchKeyword1']) || isset($filters['searchKeyword2'])) {
				// check first search field
				if ($filters['searchKeyword1'] != '') {
					if ($filters['searchField1'] == 'all') {
						foreach ($this->selectedColumns as $allColKey => $selectedColumn) {
							$parameters['search'][$selectedColumn['column']] = $filters['searchKeyword1'];
						}
					} else {
						$parameters['directFilters']['LIKEAND'][] = ["column" => $filters['searchField1'], "value" => $filters['searchKeyword1']];
					}
				}
				// check second search field
				if ($filters['searchKeyword2'] != '') {
					if ($filters['searchField2'] == 'all') {
						foreach ($this->selectedColumns as $allColKey => $selectedColumn) {
							$parameters['search'][$selectedColumn['column']] = $filters['searchKeyword2'];
						}
					} else
						$parameters['directFilters']['LIKEAND'][] = ["column" => $filters['searchField2'], "value" => $filters['searchKeyword2']];
				}
			} else {
				// advanced filters
				foreach ($filters as $paramKey => $paramValue) {
					if (isset($paramValue['value']) && $paramValue['value'] != '') {
						if ($paramValue['name'] == 'idUser')
							$parameters['directFilters']['LIKEAND'][] = ["column" => $paramValue['name'], "value" => $paramValue['value']];
						else
							$parameters['directFilters']['LIKEAND'][] = ["column" => $paramValue['name'], "value" => $paramValue['value']];
					}
				}
			}
		}

		if (isset($parameters['search']) && $parameters['search'] != '') $url = 'GrpUser/query';

		$result = runAPI($url, 'POST', NULL, $parameters);

		unset($parameters['limit'], $parameters['page']);

		generateData($this->selectedColumns, $result);
	}

	public function getSingleData() {
		$pk = inputGet('id');

		$parameters = [];
		$parameters['directFilters']['EXACTOR'][] = ["column" => 'idUser', "value" => $pk];

		$result = runAPI('GrpUser/query', 'POST', NULL, $parameters);
		jsonE($result['data']);
	}

	public function form() {
		$pk = inputPost('user')[1]['idUser'];

		$importMode = FALSE;
		$data = inputPost();

		if (isset($data['user'])) {
			// insert from form
			// transform data
			if ($data['user'][1]['idUser']) {
				$data = $data['user'][1];
				$collectedData = [
					'idUser' 		=> ($pk ? $data['idUser'] : 0),
					'userName' 		=> $data['userName'],
					'userFullName' 	=> $data['userFullName'],
					'userMail' 		=> $data['userMail'],
					'userPhone' 	=> $data['userPhone'],
				];
				(object)$collectedData;
			} else {
				$collectedData = [];
				$no = 0;
				foreach ($data['user'] as $key => $value) {
					$collectedData[$no] = [
						'idUser' 		=> ($pk ? $value['idUser'] : 0),
						'userName' 		=> $value['userName'],
						'userFullName' 	=> $value['userFullName'],
						'userMail' 		=> $value['userMail'],
						'userPhone' 	=> $value['userPhone'],
					];
					$no++;
				}
			}
		} else {
			//
		}

		// Run API
		if ($importMode) {
			$response['insertResponse'] = runAPI('GrpUser/update', 'POST', NULL, $inserted);
			$response['updateResponse'] = runAPI('GrpUser/update', 'POST', NULL, $updated);
		} else {
			$url = $pk ? 'update' : 'BulkInsert';
			$response = runAPI('GrpUser/'.$url, 'POST', NULL, $collectedData);
		}

		jsonE($response); die();
	}

	public function delete() {
		$rowsSelected = inputPost('ids');

		$response = runAPI('GrpUser/delete?idUser='.$rowsSelected[0], 'POST');
		jsonE($response);
	}

}
