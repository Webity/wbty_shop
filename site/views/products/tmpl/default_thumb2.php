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
$document->addScript( JURI::root(true) . "/media/wbty_shop/js/jquery.thumbnailScroller.js");
$document->addScript( JURI::root(true) . "/media/wbty_shop/js/jquery-ui-1.8.23.custom.min.js");
$document->addStylesheet( JURI::root(true) . "/media/wbty_shop/css/jquery.thumbnailScroller.css");
$document->addStylesheet( JURI::root(true) . "/media/wbty_shop/css/ui-lightness/jquery-ui-1.8.23.custom.css");


$javascript = "

jQuery(window).load( function () {
	jQuery('#wbty_shop_thumbs').thumbnailScroller({ 
		scrollerType:'clickButtons', 
		scrollerOrientation:'horizontal', 
		scrollSpeed:2, 
		scrollEasing:'easeOutCirc', 
		scrollEasingAmount:600, 
		acceleration:4, 
		scrollSpeed:800, 
		noScrollCenterSpace:10, 
		autoScrolling:0, 
		autoScrollingSpeed:2000, 
		autoScrollingEasing:'easeInOutQuad', 
		autoScrollingDelay:500 
	});";

$javascript .= "});";

$document->addScriptDeclaration( $javascript );

$num_photos = count($this->items);
$num_columns = ($this->params->get('columns') ? $this->params->get('columns') : 3);

?>
<div id="wbty_shop_thumbs" class="jThumbnailScroller">
<div class="jTscrollerContainer">
<div class="jTscroller">

<?php
$i = 0;
$html = array();
foreach ($this->items as $image) {
	
	$ext = strtolower(strrchr($image->image, '.'));
	$small_thumb = '/media/wbty_shop/thumbs/'.$image->id.'-small'.$ext;
	$large_thumb = '/media/wbty_shop/thumbs/'.$image->id.'-large'.$ext;

	
	echo '<a href="#wbty_shop/'.$i.'">
			<div class="image-thumb">
				<img src="'. JURI::root(true) . $small_thumb . '" data-src="'. JURI::root(true) . $large_thumb . '" data-id="'.$image->id.'" data-count="'.$i.'" data-price-set="'.$image->pricing_set.'" />
				<p class="image-title">'.$image->name.'</p>
				<div class="image-desc">'.$image->description.'</div>
			</div>
		</a>';
	
	$i++;
}
?>

</div>
</div>
<a href="#" class="jTscrollerPrevButton"></a>
<a href="#" class="jTscrollerNextButton"></a>
</div>