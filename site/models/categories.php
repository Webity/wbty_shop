<?php

/**
 * @version     1.0.0
 * @package     com_wbty_shop
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Fritsch Services <david@makethewebwork.us> - http://www.makethewebwork.us
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Wbty_shop records.
 */
class Wbty_shopModelcategories extends JModelList {

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array()) {
        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since	1.6
     */
    protected function populateState($ordering = null, $direction = null) {
        
        // Initialise variables.
        $app = JFactory::getApplication();

        // List state information
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
        $this->setState('list.limit', $limit);

        $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
        $this->setState('list.start', $limitstart);

        // List state information.
        parent::populateState();
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return	JDatabaseQuery
     * @since	1.6
     */
    protected function getListQuery() {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
                $this->getState(
                        'list.select', 'a.*'
                )
        );
        $query->from('`#__categories` AS a');
		$query->where('a.extension = "com_wbty_shop"');
		
		// Filter by selected categories
		if ($categories = $this->state->get('parameters.menu')->get('categories')) {
			$query->where('a.id IN ('.implode(',', $categories).')');
		}


        
        // Filter by published state
        $published = $this->getState('filter.state');
        if (is_numeric($published)) {
            $query->where('a.state = '.(int) $published);
        } else if ($published === '') {
            $query->where('(a.state = 1 )');
        }
        
        return $query;
    }
	
	public function getItems() {
		$query	= $this->_db->getQuery(true);
		
		$items = parent::getItems();
		
		$item_count = count($items);
		
		// Get the associated pieces and add them to each category
		for ($i = 0; $i < $item_count; $i++) {
			$query->clear();
			$query->select('a.id');
			$query->from('#__categories AS a');
			$query->where('a.extension = "com_wbty_shop"');
			$query->where('a.lft >= '.$items[$i]->lft);
			$query->where('a.rgt <= '.$items[$i]->rgt);
			$ids = $this->_db->setQuery($query)->loadColumn();
			
			if ($ids) {
    			$query->clear();
    			$query->select('i.image, i.id');
    			$query->from('#__wbty_shop_products AS i');
    			$query->where('i.category IN ('.implode(',', $ids).')');
    			$query->where('i.image != \'\'');
                $query->where('i.base_id = 0');
    			$query->where('i.state = 1');
    			//echo str_replace('#__', 'wbty_', $query);
    			$pieces = $this->_db->setQuery($query)->loadAssocList();
    			$items[$i]->pieces = $pieces;
            }
		}
		return $items;
	}

}
