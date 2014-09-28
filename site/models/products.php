<?php

/**
 * @version     0.2.0
 * @package     com_wbty_shop
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Webity <info@makethewebwork.com> - http://www.makethewebwork.com
 */
defined('_JEXEC') or die;

jimport('wbty_components.models.wbtymodellist');

/**
 * Methods supporting a list of Wbty_shop records.
 */
class Wbty_shopModelproducts extends WbtyModelList {

    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'ordering', 'a.ordering',
                'state', 'a.state',
                'image', 'a.image',
				'name', 'a.name',
				'description', 'a.description',
				'category', 'a.category',
				'pricing_set', 'a.pricing_set',
				'slide_type', 'a.slide_type',
				'menu_link', 'a.menu_link',
				'caption', 'a.caption',
            );
        }

        parent::__construct($config);
    }

    protected function populateState($ordering = null, $direction = null) {
        
        // Initialise variables.
        $app = JFactory::getApplication();
        $this->params = $app->getParams('com_wbty_shop');
		
		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $app->getUserStateFromRequest($this->context.'.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);

        // List state information.
        parent::populateState('a.image', 'asc');

        // List state information
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $this->params->get('list_limit'));
        $this->setState('list.limit', $limit);

        $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
        $this->setState('list.start', $limitstart);

        // LIMIT THAT STUFF IF NEED BE!
        if ($this->state->get('parameters.menu')) {
            if ($limit = $this->state->get('parameters.menu')->get('limit')) {
                $app->input->set('limit', $limit);
            }
        }
    }

    protected function getStoreId($id = '')
    {
        // Compile the store id.
        $id.= ':' . $this->getState('filter.search');
        $id.= ':' . $this->getState('filter.state');

        return parent::getStoreId($id);
    }
    
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
        $query->select('c.title AS category_name');
        $query->from('`#__wbty_shop_products` AS a');
        $query->join('LEFT', '#__categories AS c ON a.category=c.id');
        
        // Filter by categories
        if ($shop = JRequest::getVar('shop_id')) {
        } else if ($shop = JRequest::getVar('id')) {
        } else if ($this->state->get('parameters.menu') && $shop = $this->state->get('parameters.menu')->get('id')) {
        } else if ($this->state->get('parameters.menu') && $categories = $this->state->get('parameters.menu')->get('categories')) {
            $query->where('a.category IN ('.implode(',', $categories).')');
        }
        
        if ($categories) {
            
        }
        
        if ($shop) {
            $query->where('c.lft>=(SELECT cat.lft FROM #__categories as cat WHERE cat.id='.(int)$shop.') AND c.rgt<=(SELECT cat.rgt FROM #__categories as cat WHERE cat.id='.(int)$shop.')');
        }

        
        // Filter by published state
        $published = $this->getState('filter.state');
        if (is_numeric($published)) {
            $query->where('a.state = '.(int) $published);
        } else {
            $query->where('(a.state = 1)');
        }
        
        $query->where('a.base_id = 0');
        $query->order('c.rgt DESC, ordering DESC');
        
        return $query;
    }
    
    public function getStart()
    {

        $start = $this->getState('list.start');
        $limit = $this->getState('list.limit');
        $total = $this->getTotal();
        if ($start > $total - $limit)
        {
            $start = max(0, (int) (ceil($total / $limit) - 1) * $limit);
        }
        
        return $start;
    }
}
