<?php
class Page404Controller extends BaseController {
	private $action;
	public function __construct($module,$action) {
		parent::__construct($module,$action);
		$this->action = $action;
	}
	public function execute() {
		$this->smarty->display('404.html');
	}
}
