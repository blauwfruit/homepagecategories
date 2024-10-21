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

class HomepageCategoriesClass extends ObjectModel
{
    public $id;
    public $id_category;
    public $date_add;

    public static $definition = array(
        'table' => 'homepagecategories',
        'primary' => 'id_homepagecategories',
        'fields' => array(
            'id_category' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt',
                'required' => true
            ),
            'date_add' => array(
                'type' => self::TYPE_DATE,
                'validate' => 'isDate',
                'required' => true
            ),
        ),
    );

    public static function searchCategories($term)
    {
        $sql = new DbQuery();
        $sql->select('cl.id_category, cl.name');
        $sql->from('category_lang', 'cl');
        $sql->leftJoin('homepagecategories', 'hbi', 'cl.id_category = hbi.id_category');
        $sql->leftJoin('category_shop', 'cs', 'cs.id_category = cl.id_category');
        $sql->where('cl.name LIKE \'%' . pSQL($term) . '%\'');
        $sql->where('hbi.id_category IS NULL');
        $sql->where('cs.id_shop = ' . (int)Context::getContext()->shop->id);
        return Db::getInstance()->executeS($sql);
    }


    public static function getAllCategories()
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('homepagecategories', 'hpc');
        $sql->leftJoin('category_shop', 'cs', 'cs.id_category = hpc.id_category');
        $sql->where('cs.id_shop = ' . (int)Context::getContext()->shop->id);

        $result = Db::getInstance()->executeS($sql);

        var_dump($result);

        return $result;
    }

}