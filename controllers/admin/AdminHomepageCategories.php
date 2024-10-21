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

class AdminHomepageCategoriesController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'homepagecategories';
        $this->className = 'HomepageCategoriesClass';
        $this->context = Context::getContext();
        $this->identifier = 'id_homepagecategories';

        parent::__construct();

        $this->fields_list = [
            'id_homepagecategories' => [
                'title' => $this->l('ID'),
                'class' => 'fixed-width-xs'
            ],
            'id_category' => [
                'title' => $this->l('Category'),
                'callback' => 'getCategoryName',
            ],
            'date_add' => [
                'title' => $this->l('Date'),
            ],
        ];


        $this->bulk_actions = [
            'delete' => [
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
                'icon' => 'icon-trash'
            ],
        ];

        $this->actions = ['edit', 'delete'];
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);

        $this->addJS('https://code.jquery.com/ui/1.12.1/jquery-ui.min.js');
        $this->addCSS('https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');


        $this->addJS(_MODULE_DIR_ . $this->module->name . '/views/js/back.js');
    }

    public function renderForm()
    {
        $ajaxSearchUrl = $this->context->link->getAdminLink('AdminHomepageCategories') . '&ajax=1&action=searchObjects';

        Media::addJsDef([
            'ajaxSearchUrl' => $ajaxSearchUrl
        ]);

        $this->fields_form = [
            'legend' => [
                'title' => $this->l('Homepage Categories'),
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->l('Category'),
                    'name' => 'object_name',
                    'desc' => $this->l('Please enter a category name, please provide at least 2 characters.'),
                ],
                [
                    'type' => 'hidden',
                    'name' => 'id_category'
                ],
            ],
            'submit' => [
                'title' => $this->l('Save')
            ]
        ];

        return parent::renderForm();
    }

    public function postProcess()
    {
        parent::postProcess();
    }

    public function getCategoryName($categoryId)
    {
        $category = new Category($categoryId, $this->context->language->id);
        return $category->name;
    }

    public function ajaxProcessSearchObjects()
    {
        $term = Tools::getValue('term');
        if (!$term) {
            die(json_encode(['error' => 'No search term provided']));
        }

        try {
            $results = HomepageCategoriesClass::searchCategories($term);
            die(json_encode($results));
        } catch (Exception $e) {
            die(json_encode(['error' => $e->getMessage()]));
        }
    }

}
