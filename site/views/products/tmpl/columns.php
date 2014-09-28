<?php
/**
 * @version     1.0.0
 * @package     com_wbty_shop
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Fritsch Services <david@makethewebwork.us> - http://www.makethewebwork.us
 */


// no direct access
defined('_JEXEC') or die;

$document = JFactory::getDocument();
$document->addScript('//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js');
$document->addScript(JURI::root(true) . '/media/wbty_shop/js/wbty_shop.js');
$document->addStyleSheet(JURI::root(true) . '/components/com_wbty_shop/assets/css/shop.css');

// Add photo capabilities
$script = "";

$document->addScriptDeclaration($script);
$columns = $this->params->get('columns', 1);
if ($this->params->get('image_modal')) {
	JHTML::_('behavior.modal');
}
?>

<div id="shop-surround">
<div id="showcase <?php echo 'columns-'.$columns; ?>">
    <?php foreach ($this->items as $key => $item) : ?>
    <?php $link = '<a href="'.JRoute::_('index.php?option=com_wbty_shop&view=product&id='.$item->id).'">'; ?>
    <div class="shop-item<?php if ($key % $columns == 0) { echo ' clearer'; } ?>">
    	<div class="shop-wrap">
            <div class="content">
            	<?php if ($this->params->get('display_title')) : ?>
                <div class="item-title">
                    <?php echo '<'.$this->params->get('title_level').'>'; echo $link.$item->name; ?></a><?php echo '</'.$this->params->get('title_level').'>'; ?>
                </div>
                <?php endif; ?>
                <?php
                if ($this->params->get('display_image') && $item->image) {
					$ext = strtolower(strrchr($item->image, '.'));
					$medium_thumb = '/media/wbty_shop/thumbs/'.$item->id.'-medium'.$ext;
					$large_thumb = '/media/wbty_shop/thumbs/'.$item->id.'-large'.$ext;
				
					
					echo '<div class="main-image">'.$link.'<img src="'. JURI::root(true) . $medium_thumb . '" /></a>';
					if ($this->params->get('image_modal')) {
						echo '<a href="'.JURI::root(true) . $large_thumb.'" class="fulllink visible-phone"></a>';
						echo '<a href="'.JURI::root(true) . $large_thumb.'" class="fulllink modal hidden-phone"></a>';
					}
					echo '</div>';
				}
				?>
                <?php
                if ($this->params->get('display_caption') && $item->caption) {
					echo '
							<div class="main-caption">
								'.$item->caption.'
							</div>
						';
				}
				?>
                <?php
                if ($this->params->get('display_custom_fields') && file_exists(JPATH_BASE . '/components/com_wbty_custom_fields/helpers/loader.php')) {
					require_once(JPATH_BASE . '/components/com_wbty_custom_fields/helpers/loader.php');
					
					echo Wbty_custom_fieldsHelperLoader::loadValues('com_wbty_shop', 'products', $item->id);
				}
				?>
                <?php if ($this->params->get('display_description')) : ?>
                <div class="desc">
                <?php $content = explode('<hr id="system-readmore" />', $item->description); echo $content[0]; ?>
                </div>
                <div class="readmore">
                	<a href="<?php echo JRoute::_('index.php?option=com_wbty_shop&view=product&id='.$item->id); ?>">Read more</a>
                </div>
                <?php endif; ?>
            </div>
            <div class="pay-options" style="display:none;">
                <h4>Purchase this item</h4>
                <form method="post" action="<?php echo JRoute::_('index.php?option=com_wbty_shop&task=image.add2cart'); ?>" class="form">
                    <div class="control-group">
                        <label class="control-label" for="quantity">Quantity</label>
                        <div class="controls">
                            <input type="text" id="quantity" name="jform[quantity]" value="1" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="product">Options</label>
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
    </div>
    <?php endforeach; ?>
</div>
<style>
.shop-item {
	float:left;
	width: <?php echo 100/$this->params->get('columns'); ?>%;	
}
.shop-item.clearer { clear:left; }
.shop-item .shop-wrap { padding: 0 2%; }

.shop-item img { max-width:100%; }
.shop-item .title h3 { font-size:70%; line-height:1.2em;}
</style>
    <div class="clear"></div>
    <div class="pagination">
        <p class="counter">
                <?php echo $this->pagination->getPagesCounter(); ?>
        </p>
        <?php echo $this->pagination->getPagesLinks(); ?>
    </div>
</div>