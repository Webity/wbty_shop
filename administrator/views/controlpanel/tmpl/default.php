<?php
/**
 * @version     1.0.0
 * @package     com_fsprospects
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Created by com_combuilder - http://www.notwebdesign.com
 */

// no direct access
defined('_JEXEC') or die;
?>

<div class="cpanel">
  <h2>Main Tasks</h2>
    <div class="icon-wrapper">
        
        <div class="btn cpanel-btn">
          <a href="index.php?option=com_wbty_shop&view=products"><img src="<?php echo JURI::root(); ?>media/wbty_shop/img/products.png" alt=""><span>Products</span></a>
        </div>
        <div class="btn cpanel-btn">
          <a href="index.php?option=com_categories&extension=com_wbty_shop"><img src="<?php echo JURI::root(); ?>media/wbty_shop/img/categories.png" alt=""><span>Categories</span></a>
        </div>
        <?php 
          $params = JComponentHelper::getParams('com_wbty_shop');
          if ($params->get('pricing')) :
          ?>
            <div class="btn cpanel-btn">
                <a href="index.php?option=com_wbty_pricing&extension=com_wbty_shop"><img src="<?php echo JURI::root(); ?>media/wbty_shop/img/price.png" alt="" width="48"><span>Pricing Sets</span></a>
            </div>
          <?php endif; ?>
        <div class="clr"></div>
    </div>
    <!--<h2 style="clear:left;">Configuration / Settings</h2>
    <div class="icon-wrapper">
        
        <div class="clr"></div>
    </div>-->
</div>