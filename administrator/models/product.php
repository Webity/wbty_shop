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

	public function getForm($data = array(), $loadData = true, $control='jform', $key=0)
	{
		// Initialise variables.
		$app	= JFactory::getApplication();
		
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
		$query->where($parent_key . '!= 0');
		$query->order('state DESC, ordering ASC');
		
		$data = $this->_db->setQuery($query)->loadObjectList();
		if (count($data)) {
			$this->getState();
			$key=0;
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
		if ($this->data) {
			return $this->data;
		}
		
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_wbty_shop.edit.product.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param	integer	The id of the primary key.
	 *
	 * @return	mixed	Object on success, false on failure.
	 * @since	1.6
	 */
	public function getItem($pk = null)
	{
		if ($item['product'] = parent::getItem($pk)) {

			//Do any procesing on fields here if needed
			
			
		}

		return $item;
	}

	protected function prepareTable(&$table)
	{
		$user =& JFactory::getUser();
		
		

		parent::prepareTable($table);
	}
	
	function save($data) {
		$jform = JFactory::getApplication()->input->get('jform', array(), 'ARRAY');
		if (isset($jform['wbty_gallery']['gallery'])) {
			$data['image'] = $jform['wbty_gallery']['gallery'];
		}

		if (!parent::save($data)) {
			return false;
		}
		
		// manage link
		
		if (file_exists(JPATH_ROOT . '/components/com_wbty_custom_fields/helpers/loader.php')) {
			require_once(JPATH_ROOT . '/components/com_wbty_custom_fields/helpers/loader.php');
			
			Wbty_custom_fieldsHelperLoader::saveFormFields('com_wbty_shop', 'products', $this->table_id);
		}
		
		return $this->table_id;
	}
	
	public function batch($commands, $pks, $contexts)
	{
		// Sanitize user ids.
		$pks = array_unique($pks);
		JArrayHelper::toInteger($pks);

		// Remove any values of zero.
		if (array_search(0, $pks, true))
		{
			unset($pks[array_search(0, $pks, true)]);
		}

		if (empty($pks))
		{
			$this->setError(JText::_('JGLOBAL_NO_ITEM_SELECTED'));
			return false;
		}
		
		foreach($pks as $pk) {
			$table = $this->getTable();
			
			$table->load($pk);
			
			if (!empty($commands['category']))
			{
				$table->category = $commands['category'];
			}
			
			if (!empty($commands['pricing_set']))
			{
				$table->pricing_set = $commands['pricing_set'];
			}
			
			$table->store();
			
		}

		// Clear the cache
		$this->cleanCache();

		return true;
	}
}