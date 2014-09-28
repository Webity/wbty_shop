<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_footer
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

if($image) : ?>
    <div class="wbty_shop">
		<a href="<?php echo JRoute::_('index.php?option=com_wbty_shop&view=products&id='.$image->category); ?>"><img src="<?php echo JURI::root(true).'/'.$image->image; ?>" style="max-width:100%;" /></a>
        <p><span class="italix"><?php echo $image->name; ?></span><br /><?php echo $image->title; ?></p>
    </div>
<?php endif; ?>