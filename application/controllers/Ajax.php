<?php

class Ajax extends INS_Controller {
	
	public function __construct() {
		parent::__construct();
	}

	public function instrumentCategories() {
		$response = runAPI('asset/list', 'GET');

		$collectedCategory = array();
		foreach ($response['data'] as $key => $value) {
			if (!in_array($value['propInstrument']['insCategory'], $collectedCategory) && $value['propInstrument']['insCategory']) {
				$collectedCategory[] = $value['propInstrument']['insCategory'];
			}
		}

		sort($collectedCategory);
		echo json_encode($collectedCategory);
	}

	public function merk() {
		$response = runAPI('asset/masterlist', 'GET');

		$collectedMerk = array();
		foreach ($response['data'] as $key => $value) {
			if (!in_array($value['merk'], $collectedMerk) && $value['merk']) {
				$collectedMerk[] = $value['merk'];
			}
		}

		sort($collectedMerk);
		echo json_encode($collectedMerk);
	}

	public function catalogue() {
		$catCode = uriSegment(3);

		$parameters['directFilters']['EXACTOR'][] = [
			"column" => 'catCode',
			"value" => $catCode
		];
		$parameters['limit'] = 10;
		$response = runAPI('asset/masterquery', 'POST', NULL, $parameters);

		$collectedCatalogue = array();
		foreach ($response['data'] as $key => $value) {
			if (!in_array($value['idAssetMaster'], $collectedCatalogue) && $value['idAssetMaster']) {
				$collectedCatalogue[$value['idAssetMaster']] = $value['productCode'] . ' | ' . $value['assetMasterName'] . ', ' . $value['merk'];
			}
		}

		sort($collectedCatalogue);
		echo json_encode($collectedCatalogue);
	}

}
