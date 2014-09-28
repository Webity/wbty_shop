<?php
/**
 * @version     0.2.0
 * @package     com_wbty_shop
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Webity <info@makethewebwork.com> - http://www.makethewebwork.com
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');
jimport('legacy.controller.legacy');

if (!class_exists(JControllerLegacy)) {
	class JControllerLegacy extends JController {}
}

// Include base css and javascript files for the component
// Import CSS
$document = &JFactory::getDocument();
if ($_SERVER['REQUEST_URI']) {
	$document->setBase($_SERVER['REQUEST_URI']);
}

JHTML::stylesheet('wbty_shop/wbty_shop.css', false, true);
JHTML::stylesheet('wbty_shop/site.css', false, true);

$jversion = new JVersion();
$above3 = version_compare($jversion->getShortVersion(), '3.0', 'ge');

if ($above3) {
	JHtml::_('bootstrap.framework');
	JHTML::stylesheet('wbty_components/ui-lightness/jquery-ui-1.10.3.custom.min.css', false, true);
	JHTML::script('wbty_components/jquery-ui-1.10.3.custom.min.js', false, true);
	if (JFactory::getApplication()->isAdmin()) {}
} else {
	JHTML::stylesheet('wbty_components/ui-lightness/jquery-ui-1.10.3.custom.min.css', false, true);
	JHTML::stylesheet('wbty_components/bootstrap.min.css', false, true);
	JHTML::stylesheet('wbty_components/font-awesome.min.css', false, true);
	JHTML::script('wbty_components/jquery-1.10.2.min.js', false, true);
	JHTML::script('wbty_components/jquery-ui-1.10.3.custom.min.js', false, true);
	JHTML::script('wbty_components/bootstrap.min.js', false, true);
}

//include backend language file for jfield labels and descriptions
$lang =& JFactory::getLanguage();
$extension = '';
$base_dir = JPATH_ADMINISTRATOR;
$language_tag = 'en-GB';
$reload = true;
$lang->load($extension, $base_dir, $language_tag, $reload);

// Execute the task.
$controller	= JControllerLegacy::getInstance('Wbty_shop');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();