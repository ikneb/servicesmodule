{**
* 2016 WeeTeam
*
* @author    WeeTeam
* @copyright 2016 WeeTeam
* @license   http://www.gnu.org/philosophy/categories.html (Shareware)
*}

{if $virtual == 1}
    {if $version }
        {include file="$product_tab_for_6" }
    {else}
        {include file="$product_tab_for_7" }
    {/if}
{else}
<h3>Product must be virtual! Please create product and save it virtual.</h3>
{/if}
<div class="b-popup">
    <div class="b-popup-content">
        Saved successfully!
    </div>
</div>
