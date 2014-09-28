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

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

$document = &JFactory::getDocument();
JHTML::script("wbty_components/linked_tables.js", false, true);
JHTML::script("wbty_shop/edit.js", false, true);

$params = JComponentHelper::getParams('com_wbty_shop');

ob_start();
// start javascript output -- script
?>
window.addEvent('domready', function(){
    // save validator, getting overwritten by AJAX call
    document.productvalidator = document.formvalidator;
    jQuery('#product-form .toolbar-list a').each(function() {
        $(this).attr('data-onclick', $(this).attr('onclick')).attr('onclick','');
    });
    jQuery('#product-form .toolbar-list a').click(function() { 
        Joomla.submitbutton = document.productsubmitbutton;
        
        // clean up hidden subtables
        jQuery('.subtables:hidden').remove();
        
        eval($(this).attr('data-onclick'));
    });
});

window.juri_root = '<?php echo JURI::root(); ?>';
window.juri_base = '<?php echo JURI::base(); ?>';

Joomla.submitbutton = function(task)
{
    if (jQuery('#sbox-window').attr('aria-hidden')==true) {
        Joomla.submitform = defaultsubmitform;
    }
    
    if (task == 'product.cancel' || document.productvalidator.isValid(document.id('product-form'))) {
        Joomla.submitform(task, document.getElementById('product-form'));
    }
    else {
        alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
    }
}
document.productsubmitbutton = Joomla.submitbutton;
<?php
// end javascript output -- /script
$script=ob_get_contents();
ob_end_clean();
$document->addScriptDeclaration($script);
?>

<?php echo JHTML::_('wbty_shopHelper.buildEditForm', $this->form); ?>

<form action="<?php echo JRoute::_('index.php?option=com_wbty_shop&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="product-form" class="form-validate form-horizontal">
    <fieldset class="parentform" data-controller="product" data-task="product.ajax_save">
    <div class="row-fluid">
        <div class="span7">
        <fieldset class="adminform">
            <legend>Item</legend>

            <?php JHtml::_('wbty.renderField', $this->form->getField('id', 'product')); ?>


            <?php JHtml::_('wbty.renderField', $this->form->getField('name', 'product')); ?>


            <?php JHtml::_('wbty.renderField', $this->form->getField('category', 'product')); ?>


            <?php JHtml::_('wbty.renderField', $this->form->getField('state', 'product')); ?>

        </fieldset>
        
        <fieldset id="content_set" class="subset adminform">
            <legend>Content</legend>
            
            <?php JHtml::_('wbty.renderField', $this->form->getField('description', 'product')); ?>
            
        </fieldset>
        
        <?php // fieldset for each linked table  ?>
        
        
    </div>
    
    <div class="span5">
        
        <?php if ($params->get('images')) : ?>
        <fieldset id="image_set" class="subset adminform">
            <legend>Image</legend>
            <?php
                if (file_exists(JPATH_ADMINISTRATOR . '/components/com_wbty_gallery/helpers/wbty_gallery.php') && method_exists('Wbty_galleryHelper', 'getGalleryBuilder')) {
                    require_once(JPATH_ADMINISTRATOR . '/components/com_wbty_gallery/helpers/wbty_gallery.php');
                    echo Wbty_galleryHelper::getGalleryBuilder('com_wbty_shop', $this->form->getField('image', 'product')->__get('value'), $this->form->getField('id', 'product')->__get('value'));
                } else {
                    JHtml::_('wbty.renderField', $this->form->getField('image', 'product'));
                    JHtml::_('wbty.renderField', $this->form->getField('caption', 'product'));
                    JHtml::_('wbty.renderField', $this->form->getField('menu_link', 'product'));
                }
            ?>
        </fieldset>
       <?php endif; ?>
        
       <?php if ($params->get('pricing')) : ?>
        <fieldset class="adminform">
            <legend>Pricing</legend>
            <?php JHtml::_('wbty.renderField', $this->form->getField('pricing_set', 'product')); ?>
        </fieldset>
       <?php endif; ?>
        
       <?php if ($params->get('custom_fields')) : ?>
        <fieldset class="adminform">
            <legend>Additional Fields</legend>
            <?php
            if (file_exists(JPATH_ROOT . '/components/com_wbty_custom_fields/helpers/loader.php')) {
                require_once(JPATH_ROOT . '/components/com_wbty_custom_fields/helpers/loader.php');
                echo Wbty_custom_fieldsHelperLoader::loadFormFields('com_wbty_shop', 'products', $this->form->getValue('id', 'product'), true);
            }
            ?>
        </fieldset>
       <?php endif; ?>
       
    </div>
    </div>

    <div class="control-group"> 
        <div class="controls">
            <span class="btn btn-success save-primary"><i class="icon-ok"></i> Save Product Info</span>
        </div>
    </div>
</fieldset>
	
    
	<input type="hidden" name="task" value="" />
    <input type="hidden" name="option" id="option" value="com_wbty_shop" />
    <input type="hidden" name="form_name" id="form_name" value="product" />
	<?php echo JHtml::_('form.token'); ?>
	<div class="clr"></div>
</form>