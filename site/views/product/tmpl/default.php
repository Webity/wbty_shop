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

<?php if( $this->item ) : $item = $this->item; ?>
<div class="shop-item">
    <div class="shop-wrap">
        <div class="content">
            <?php if ($this->params->get('display_title')) : ?>
            <h2 class="title"><?php echo $item->name; ?></h2>
            <?php endif; ?>
            
            <?php if ($item->category) : ?>
            <a href="<?php echo JRoute::_('index.php?option=com_wbty_shop&view=products&layout=columns&id='.$item->category); ?>" class="btn">View all items in Category</a>
            <?php endif; ?>
            
            <?php 
			if ($this->params->get('display_image') && $item->image) {
				$ext = strtolower(strrchr($item->image, '.'));
				$small_thumb = '/media/wbty_shop/thumbs/'.$item->id.'-small'.$ext;
				$large_thumb = '/media/wbty_shop/thumbs/'.$item->id.'-large'.$ext;
			
				
				echo '
						<div class="image">
							<img src="'. JURI::root(true) . $large_thumb . '" />
							<a href="'.JURI::root(true) . $large_thumb.'" class="fulllink modal hidden-phone"></a>
						</div>
					';
			}
			if ($this->params->get('display_custom_fields') && file_exists(JPATH_BASE . '/components/com_wbty_custom_fields/helpers/loader.php')) {
				require_once(JPATH_BASE . '/components/com_wbty_custom_fields/helpers/loader.php');
				
				echo Wbty_custom_fieldsHelperLoader::loadValues('com_wbty_shop', 'products', $this->item->id);
			}
			?>
            <?php if ($item->content_position == 'above' && $this->params->get('display_description')) : ?>
            <div class="desc">
                <?php echo $item->description; ?>
            </div>
			<?php endif; ?>
        </div>
        <div class="pay-options">
        	<?php 
				require_once(JPATH_BASE . '/components/com_wbty_pricing/helpers/wbty_pricing.php');
				$price_set = Wbty_pricingHelper::getPricingSet($item->pricing_set);
			?>
            <h4>Purchase this item</h4>
            <form method="post" action="<?php echo JRoute::_('index.php?option=com_wbty_shop&task=product.add2cart'); ?>" class="form-horizontal">
            	<div class="control-group">
                    <label class="control-label" for="price">Price</label>
                    <div class="controls">
                        <h5>$<?php echo $price_set->base_price; ?></h5>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="quantity">Quantity</label>
                    <div class="controls">
                        <input type="text" id="quantity" name="jform[quantity]" value="1" />
                    </div>
                </div>
                <?php if ($price_set->options) : ?>
                <div class="control-group">
                    <label class="control-label" for="product"><?php echo $option[0]->name; ?></label>
                    <div class="controls">
                        <select id="product" name="jform[product][]">
                        <?php foreach ($price_set->prices as $option) : ?>
                        	<option value="<?php echo $option->id; ?>"><?php echo $option->option_name; if ($option->price) { echo ", +$".$option->price; } ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <?php endif; ?>
                <div class="control-group">
                    <div class="controls">
                        <input type="hidden" id="image" name="jform[image]" value="<?php echo $item->id; ?>" />
                        <input type="submit" value="Add to Cart" />
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <?php if ($this->params->get('display_description') && $item->content_position != 'above') : ?>
    <hr>
    <div class="desc">
	    <?php echo $item->description; ?>
    </div>
    <?php endif; ?>
    
</div>
<?php endif; ?>
