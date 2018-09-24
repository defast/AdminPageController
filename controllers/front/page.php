<?php

if (!defined('_PS_VERSION_'))
    exit;

class pwmodulePageModuleFrontController extends ModuleFrontController
{
    public $module;

	
    public function initContent()
    {
        
        parent::initContent();
        $this->setTemplate('module:pwmodule/views/templates/front/page.tpl');
    }

    public function postProcess()
    {
        /**
         * Для POST запросов
         */
    }


}
