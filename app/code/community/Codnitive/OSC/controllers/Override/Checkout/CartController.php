<?php
/**
 * CODNITIVE
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE_EULA.html.
 * It is also available through the world-wide-web at this URL:
 * http://www.codnitive.com/en/terms-of-service-softwares/
 * http://www.codnitive.com/fa/terms-of-service-softwares/
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade to newer
 * versions in the future.
 *
 * @category   Codnitive
 * @package    Codnitive_OSC
 * @author     Hassan Barza <support@codnitive.com>
 * @copyright  Copyright (c) 2014 CODNITIVE Co. (http://www.codnitive.com)
 * @license    http://www.codnitive.com/en/terms-of-service-softwares/ End User License Agreement (EULA 1.0)
 */

/**
 * Shopping cart controller
 */

require_once 'Mage/Checkout/controllers/CartController.php';
class Codnitive_OSC_Override_Checkout_CartController extends Mage_Checkout_CartController
{
	private function _getConfig()
	{
		return Mage::getModel('osc/config');
	}

	protected function _getCustomerSession()
	{
		return Mage::getSingleton('customer/session');
	}

	/**
	 * Shopping cart display action
	 */
	public function indexAction()
	{
		if ($this->_getConfig()->removeCart()) {
			$this->_goBack();
			return;
		}
		parent::indexAction();
	}

	/**
	 * Add product to shopping cart action
	 */
	public function addAction()
	{
		$condition = $this->_getConfig()->getShowContinueBtn()
			&& isset($_SERVER['HTTP_REFERER'])
			&& !empty($_SERVER['HTTP_REFERER']);
		if ($condition) {
			Mage::getSingleton('customer/session')->setContinueShoppingUrl($_SERVER['HTTP_REFERER']);
		}

		parent::addAction();
	}

	/**
	 * Delete shoping cart item action
	 */
	public function deleteAction()
	{
		if (!$this->_getConfig()->getShowItemRemoveBtn()) {
			parent::deleteAction();
			return;
		}

		$id = (int) $this->getRequest()->getParam('id');
		if ($id) {
			try {
				$this->_getCart()->removeItem($id)
				  ->save();
			} catch (Exception $e) {
				$this->_getSession()->addError($this->__('Cannot remove the item.'));
				Mage::logException($e);
			}
		}
		$this->_goBack();
	}

	/**
	 * Set back redirect url to response
	 *
	 * @return Mage_Checkout_CartController
	 */
	protected function _goBack()
	{
		$returnUrl = $this->getRequest()->getParam('return_url');
		if ($returnUrl) {

			if (!$this->_isUrlInternal($returnUrl)) {
				throw new Mage_Exception('External urls redirect to "' . $returnUrl . '" denied!');
			}

			$this->_getSession()->getMessages(true);
			$this->getResponse()->setRedirect($returnUrl);
		} elseif (!Mage::getStoreConfig('checkout/cart/redirect_to_cart')
			&& !$this->getRequest()->getParam('in_cart')
			&& $backUrl = $this->_getRefererUrl()
		) {
			$this->getResponse()->setRedirect($backUrl);
		} else {
			if (($this->getRequest()->getActionName() == 'add') && !$this->getRequest()->getParam('in_cart')) {
				$this->_getSession()->setContinueShoppingUrl($this->_getRefererUrl());
			}
			if ($this->_getConfig()->removeCart()/* && $this->_getCart()->getItemsCount()*/) {
//                $this->_redirect('onestepcheckout/checkout');
				if (!$this->_getCustomerSession()->isLoggedIn() && $this->_getConfig()->separateLogin()) {
					$this->_redirect('checkout/onepage/login');
				}
				else {
					$this->_redirect('checkout/onepage');
				}
				return;
			}
			$this->_redirect('checkout/cart');
		}
		return $this;
	}
}
