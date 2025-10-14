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

/**
 * Upgrade to 1.0.1
 * - Remove duplicate rows in homepagecategories, keeping the oldest entry
 */
function upgrade_module_1_0_1($module)
{
    $db = Db::getInstance();

    // Delete duplicates: keep the lowest id_homepagecategories for each (id_category, id_shop)
    $sql = 'DELETE h1 FROM `'.pSQL(_DB_PREFIX_).'homepagecategories` h1
            INNER JOIN `'.pSQL(_DB_PREFIX_).'homepagecategories` h2 
                ON h1.id_category = h2.id_category 
               AND h1.id_shop = h2.id_shop 
               AND h1.id_homepagecategories > h2.id_homepagecategories';

    return (bool) $db->execute($sql);
}


