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

jimport('wbty_components.models.wbtymodeladmin');

/**
 * Wbty_shop model.
 */
class Wbty_shopModelproduct extends WbtyModelAdmin
{
	protected $text_prefix = 'com_wbty_shop';
	protected $com_name = 'wbty_shop';
	protected $list_name = 'products';
	
	public function getTable($type = 'products', $prefix = 'Wbty_shopTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	protected function populateState()
	{
		$app = JFactory::getApplication('site');

		// Load state from the request.
		$pk = JRequest::getInt('id');
		$this->setState('product.id', $pk);

		$offset = JRequest::getUInt('limitstart');
		$this->setState('list.offset', $offset);

		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);

		// TODO: Tune these values based on other permissions.
		$user		= JFactory::getUser();
		if ((!$user->authorise('core.edit.state', 'com_wbty_shop')) &&  (!$user->authorise('core.edit', 'com_wbty_shop'))){
			$this->setState('filter.published', 1);
			$this->setState('filter.archived', 2);
		}
	}

	public function getForm($data = array(), $loadData = true, $control='jform', $key=0)
	{
		// Initialise variables.
		$app	= JFactory::getApplication();

		JForm::addFieldPath(JPATH_COMPONENT_ADMINISTRATOR . '/models/fields');
		// Get the form.
		$form = $this->loadForm('com_wbty_shop.product.'.$control.'.'.$key, 'product', array('control' => $control, 'load_data' => $loadData, 'key'=>$key));

		if (empty($form)) {
			return false;
		}

		return $form;
	}
	
	public function getItems($parent_id, $parent_key) {
		$query = $this->_db->getQuery(true);
		
		$query->select('id, state');
		$query->from($this->getTable()->getTableName());
		$query->where($parent_key . '=' . (int)$parent_id);
		$query->order('state DESC');
		
		$data = $this->_db->setQuery($query)->loadObjectList();

		if (count($data)) {
			$this->getState();
			foreach ($data as $key=>$d) {
				$this->data = null;
				$this->setState($this->getName() . '.id', $d->id);

				$return[$d->id] = $this->getForm(array(), true, 'jform', $d->id);
			}
		}
		
		return $return;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData()
	{
		if (isset($this->data) && $this->data) {
			return $this->data;
		}
		
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_wbty_shop.edit.product.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}

	public function getItem($pk = null)
	{
		if ($item['product'] = parent::getItem($pk)) {

			//Do any procesing on fields here if needed
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);

			$query->select('c.parent_id AS parent_category');
			$query->from('#__categories AS c');
			$query->where('c.id = '. (int) $item['product']->category);
			
			$item['product']->parent_category = $db->setQuery($query)->loadResult();
		}
		
		return $item;
	}
	
	protected function prepareTable(&$table)
	{
		$user =& JFactory::getUser();

		

		parent::prepareTable($table);
	}
	
	public function save($data) {
		if (!parent::save($data)) {
			return false;
		}
		
		// manage link
		
		
		return $this->table_id;
	}

}