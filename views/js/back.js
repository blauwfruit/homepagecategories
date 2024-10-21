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
*
* Don't forget to prefix your containers with your own identifier
* to avoid any conflicts with others containers.
*/

$(document).ready(function() {
    $("#object_name").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: ajaxSearchUrl,
                type: 'GET',
                dataType: 'json',
                data: {
                    term: request.term
                },
                success: function(data) {
                    // Remove duplicates based on id_category
                    var uniqueResults = [];
                    var ids = new Set();

                    data.forEach(function(item) {
                        if (!ids.has(item.id_category)) {
                            ids.add(item.id_category);
                            uniqueResults.push({
                                label: item.name,
                                value: item.name,
                                id: item.id_category
                            });
                        }
                    });
                    response(uniqueResults);
                },
                error: function(xhr, status, error) {
                    return response(status, error);
                }
            });
        },
        minLength: 2,
        select: function(event, ui) {
            $('#object_name').val(ui.item.label);
            $('#id_category').val(ui.item.id);
        }
    });
});