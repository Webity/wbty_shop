<?php
/**
 * @version     0.2.0
 * @package     com_wbty_shop
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Webity <info@makethewebwork.com> - http://www.makethewebwork.com
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Wbty_shop helper.
 */
class Wbty_shopHelper
{
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($vName = '')
	{
		
		
		JSubMenuHelper::addEntry(
			JText::_('COM_WBTY_SHOP_TITLE_CONTROLPANEL'),
			'index.php?option=com_wbty_shop&view=controlpanel',
			$vName == 'controlpanel'
		);
		JSubMenuHelper::addEntry(
			JText::_('Products'),
			'index.php?option=com_wbty_shop&view=products',
			$vName == 'products'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_WBTY_SHOP_TITLE_CATEGORIES'),
			'index.php?option=com_categories&extension=com_wbty_shop',
			$vName == 'categories'
		);

		$params = JComponentHelper::getParams('com_wbty_shop');
		if ($params->get('pricing')) {
			JSubMenuHelper::addEntry(
				JText::_('COM_WBTY_SHOP_TITLE_PRICING_SETS'),
				'index.php?option=com_wbty_pricing&extension=com_wbty_shop',
				$vName == 'pricing_sets'
			);
		}
		
		/*JSubMenuHelper::addEntry(
			JText::_('COM_WBTY_SHOP_TITLE_BULK_UPLOAD'),
			'index.php?option=com_wbty_shop&view=bulk_upload',
			$vName == 'bulk_upload'
		);*/
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return	JObject
	 * @since	1.6
	 */
	public static function getActions()
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		$assetName = 'com_wbty_shop';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}

		return $result;
	}
	
	public static function generateThumbnail($file, $id, $thumbfolder = 'media/wbty_shop/thumbs/', $maxsides = array('small'=>150, 'medium'=>400, 'large'=>1000)) {
		if ( !($image=self::openImage(JPATH_ROOT . '/' . $file)) || !$id) {
			// TODO: Add Fail handler
			return false;
		}
		
		$basename = basename($file);
		$width  = imagesx($image);  
        $height = imagesy($image);
		$ext = strtolower(strrchr($file, '.')); 
		
		foreach ($maxsides as $key=>$maxside) {
			$newwidth = $newheight = 0;
			
			if ($width >= $height) {
				$newwidth = $maxside;
				$newheight = $newwidth*$height/$width;
			} elseif ($height > $width) {
				$newheight = $maxside;
				$newwidth = $newheight*$width/$height;
			}
			
			if ($newheight && $newwidth) {
				$newimage = imagecreatetruecolor($newwidth, $newheight);  
				
				$extension = strtolower(strrchr($file, '.'));
				switch($extension)
				{
					case '.gif': 
						$background = imagecolorallocate($newimage, 0, 0, 0);
						imagecolortransparent($newimage, $background);
						break;  
					case '.png':  
						$background = imagecolorallocate($newimage, 0, 0, 0);
						imagecolortransparent($newimage, $background);
						imagealphablending($newimage, false);
						imagesavealpha($newimage, true);
						break;  
				}  
				
				imagecopyresampled($newimage, $image, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
				
				if (!self::saveImage($newimage, $thumbfolder . $id . '-' . $key . $ext)) {
					// TODO: Add fail handler
				}
			}
		}
		
		return true;
	}
	
	private function openImage($file)  
	{  
		// *** Get extension  
		$extension = strtolower(strrchr($file, '.'));
		switch($extension)
		{  
			case '.jpg':  
			case '.jpeg':  
				$img = @imagecreatefromjpeg($file);  
				break;  
			case '.gif':  
				$img = @imagecreatefromgif($file);  
				break;  
			case '.png':  
				$img = @imagecreatefrompng($file);  
				$background = imagecolorallocate($img, 0, 0, 0);
				imagecolortransparent($img, $background);
				imagealphablending($img, false);
				imagesavealpha($img, true);
				break;  
			default:
				$img = false;  
				break;  
		}  
		return $img;  
	} 
	
	public function saveImage($image, $savePath, $imageQuality="100")  
	{  
		// *** Get extension  
			$extension = strrchr($savePath, '.');  
			$extension = strtolower($extension);  
		switch($extension)  
		{  
			case '.jpg':  
			case '.jpeg':  
				if (imagetypes() & IMG_JPG) {  
					imagejpeg($image, JPATH_ROOT . '/' . $savePath, $imageQuality);  
				}  
				break;  
			case '.gif':  
				if (imagetypes() & IMG_GIF) {  
					imagegif($image, JPATH_ROOT . '/' . $savePath);  
				}  
				break;  
			case '.png':  
				// *** Scale quality from 0-100 to 0-9  
				$scaleQuality = round(($imageQuality/100) * 9);  
				// *** Invert quality setting as 0 is best, not 9  
				$invertScaleQuality = 9 - $scaleQuality;  
				if (imagetypes() & IMG_PNG) {  
					imagepng($image, JPATH_ROOT . '/' . $savePath, $invertScaleQuality);  
				}  
				break;  
			// ... etc  
			default:  
				// *** No extension - No save.  
				break;  
		}  
		imagedestroy($image); 
		return $savePath; 
	} 
}

class JHTMLWbty_shopHelper {
	

	public static function buildEditForm($form, $hidden = true) {
		if (!$form instanceof JForm) {
			return false;
		}

		ob_start();
		foreach ($form->getFieldsets() as $fieldset) {
			echo '<fieldset name="'.$fieldset->name.'"';
			$class = array();
			$field = $value = '';
			if ($fieldset->multiple) {
				 $class[] = 'multiple';
			}
			if ($fieldset->dependency) {
				$class[] = 'dependency';
				$field = $fieldset->field;
				$value = $fieldset->value;
			}
			if ($class) {
				echo ' class="'. implode(' ', $class) . '"';
			}
			if ($field && $value) {
				echo ' data-field="'. $field . '" data-value="'. $value . '"';
			}
			if ($fieldset->copy) {
				echo ' data-copy="' . $fieldset->copy . '"';
			}
			echo '>';
			if ($fieldset->legend) {
				echo '<legend>'.$fieldset->legend.'</legend>';
			}
			if ($fieldset->soc) {
				echo '<p>This section should have a search or create option. Only one is currently shown.</p>';
			}
			//echo '<div class="edit-values">';
			foreach($form->getFieldset($fieldset->name) as $field):
				if (!$field->hidden && $field->display_value) {
				//	echo strip_tags($field->label) . ': <span class="' . str_replace(array('[',']'), array('_'),$field->name) . '">' . $field->value . '</span><br>';
				}
			endforeach;
			echo '<!--</div>-->
			<div class="edit-form">';
			foreach($form->getFieldset($fieldset->name) as $field):
				// If the field is hidden, only use the input.
				if ($field->hidden):
					echo $field->input;
				else:
				?>
				<div class="control-group">
					<?php echo str_replace('<label', '<label class="control-label"', $field->label); ?>
					<div class="controls">
						<?php echo $field->input; ?>
					</div>
				</div>
				<?php
				endif;
			endforeach;
			echo '</div>';
			echo '</fieldset>';
		}
		$html = ob_get_contents();
		ob_end_clean();

		if ($hidden) {
			$html = '<div style="display:none;" id="hidden-forms">'.$html.'</div>';
		}

		return $html;
	}
}
