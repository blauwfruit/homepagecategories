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

    public function hookDisplayHome()
    {
        $fetchedCategories = HomepageCategoriesClass::getAllCategories();
        $categories = [];

        foreach ($fetchedCategories as $categoryData) {
            $category = new Category($categoryData['id_category'], $this->context->language->id, $this->context->shop->id);

            $categoryImageUrl = $this->context->link->getCatImageLink($category->link_rewrite, $category->id);
            $categoryLink = $this->context->link->getCategoryLink($category->id, $category->link_rewrite);

            $categories[] = [
                'name' => $category->name,
                'image' => $categoryImageUrl,
                'link' => $categoryLink
            ];
        }

        var_dump($categories);


        if ($categories) {
            $this->context->smarty->assign([
                'categories' => $categories
            ]);

            return $this->context->smarty->fetch('module:homepagecategories/views/templates/hook/categories.tpl');
        }

        return '';
    }

}
