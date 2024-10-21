<?php
/**
* 2007-2024 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2024 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class HomepageCategories extends Module
{
    public function __construct()
    {
        require_once _PS_MODULE_DIR_ . 'homepagecategories/classes/HomepageCategoriesClass.php';

        $this->name = 'homepagecategories';
        $this->tab = 'content_management';
        $this->version = '1.0.0';
        $this->author = 'blauwfruit';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Home Page Categories');
        $this->description = $this->l('Introduction section for the home page');

        $this->confirmUninstall = $this->l('Do you want to uninstall?');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        include(dirname(__FILE__).'/sql/install.php');

        return parent::install() &&
            $this->installTab() &&
            $this->registerHook('displayHome');
    }

    public function uninstall()
    {
        include(dirname(__FILE__).'/sql/uninstall.php');

        return parent::uninstall() &&
            $this->uninstallTab() &&
            $this->unregisterHook('displayHome');
    }

    public function installTab()
    {
        $tab = new Tab();
        $tab->class_name = 'AdminHomepageCategories';
        $tab->module = $this->name;
        $tab->id_parent = (int) Tab::getIdFromClassName('AdminParentThemes');
        $tab->name = array();

        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = 'Homepage Categories';
        }

        return $tab->add();
    }

    public function uninstallTab()
    {
        $id_tab = (int) Tab::getIdFromClassName('AdminHomepageCategories');
        $tab = new Tab($id_tab);

        return $tab->delete();
    }

    public function getContent()
    {
        $output = '';

        // Handle form submission
        if (Tools::isSubmit('submit_'.$this->name)) {
            $show_button = Tools::getValue('HOMEPAGECATEGORIES_DISPLAY_SHOW_MORE_BUTTON');
            Configuration::updateValue('HOMEPAGECATEGORIES_DISPLAY_SHOW_MORE_BUTTON', $show_button);
            $output .= $this->displayConfirmation($this->trans('Settings updated', array(), 'Admin.Global'));
        }

        // Render the configuration form
        return $output . $this->renderForm();
    }

    public function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->trans('Settings', array(), 'Admin.Global'),
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->trans('Enable "Show more" button', array(), 'Admin.Global'),
                        'name' => 'HOMEPAGECATEGORIES_DISPLAY_SHOW_MORE_BUTTON',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->trans('Enabled', array(), 'Admin.Global'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->trans('Disabled', array(), 'Admin.Global'),
                            ),
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->trans('Save', array(), 'Admin.Actions'),
                ),
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = (int) Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submit_' . $this->name;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => array(
                'HOMEPAGECATEGORIES_DISPLAY_SHOW_MORE_BUTTON' => Tools::getValue('HOMEPAGECATEGORIES_DISPLAY_SHOW_MORE_BUTTON', Configuration::get('HOMEPAGECATEGORIES_DISPLAY_SHOW_MORE_BUTTON')),
            ),
        );

        return $helper->generateForm(array($fields_form));
    }


    public function hookDisplayHome()
    {
        $this->context->controller->addCSS($this->_path.'views/css/front.css');

        $fetchedCategories = HomepageCategoriesClass::getAllCategories();
        $categories = [];

        foreach ($fetchedCategories as $categoryData) {
            $category = new Category($categoryData['id_category'], $this->context->language->id);

            $categoryImageUrl = $this->context->link->getCatImageLink($category->link_rewrite, $category->id);
            $categoryLink = $this->context->link->getCategoryLink($category->id, $category->link_rewrite);

            $categories[] = [
                'name' => $category->name,
                'image' => $categoryImageUrl,
                'link' => $categoryLink
            ];
        }

        if (is_array($categories) && $categories <= 0) {
            return;
        }

        $this->context->smarty->assign([
            'categories' => $categories,
            'show_more_button' => [
                'display' => (bool) Configuration::get('HOMEPAGECATEGORIES_DISPLAY_SHOW_MORE_BUTTON'),
                'link' => $this->context->link->getCategoryLink($this->context->shop->id_category),
            ]
        ]);

        return $this->context->smarty->fetch('module:homepagecategories/views/templates/hook/categories.tpl');
    }

}
