{**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 *}

<div class="homepagecategories col-md-12 pl-0 pr-0 mt-3">
    <div class="homepagecategories-wrapper">
        <p class="d-none d-md-block h3">{l s='Ons assortiment'}</p>
        <div class="d-flex flex-wrap">
            {foreach from=$categories item=category}

                <div class="col-homecategories col-xs-6 col-sm-4 col-md-2 pl-0">
                    <div class="category-block-card mt-2">
                        <a href="{$category.link}" class="d-block text-center text-dark">
                            <img src="{$category.image}" alt="{$category.name|escape:'html'}">
                            <p class="text-dark px-1">{$category.name}</p>
                        </a>
                    </div>
                </div>
            {/foreach}
        </div>
    </div>
    {if $show_more_button.display}
        <div class="row">
            <div class="col-md-12 mb-2">
                <a href="{$show_more_button.link}" class="btn btn-primary float-right">
                    {l s='See all categories' mod='Shop.Catalog.Default'}
                </a>
            </div>
        </div>
    {/if}
</div>
