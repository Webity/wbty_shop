<?php
/**
 * @package AkeebaReleaseSystem
 * @copyright Copyright (c)2010-2011 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @version $Id: ars.php 123 2011-04-13 07:47:16Z nikosdion $
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

// Install modules and plugins -- BEGIN

// -- General settings
jimport('joomla.installer.installer');
$db = & JFactory::getDBO();
$status = new JObject();
$status->modules = array();
$status->plugins = array();
if( version_compare( JVERSION, '1.6.0', 'ge' ) ) {
	// Thank you for removing installer features in Joomla! 1.6 Beta 13 and
	// forcing me to write ugly code, Joomla!...
	$src = dirname(__FILE__);
} else {
	$src = $this->parent->getPath('source');
}

$status->libraries = array();
$status->modules = array();
$status->plugins = array();

if(is_dir($src.'/libraries')) {
	$libraries = JFolder::folders($src.'/libraries', '.', false, false);
	foreach ($libraries as $library) {
		$installer = new JInstaller;
		$result = $installer->install($src.'/libraries/'.$library);
		$status->libraries[] = array('name'=>$library,'result'=>$result);
	}
}
if(is_dir($src.'/modules')) {
	$modules = JFolder::folders($src.'/modules', '.', false, false);
	foreach ($modules as $module) {
		$installer = new JInstaller;
		$result = $installer->install($src.'/modules/'.$module);
		$status->modules[] = array('name'=>$module,'result'=>$result);
	}
}
if(is_dir($src.'/plugins')) {
	$plugins = JFolder::folders($src.'/plugins', '.', false, false);
	foreach ($plugins as $plugin) {
		$installer = new JInstaller;
		$result = $installer->install($src.'/plugins/'.$plugin);
		$status->plugins[] = array('name'=>$plugin,'result'=>$result);
	}
}

// Install modules and plugins -- END

// Finally, show the installation results form
?>
<h1>WBTY Shop - Product Component & Module</h1>

<h2>Welcome!</h2>

<p>Thank you for installing WBTY Shop. This installation includes the component and module.</p>

<h3>Libraries</h3>
<?php foreach ($status->libraries as $library) : ?>

    <p><?php echo $library['name']; ?> - <?php echo ($library['result'])?JText::_('Installed'):JText::_('Not installed'); ?></p>

<?php endforeach;?>
<h3>Modules</h3>
<?php foreach ($status->modules as $module) : ?>

    <p><?php echo $module['name']; ?> - <?php echo ucfirst($module['client']); ?> - <?php echo ($module['result'])?JText::_('Installed'):JText::_('Not installed'); ?></p>

<?php endforeach;?>
<h3>Plugins</h3>
<?php foreach ($status->plugins as $plugin) : ?>

    <p><?php echo $plugin['name']; ?> - <?php echo ucfirst($plugin['client']); ?> - <?php echo ($plugin['result'])?JText::_('Installed'):JText::_('Not installed'); ?></p>

<?php endforeach;?>