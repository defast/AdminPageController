<?php
if (!defined('_PS_VERSION_'))
    exit;

class pwmodule extends Module
{
    public function __construct()
    {
        $this->name = strtolower(get_class());
        $this->tab = 'other';
        $this->version = 0.1;
        $this->author = 'PrestaWeb.ru';
        $this->need_instance = 0;
        $this->bootstrap = true;
		//start_controller
        $this->controllers = array('page');
        //end_controller
		
        parent::__construct();

        $this->displayName = $this->l("Модуль №1");
        $this->description = $this->l("Модуль №1");
        
        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);
    }

    public function install()
    {

        if (!parent::install() or !$this->registerHook(Array('displayTop')) or !$this->installTab()) return false;

        return true;
    } 
	public function uninstall()
    {
		
        if(!parent::uninstall() && !$this->uninstallTab()) return false;
		
		return true;
    }
	public function installTab()
	{
		$tab = new Tab();
		$tab->class_name = 'AdminPage';
		$tab->id_parent  = (int)Tab::getIdFromClassName('AdminTools'); 
		$tab->module = $this->name; 
		foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = 'Модуль №1';
        }
		if (!$tab->add()) 
			return false;
		return true;
	}
	public function unisntallTab()
	{
		$tab = new Tab(Tab::getIdFromClassName('AdminPage')); 
		if (!$tab->delete())
			return false;
		return true;
	}
    
    public function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Настройки'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'radio',
                        'label' => $this->l('Случайная настройка 1'),
                        'name' => 'PWMODULE_OPTION1',
                        'hint' => $this->l('Select which category is displayed in the block. The current category is the one the visitor is currently browsing.'),
                        'values' => array(
                            array(
                                'id' => 'home',
                                'value' => 0,
                                'label' => $this->l('Вариант 1')
                            ),
                            array(
                                'id' => 'current',
                                'value' => 1,
                                'label' => $this->l('Вариант 2')
                            ),
                            array(
                                'id' => 'parent',
                                'value' => 2,
                                'label' => $this->l('Вариант 3')
                            )
                        )
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Настройка 2'),
                        'name' => 'PWMODULE_OPTION2',
                        'desc' => $this->l('Подсказка'),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Настройка 3'),
                        'name' => 'PWMODULE_OPTION3',
                        'desc' => $this->l('Подсказка'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    )
                ),
                'submit' => array(
                    'title' => $this->l('Сохранить'),
                )
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitPWMODULE';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($fields_form));
    }

    public function getConfigFieldsValues()
    {
        return array(
            'PWMODULE_OPTION1' => Tools::getValue('PWMODULE_OPTION1', Configuration::get('PWMODULE_OPTION1')),
            'PWMODULE_OPTION2' => Tools::getValue('PWMODULE_OPTION2', Configuration::get('PWMODULE_OPTION2')),
            'PWMODULE_OPTION3' => Tools::getValue('PWMODULE_OPTION3', Configuration::get('PWMODULE_OPTION3')),
        );
    }
    public function getContent()
    {
        $output = '';
        if (Tools::isSubmit('submitPWMODULE'))
        {
            $maxDepth = (int)(Tools::getValue('PWMODULE_OPTION1'));
            if ($maxDepth < 0)
                $output .= $this->displayError($this->l('Опция не прошла проверку, убирите её из кода если не нужна'));
            else{
                Configuration::updateValue('PWMODULE_OPTION1', Tools::getValue('PWMODULE_OPTION1'));
                Configuration::updateValue('PWMODULE_OPTION2', Tools::getValue('PWMODULE_OPTION2'));
                Configuration::updateValue('PWMODULE_OPTION3', Tools::getValue('PWMODULE_OPTION3'));
                Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&conf=6');
            }
        }
        return $output.$this->renderForm();
    }
    //end_helper

    

    


	public function hookdisplayTop($params){
		
		$this->smarty->assign("url", $this->_path);
		
		return $this->display(__FILE__, 'pwmodule.tpl');
	}


}


