<?php
/**
 * @version     0.2.0
 * @package     com_wbty_shop
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Webity <info@makethewebwork.com> - http://www.makethewebwork.com
 */

// no direct access
defined('_JEXEC') or die;
?>

<ul class="itemlist">
            
	
					<li><?php echo JText::_('COM_WBTY_SHOP_FORM_LBL_PRODUCTS_IMAGE'); ?>: <?php echo $this->item->image; ?></li>
					<li><?php echo JText::_('COM_WBTY_SHOP_FORM_LBL_PRODUCTS_NAME'); ?>: <?php echo $this->item->name; ?></li>
					<li><?php echo JText::_('COM_WBTY_SHOP_FORM_LBL_PRODUCTS_DESCRIPTION'); ?>: <?php echo $this->item->description; ?></li>
					<li><?php echo JText::_('COM_WBTY_SHOP_FORM_LBL_PRODUCTS_CATEGORY'); ?>: <?php echo $this->item->category; ?></li>
					<li><?php echo JText::_('COM_WBTY_SHOP_FORM_LBL_PRODUCTS_PRICING_SET'); ?>: <?php echo $this->item->pricing_set; ?></li>
					<li><?php echo JText::_('COM_WBTY_SHOP_FORM_LBL_PRODUCTS_SLIDE_TYPE'); ?>: <?php echo $this->item->slide_type; ?></li>
					<li><?php echo JText::_('COM_WBTY_SHOP_FORM_LBL_PRODUCTS_MENU_LINK'); ?>: <?php echo $this->item->menu_link; ?></li>
					<li><?php echo JText::_('COM_WBTY_SHOP_FORM_LBL_PRODUCTS_CAPTION'); ?>: <?php echo $this->item->caption; ?></li>

</ul>

<form action="<?php echo JRoute::_('index.php?option=com_wbty_shop{parent_url}&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="product-form" class="form-validate form-horizontal">
	<input type="hidden" name="task" value="" />
    <input type="hidden" name="option" id="option" value="com_wbty_shop" />
    <input type="hidden" name="form_name" id="form_name" value="product" />
    <?php echo JHtml::_('form.token'); ?>
</form>