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

require_once 'Mage/Customer/controllers/AccountController.php';
class Codnitive_OSC_Override_Customer_AccountController extends Mage_Customer_AccountController
{
	public function forgotPasswordPostAction()
	{
		$email = (string) $this->getRequest()->getPost('email');
		if ($email) {
			if (!Zend_Validate::is($email, 'EmailAddress')) {
				$this->_getSession()->setForgottenEmail($email);
				$this->_getSession()->addError($this->__('Invalid email address.'));
				$this->_redirect('*/*/forgotpassword');
				return;
			}

			/** @var $customer Mage_Customer_Model_Customer */
			$customer = Mage::getModel('customer/customer')
				->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
				->loadByEmail($email);

			if ($customer->getId()) {
				try {
					$newResetPasswordLinkToken = Mage::helper('customer')->generateResetPasswordLinkToken();
					$customer->changeResetPasswordLinkToken($newResetPasswordLinkToken);
					$customer->sendPasswordResetConfirmationEmail();
				} catch (Exception $exception) {
					$this->_getSession()->addError($exception->getMessage());
					$this->_redirect('*/*/forgotpassword');
					return;
				}
			}
			$this->_getSession()
				->addSuccess(Mage::helper('customer')->__('If there is an account associated with %s you will receive an email with a link to reset your password.', Mage::helper('customer')->htmlEscape($email)));
			$this->_redirect('*/*/');
			return;
		} else {
			$this->_getSession()->addError($this->__('Please enter your email.'));
			$this->_redirect('*/*/forgotpassword');
			return;
		}
	}
}
