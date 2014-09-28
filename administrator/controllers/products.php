<?php
/**
 * @version     0.2.0
 * @package     com_wbty_shop
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Webity <info@makethewebwork.com> - http://www.makethewebwork.com
 */

// No direct access.
defined('_JEXEC') or die;

jimport('wbty_components.controllers.wbtycontrolleradmin');

/**
 * Products list controller class.
 */
class Wbty_shopControllerProducts extends WbtyControllerAdmin
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function &getModel($name = 'product', $prefix = 'Wbty_shopModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	
	public function regenerateThumbnails() {
		require_once(JPATH_COMPONENT . '/helpers/wbty_shop.php');
		$db =& JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->select('a.id, a.image');
		$query->from('#__wbty_shop_products as a');
		$query->where('a.state=1');
		
		$ids = $db->setQuery($query)->loadObjectList();
		foreach($ids as $row) {
			Wbty_shopHelper::generateThumbnail($row->image, $row->id);
		}
		
		JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_wbty_shop&view=products'));
		exit();
	}
	
	public function editFields() {
		JFactory::getApplication()->redirect('index.php?option=com_wbty_custom_fields&view=fields&extension_info=com_wbty_shop.products');
	}
}