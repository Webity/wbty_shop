<?php
/**
 * @version     0.2.0
 * @package     com_wbty_shop
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Webity <info@makethewebwork.com> - http://www.makethewebwork.com
 */


// no direct access
defined('_JEXEC') or die;

$jversion = new JVersion();
$above3 = version_compare($jversion->getShortVersion(), '3.0', 'ge');
if (!$above3) {
	JFactory::getDocument()->addScriptDeclaration('
jQuery(document).ready(function($) {
	$("[title]").tooltip();
});
		');
} else {
	JHtml::_('bootstrap.tooltip');
}

JHTML::_('script','system/multiselect.js',false,true);

$user	= JFactory::getUser();
$userId	= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$canOrder	= $user->authorise('core.edit.state', 'com_wbty_shop');
$saveOrder	= $listOrder == 'a.ordering';

ob_start();
?>
jQuery(document).ready(function($) {
	$('.state-filter').click(function() {
		if ($(this).hasClass('active')) {return;}

		value = 1;
		if ($(this).hasClass('trashed')) {value = -2;}

		$('#adminForm').append('<input type="hidden" name="filter_published" value="'+ value +'" />').submit();
	});
});
<?php 
$script = ob_get_contents();
ob_end_clean();
JFactory::getDocument()->addScriptDeclaration($script);
?>

<form action="<?php echo JRoute::_('index.php?option=com_wbty_shop&view=products'); ?>" method="post" name="adminForm" id="adminForm" class="form-inline">
	<div class="state-filters">
		<div class="state-filter published<?php if ($this->state->get('filter.state') != -2) { echo ' active'; } ?>"><?php echo JText::_('JPUBLISHED'); ?></div>
		<div class="state-filter trashed<?php if ($this->state->get('filter.state') == -2) { echo ' active'; } ?>"><?php echo JText::_('JTRASHED'); ?></div>
	</div>
	<div class="clr"></div>
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('Search'); ?>" />
			<div class="btn-group">
				<button class="btn" type="submit"><i class="icon-search"></i></button>
				<button class="btn" type="button" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
			</div>
		</div>
		<div class="filter-select fltrt">
            	<select name="filter_category" class="inputbox" id="filter_category" onchange="this.form.submit()">
                    <option value="">- Select Category -</option>
                   <?php echo JHtml::_('select.options', JHtml::_('category.options', 'com_wbty_shop', array('filter.published' => 1)), 'value', 'text', $this->state->get('filter.category'));?>
                </select>

	        <select name="filter_published" class="inputbox" onchange="this.form.submit()">
	            <option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
	            <?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), "value", "text", $this->state->get('filter.state'), true);?>
	        </select>
		</div>
	</fieldset>
	<div class="clr"></div>

	<table class="adminlist table table-striped">
		<thead>
			<tr>
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
				</th>
                
                <th width="5%">
                	<?php if (isset($this->items[0]->state)) { ?>
						<?php echo JHtml::_('grid.sort',  'JPUBLISHED', 'a.state', $listDirn, $listOrder); ?>
					<?php } ?>
                </th>
                
                <th>
                    <?php echo JHtml::_('grid.sort',  'COM_WBTY_SHOP_FORM_LBL_PRODUCTS_IMAGE', 'a.image', $listDirn, $listOrder); ?>
                </th>
                
                <th>
                    <?php echo JHtml::_('grid.sort',  'COM_WBTY_SHOP_FORM_LBL_PRODUCTS_NAME', 'a.name', $listDirn, $listOrder); ?>
                </th>
                
                <th>
                    <?php echo JHtml::_('grid.sort',  'COM_WBTY_SHOP_FORM_LBL_PRODUCTS_DESCRIPTION', 'a.description', $listDirn, $listOrder); ?>
                </th>
                
                <th>
                    <?php echo JHtml::_('grid.sort',  'COM_WBTY_SHOP_FORM_LBL_PRODUCTS_CATEGORY', 'a.category', $listDirn, $listOrder); ?>
                </th>
                
                <th>
                    <?php echo JHtml::_('grid.sort',  'COM_WBTY_SHOP_FORM_LBL_PRODUCTS_PRICING_SET', 'a.pricing_set', $listDirn, $listOrder); ?>
                </th>
					
                <?php } ?>
                <?php if (isset($this->items[0]->ordering)) { ?>
				<th width="10%">
					<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ORDERING', 'a.ordering', $listDirn, $listOrder); ?>
					<?php if ($canOrder && $saveOrder) :?>
						<?php echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'products.saveorder'); ?>
					<?php endif; ?>
				</th>
                <?php } ?>
                <?php if (isset($this->items[0]->id)) { ?>
                <th width="1%" class="nowrap">
                    <?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
                </th>
                <?php } ?>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($this->items as $i => $item) :
			$ordering	= ($listOrder == 'a.ordering');
			$canCreate	= $user->authorise('core.create',		'com_wbty_shop');
			$canEdit	= $user->authorise('core.edit',			'com_wbty_shop');
			$canCheckin	= $user->authorise('core.manage',		'com_wbty_shop');
			$canChange	= $user->authorise('core.edit.state',	'com_wbty_shop');
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
                
                <td class="center">
                	<div class="btn-group">
                	<?php if (isset($this->items[0]->state)) { ?>
					    <?php echo JHtml::_('jgrid.published', $item->state, $i, 'products.', $canChange, 'cb'); ?>
                	<?php } ?>
                    <button data-toggle="dropdown" class="dropdown-toggle btn btn-micro">
                    	<span class="caret"></span>
                        <span class="element-invisible">Actions</span>
                    </button>
                      <ul class="dropdown-menu">
                        <?php
                        echo '<li><a href="index.php?option=com_wbty_shop&view=product&layout=default&id='.$item->id.'">View</a></li>';
						if ($canEdit && $this->state->get('filter.state') != -2) {
	                        echo '<li><a href="index.php?option=com_wbty_shop&task=product.edit&id='.$item->id.'">Edit</a></li>';
						}
                        ?>
                        
                      </ul>
                    </div>
                </td>
        
                <td>
                    <?php if (isset($item->checked_out) && $item->checked_out && (JDate::getInstance()->toUnix() - JDate::getInstance($item->checked_out_time)->toUnix()) < 120 ) : ?>

                        <span class="hasTip" title="Item is currently being edited by Super User"><i class="icon-lock"></i></span>
                    <?php endif; ?>
                    <?php echo $this->escape($item->image); ?>
                </td>
                
                <td>
                    <?php echo $item->name; ?>
                </td>
                
                <td>
                    <?php echo $item->description; ?>
                </td>
                
                <td>
                    <?php echo $item->category; ?>
                </td>
                
                <td>
                    <?php echo $item->pricing_set; ?>
                </td>

                <?php if (isset($this->items[0]->ordering)) { ?>
				    <td class="order">
					    <?php if ($canChange) : ?>
						    <?php if ($saveOrder) :?>
							    <?php if ($listDirn == 'asc') : ?>
								    <span><?php echo $this->pagination->orderUpIcon($i, true, 'products.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
								    <span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'products.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
							    <?php elseif ($listDirn == 'desc') : ?>
								    <span><?php echo $this->pagination->orderUpIcon($i, true, 'products.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
								    <span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'products.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
							    <?php endif; ?>
						    <?php endif; ?>
						    <?php $disabled = $saveOrder ?  '' : 'disabled="disabled"'; ?>
						    <input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" <?php echo $disabled ?> class="text-area-order" />
					    <?php else : ?>
						    <?php echo $item->ordering; ?>
					    <?php endif; ?>
				    </td>
                <?php } ?>
                <?php if (isset($this->items[0]->id)) { ?>
				<td class="center">
					<?php echo (int) $item->id; ?>
				</td>
                <?php } ?>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<div class="pagination pagination-toolbar">
		<?php echo $this->pagination->getListFooter(); ?>
	</div>


	<?php echo $this->loadTemplate('batch'); ?>

	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>