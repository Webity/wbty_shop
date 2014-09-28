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

$num_photos = count($this->items);
$num_columns = ($this->params->get('columns') ? $this->params->get('columns') : 3);

$i = 0;
$html = array();
foreach ($this->items as $image) {
	
	$ext = strtolower(strrchr($image->image, '.'));
	$small_thumb = '/media/wbty_shop/thumbs/'.$image->id.'-small'.$ext;
	$large_thumb = '/media/wbty_shop/thumbs/'.$image->id.'-large'.$ext;

	$html[$i][] = '<div class="image-thumb">
						<img src="'. JURI::root(true) . $small_thumb . '" data-src="'. JURI::root(true) . $large_thumb . '" data-id="'.$image->id.'" data-count="'.$i.'" data-price-set="'.$image->pricing_set.'" />
						<p class="image-title">'.$image->name.'</p>
						<div class="image-desc">'.$image->description.'</div>
					</div>';

	if ($i == $num_columns - 1) {
		$i = 0;
	} else {
		$i++;
	}

}

foreach ($html as $h) {
	echo '<div class="thumb-column">';
	foreach ($h as $img) {
		echo $img;
	}
	echo '</div>';
}