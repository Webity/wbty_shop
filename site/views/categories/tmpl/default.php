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
$document->addStyleSheet(JURI::root() . 'components/com_wbty_shop/assets/css/shop.css');

$num_categories = count($this->items);
$num_columns = ($this->params->get('columns') ? $this->params->get('columns') : 3);
if ($num_categories < $num_columns && $num_categories) $num_columns = $num_categories;

// Add any dynamic styles!
if ($num_categories < 3) {
	$document->addStyleDeclaration('.shop-thumb {background-size: 100% auto;}');
}
$document->addStyleDeclaration('#categories .shop-container {width: '. (100 - ($num_columns*5)) / $num_columns .'%}');
$document->addScriptDeclaration($script);
$equal_height_script = "
	var currentTallest = 0,
		 currentRowStart = 0,
		 rowDivs = new Array(),
		 $el,
		 topPosition = 0;
	
	 $('.shop-container').each(function() {
	
	   $el = $(this);
	   topPostion = $el.position().top;
	   
	   if (currentRowStart != topPostion) {
	
		 // we just came to a new row.  Set all the heights on the completed row
		 for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
		   rowDivs[currentDiv].height(currentTallest);
		 }
	
		 // set the variables for the new row
		 rowDivs.length = 0; // empty the array
		 currentRowStart = topPostion;
		 currentTallest = $el.height();
		 rowDivs.push($el);
	
	   } else {
	
		 // another div on the current row.  Add it to the list and check if it's taller
		 rowDivs.push($el);
		 currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
	
	  }
	   
	  // do the last row
	   for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
		 rowDivs[currentDiv].height(currentTallest);
	   }
	   
	 });
";
$document->addScriptDeclaration($equal_height_script);
?>

<div id="categories">
    
	<?php
	
	
	$menu_param = '';
	if ($menu_id = $this->params->get('menu_item')) $menu_param = '&Itemid='.$menu_id;
	//echo $menu_param;
	
    $html = '';
    foreach ($this->items as $shop) {
		$html .= '<div class="shop-container">';
		if ($shop->pieces) {
			$image = $shop->pieces[rand(0, count($shop->pieces) - 1)];
			$ext = strtolower(strrchr($image['image'], '.'));
			$medium_thumb = '/media/wbty_shop/thumbs/'.$image['id'].'-medium'.$ext;
			$bg_css = ' style="background-image: url(\''.JURI::root(true) . $medium_thumb.'\');"';
    
			$html .= '<a href="'.JRoute::_('index.php?option=com_wbty_shop&view=products&layout=columns'.$menu_param.'&id='.$shop->id).'"><div class="shop-thumb"'.$bg_css.'></div></a>
								';
		}
		$html .= '				<div class="shop-title" style="text-align:center;">
									<a href="'.JRoute::_('index.php?option=com_wbty_shop&view=products&layout=columns'.$menu_param.'&id='.$shop->id).'" class="shop-title">'.$shop->title.'</a>
								</div>
							</div>';
    }
    
	echo $html;
    ?>
        
    <div class="clear"></div>
</div>