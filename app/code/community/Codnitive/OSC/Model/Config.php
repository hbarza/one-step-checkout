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
 * @copyright  Copyright (c) 2012 CODNITIVE Co. (http://www.codnitive.com)
 * @license    http://www.codnitive.com/en/terms-of-service-softwares/ End User License Agreement (EULA 1.0)
 */

class Codnitive_OSC_Model_Config
{

	const PATH_NAMESPACE            = 'codnitiveosc';
	const EXTENSION_NAMESPACE       = 'osc';
	const BILLING_NAMESPACE         = 'billing';
	const SHIPPING_NAMESPACE        = 'shipping';
	const SHIPPING_METHOD_NAMESPACE = 'shipping_method';
	const PAYMENT_NAMESPACE         = 'payment';
	const REVIEW_NAMESPACE          = 'review';

	const EXTENSION_NAME    = 'One Step Checkout';
	const EXTENSION_VERSION = '1.0.04';
	const EXTENSION_EDITION = 'Basic';

	public static function getNamespace()
	{
		return self::PATH_NAMESPACE . '/' . self::EXTENSION_NAMESPACE . '/';
	}

	public function getExtensionName()
	{
		return self::EXTENSION_NAME;
	}

	public function getExtensionVersion()
	{
		return self::EXTENSION_VERSION;
	}

	public function getExtensionEdition()
	{
		return self::EXTENSION_EDITION;
	}

	public static function getBillingNamespace()
	{
		return self::PATH_NAMESPACE . '/' . self::BILLING_NAMESPACE . '/';
	}

	public static function getShippingNamespace()
	{
		return self::PATH_NAMESPACE . '/' . self::SHIPPING_NAMESPACE . '/';
	}

	public static function getShippingMethodNamespace()
	{
		return self::PATH_NAMESPACE . '/' . self::SHIPPING_METHOD_NAMESPACE . '/';
	}

	public static function getPaymentNamespace()
	{
		return self::PATH_NAMESPACE . '/' . self::PAYMENT_NAMESPACE . '/';
	}

	public static function getReviewNamespace()
	{
		return self::PATH_NAMESPACE . '/' . self::REVIEW_NAMESPACE . '/';
	}

	/* Base Settings ==================================== */
	public function isActive()
	{
		return Mage::getStoreConfigFlag(self::getNamespace() . 'active');
	}

	public function removeCart()
	{
		if (!$this->isActive()) {
			return false;
		}
		return Mage::getStoreConfigFlag(self::getNamespace() . 'remove_cart');
	}

	public function separateLogin()
	{
		if (!$this->isActive()) {
			return false;
		}
		return Mage::getStoreConfigFlag(self::getNamespace() . 'separate_login');
	}

	public function getShowCouponCode()
	{
		if (!$this->isActive()) {
			return false;
		}
		return Mage::getStoreConfigFlag(self::getNamespace() . 'show_coupon');
	}

	public function getFormFields($section, $option, $array = true)
	{
		switch ($section) {
			case 'billing':
				$namespace = self::getBillingNamespace();
				break;

			case 'shipping':
				$namespace = self::getShippingNamespace();
				break;

			default:
				$namespace = self::getNamespace();
		}

		$value = Mage::getStoreConfig($namespace . $option);
		if (empty($value)) {
			return false;
		}
		return $array ? explode(',', $value) : $value;
	}

	/* Billing Settings ================================= */
	public function getBillingFormFields($option, $array = true)
	{
		return $this->getFormFields('billing', $option, $array);
	}

	/*public function getBillingStreetLines()
	{
		return Mage::getStoreConfig(self::getBillingNamespace() . 'street_lines');
	}*/

	/* Shipping Settings ================================ */
	public function removeShippingAddress()
	{
		if (!$this->isActive()) {
			return false;
		}
		return Mage::getStoreconfigFlag(self::getShippingNamespace() . 'remove_shipping');
	}

	public function getShippingFormFields($option, $array = true)
	{
		return $this->getFormFields('shipping', $option, $array);
	}

	/* Shipping Method Settings ========================= */
	public function removeShippingMethod()
	{
		if (!$this->isActive()) {
			return false;
		}
		$remove = Mage::getStoreconfigFlag(self::getShippingMethodNamespace() . 'remove_shipping');
		$check  = (!$this->getShippingMethodInstead() || $this->getShippingMethodInstead() == 'none') && $remove;
		if ($check) {
			return false;
		}
		return $remove;
	}

	public function getDefaultShippingMethod()
	{
//		if (!$this->removeShippingMethod()) {
//			return false;
//		}
		return Mage::getStoreConfig(self::getShippingMethodNamespace() . 'default_shipping');
	}

	public function getShippingMethodInstead()
	{
//		if (!$this->removeShippingMethod()) {
//			return false;
//		}
		return Mage::getStoreConfig(self::getShippingMethodNamespace() . 'shipping_method_instead');
	}

	/* Payment Settings ================================= */
	public function removePayment()
	{
		if (!$this->isActive()) {
			return false;
		}
		$remove = Mage::getStoreconfigFlag(self::getPaymentNamespace() . 'remove_payment');
		$check  = (!$this->getPaymentInstead() || $this->getPaymentInstead() == 'none') && $remove;
		if ($check) {
			return false;
		}
		return $remove;
	}

	public function getDefaultPaymentMethod()
	{
//		if (!$this->removePayment()) {
//			return false;
//		}
		return Mage::getStoreConfig(self::getPaymentNamespace() . 'default_payment');
	}

	public function getPaymentInstead()
	{
//		if (!$this->removePayment()) {
//			return false;
//		}
		return Mage::getStoreConfig(self::getPaymentNamespace() . 'payment_instead');
	}

	/* Review Settings ================================= */
	public function getEditableItemQty()
	{
		return Mage::getStoreConfigFlag(self::getReviewNamespace() . 'editable_qty');
	}

	public function getShowItemRemoveBtn()
	{
		return Mage::getStoreConfigFlag(self::getReviewNamespace() . 'item_remove_btn');
	}

	public function getShowItemEditBtn()
	{
		return Mage::getStoreConfigFlag(self::getReviewNamespace() . 'item_edit_btn');
	}

	public function getShowMoveWishlistBtn()
	{
		return Mage::getStoreConfigFlag(self::getReviewNamespace() . 'move_wishlist_btn');
	}

	public function getShowContinueBtn()
	{
		return Mage::getStoreConfigFlag(self::getReviewNamespace() . 'show_continue_btn');
	}

	public function getShowUpdateBtn()
	{
		return Mage::getStoreConfigFlag(self::getReviewNamespace() . 'show_update_btn');
	}

	public function getShowClearBtn()
	{
		return Mage::getStoreConfigFlag(self::getReviewNamespace() . 'show_clear_btn');
	}

}
