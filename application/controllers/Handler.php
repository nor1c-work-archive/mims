<?php

class Handler extends INS_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function page_404() {
		echo "Page not found <a href='".site_url('')."'>Back to Application</a>";
	}

}
