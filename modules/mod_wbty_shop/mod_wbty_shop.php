<?php defined('_JEXEC') or die('Restricted access');

$db =& JFactory::getDbo();

$query = $db->getQuery(true);

$query->select('*');
$query->from('#__wbty_shop_products as i');
if ($params->get('category')) {
	$query->where('i.category = '.(int)$params->get('category'));
}
$query->join('LEFT','#__categories as c ON c.id=i.category AND c.published=1');
$query->where('i.state=1');
$query->order('RAND()');

$image = $db->setQuery($query, 0, 1)->loadObject();

require JModuleHelper::getLayoutPath('mod_wbty_shop', 'default');

?>