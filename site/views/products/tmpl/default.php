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

$jversion = new JVersion();
$above3 = version_compare($jversion->getShortVersion(), '3.0', 'ge');
if (!$above3) {
	JFactory::getDocument()->addScriptDeclaration('
jQuery(document).ready(function($) {
	$("[title]").tooltip();
});
		');
} else {
	JHtml::_('bootstrap.tooltip');
}

JHtml::_('script', 'wbty_shop/wbty_shop.js', false, true);
JHtml::_('script','system/multiselect.js',false,true);

$user	= JFactory::getUser();
$userId	= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$canOrder	= $user->authorise('core.edit.state', 'com_wbty_shop');
$saveOrder	= $listOrder == 'a.ordering';

ob_start();
?>
jQuery(document).ready(function($) {
	$('.state-filter').click(function() {
		if ($(this).hasClass('active')) {return;}

		value = 1;
		if ($(this).hasClass('trashed')) {value = -2;}

		$('#adminForm').append('<input type="hidden" name="filter_published" value="'+ value +'" />').submit();
	});
});
<?php 
$script = ob_get_contents();
ob_end_clean();
$document = JFactory::getDocument();
$document->addScriptDeclaration($script);

$num_photos = count($this->items);
$num_columns = ($this->params->get('columns') ? $this->params->get('columns') : 3);
if ($num_photos < $num_columns && $num_photos > 0) $num_columns = $num_photos;

// Add any dynamic styles!
$document->addStyleDeclaration('#thumbs .thumb-column {width: '. 100 / $num_columns .'%}');
?>

<div id="shop-surround">
    <div class="shop-title">
    	<?php foreach ($this->items as $item) {
			if ($item->category) {
				echo '<h2>'.$item->category_name.'</h2>';
				$firstimage = $item;
				break;
			}
		}
		if ($this->params->get('show_num')) echo '<p>'.$num_photos.' photos</p>';
		?>
    </div>
    
    <div id="showcase">
    	<div class="image-desc">
            <div class="image-title">
                <h3></h3>
            </div>
            <div class="desc">
            
            </div>
        </div>
        <div class="image">
            <?php echo '<img src="' . JURI::root(true) . '/media/wbty_shop/images/ajax_loader.gif" data-count="0" />'; ?> 
            <div class="prev-image"></div>
            <div class="next-image"></div>
        </div>
        <div class="pay-options">
        	<h4>Purchase this print</h4>
        	<form method="post" action="<?php echo JRoute::_('index.php?option=com_wbty_shop&task=image.add2cart'); ?>" class="form-horizontal">
            	<div class="control-group">
                	<label class="control-label" for="quantity">Quantity</label>
                    <div class="controls">
                        <select id="quantity" name="jform[quantity]">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                </div>
            	<div class="control-group">
                	<label class="control-label" for="product">Product</label>
                    <div class="controls">
                        <select id="product" name="jform[product]">
                        </select>
                    </div>
                </div>
            	<div class="control-group">
                    <div class="controls">
	                    <input type="hidden" id="image" name="jform[image]" value="" />
                        <input type="submit" value="Add to Cart" />
                    </div>
                </div>
            </form>
        </div>
    </div>
	
	<div id="thumbs">
        <?php 
		if ($this->params->get('thumb_layout') && file_exists(JPATH_COMPONENT . '/views/images/tmpl/default_thumb'.$this->params->get('thumb_layout').'.php')) :
			echo $this->loadTemplate('thumb'.$this->params->get('thumb_layout'));
		else :
			echo $this->loadTemplate('thumb2');
		endif;
		?>
        
        <div class="clear"></div>
        
    </div>
</div>
