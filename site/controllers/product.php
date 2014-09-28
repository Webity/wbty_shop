<?php
/**
 * @version     0.2.0
 * @package     com_wbty_shop
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Webity <info@makethewebwork.com> - http://www.makethewebwork.com
 */

// No direct access
defined('_JEXEC') or die;

jimport('wbty_components.controllers.wbtycontrollerform');

/**
 * product controller class.
 */
class Wbty_shopControllerProduct extends WbtyControllerForm
{
	protected $view_list = 'products';
    protected $view_form = 'product';
    protected $com_name = 'wbty_shop';

    function __construct() {
        parent::__construct();
		
		$this->_model = $this->getModel();
    }

    function add2cart() {
		$app = JFactory::getApplication();
		$jform = $app->input->get('jform', array(), 'ARRAY');
		
		$url = $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : 'index.php?option=com_wbty_shop';
		
		jimport('wbtypayments.wbtypayments');
		
		if (!class_exists('WbtyPayments')) {
			$app->enqueueMessage('Add to cart requires Webity Payments. Please let the webmaster know that Webity Payments is not found.');
			$app->redirect($url);
		}
		
		$product_model = $this->getModel('product');
		$product_model->getState();
		$product_model->setState('product.id', $jform['product']);
		$product = $product_model->getItem();
		
		require_once(JPATH_BASE . '/components/com_wbty_pricing/helpers/wbty_pricing.php');
		$price_set = Wbty_pricingHelper::getPricingSet($product->pricing_set);
		
		$price = $price_set->base_price;
		$description = $price_set->name;
		foreach ($jform['product'] as $option=>$item) {
			foreach($price_set->options[$option] as $opt) {
				if ($opt->id == $item) {
					$price += $opt->price_change;
					$description .= ', ' . $opt->title;
					break;
				}
			}
		}
				
		$order_info = array(
						'amount' => $price,
						'item_name' => $product->name,
						'item_desc' => $description,
						'item_id' => $product->id,
						'callback_file' => '',
						'callback_function' => '',
						'callback_id' => 0,
						'redirect_url' => '',
						'redirect_text' => ''
						);
		
		if (WbtyPayments::createOrder($order_info)) {
			$url = WbtyPayments::getCheckoutUrl();
		} else {
			$app->enqueueMessage('Error Purchasing Image...');
		}
		
		$app->redirect($url);
		exit();
	}
	
	function back() {
		$this->setRedirect(
			JRoute::_(
				'index.php?option=' . $this->option . '&view=' . $this->view_list
				. $this->getRedirectToListAppend(), false
			)
		);
	}
	
	
	
}