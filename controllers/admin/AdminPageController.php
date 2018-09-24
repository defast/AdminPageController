<?php


class AdminPageController extends ModuleAdminController
{
    public function __construct() {
        $this->name = 'AdminPageController'; 
        $this->version = '1.0.0';
        $this->bootstrap = true;
		$this->display = 'view';
        $this->className = 'AdminPageController';
        parent::__construct();
	}

	public function initContent()
	{
		$this->renderView();
		return parent::initContent();
	}

	public function renderView()
	{
		$this->base_tpl_view = 'view.tpl';
		return parent::renderView();
	} 
   
}
