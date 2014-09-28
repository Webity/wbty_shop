<?php
/**
 * @version     1.0.0
 * @package     com_wbty_shop
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Fritsch Services <david@makethewebwork.us> - http://www.makethewebwork.us
 */

/**
 * @param	array	A named array
 * @return	array
 */
function Wbty_shopBuildRoute(&$query)
{
	$segments = array();
	
	$app =& JFactory::getApplication();
	$menu		= $app->getMenu();
	$active = $menu->getActive();
	
	// hack to protect the actual current item as well as the search module or other places that use JRoute::_('index.php');
	if (count($query)==2 && $query['option'] && $query['Itemid']) {
		return $segments;
	}
	if (empty($query['Itemid']) || $query['Itemid'] == $active->id) {
		$items = $menu->getMenu();
		$match = false;
		$match_level = 0;
		//var_dump($items);
		foreach ($items as $item) {
			
			if ($item->query['option'] == $query['option']) {
				if ($item->query['view'] == $query['view'] && $item->query['layout'] == $query['layout'] && $item->query['id'] == $query['id']) {
					$match = $item;
					$match_level = 4;
					break;
				} elseif ($item->query['view'] == $query['view'] && $item->query['layout'] == $query['layout'] && $match_level < 3) {
					$match = $item;
					$match_level = 3;
				} elseif ($item->query['view'] == $query['view'] && $match_level < 2) {
					$match = $item;
					$match_level = 2;
				} elseif ($match_level < 1) {
					$match = $item;
					$match_level = 1;
				}
			}
		}
		
		if ($match) {
			$query['Itemid'] = $match->id;
			$menuItem = $menu->getItem($match->id);
			$menuItemGiven = true;
		} else {
			$menuItem = $menu->getActive();
			$menuItemGiven = false;
		}
	}
	if ($match_level > 1) {
		unset($query['view']);
	} elseif (isset($query['view'])&& strpos($query['view'],'.')===FALSE) {
		// by supporting tasks we do not support views with a period in them. Don't do it.
		$segments[] = $query['view'];
		unset($query['view']);
	} elseif (isset($query['task']) && strpos($query['task'],'.')!==FALSE) {
		// we can place a task in the view's position as long as it has a period in it to distinguish. Also you can't set a view and task without the task not being parsed
		$segments[] = $query['task'];
		unset($query['task']);
	} else {
		// skip parsing if no view or task is set. View is required
		return $segments;
	}
	if (isset($query['layout'])) {
		if ($match_level < 3) {
			$segments[] = $query['layout'];
		}
		unset($query['layout']);
	}
	if (isset($query['id'])) {
		if ($match_level < 4) {
			$segments[] = $query['id'];
		}
		unset($query['id']);
	}
	if (isset($query['format'])) {
		$segments[] = $query['format'];
		unset($query['format']);
	}

	return $segments;
}

/**
 * @param	array	A named array
 * @param	array
 *
 * Formats:
 *
 * index.php?/banners/task/id/Itemid
 *
 * index.php?/banners/id/Itemid
 */
function Wbty_shopParseRoute($segments)
{
	$app = JFactory::getApplication();
	$menu = $app->getMenu(true);
	$item = $menu->getActive();
	$vars = $item->query ? $item->query : array();

	$count = count($segments);
	
	if ($count)
	{
		$count--;
		$segment = array_shift($segments);
		if (is_numeric($segment)) {
			$vars['id'] = $segment;
		} elseif (strpos($segment, '.')===FALSE) {
			$vars['view'] = $segment;
		} else {
			$vars['task'] = $segment;
		}
	}

	while ($count)
	{
		$count--;
		$segment = array_shift($segments) ;
		if (is_numeric($segment)) {
			$vars['id'] = $segment;
		} else {
			if (!isset($vars['id'])) {
				$vars['layout'] = $segment;
			} else {
				$vars['format'] = $segment;
			}
		}
	}
	
	return $vars;
}
