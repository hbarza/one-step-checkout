<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Checkout
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


require_once 'Mage/Checkout/controllers/OnepageController.php';
class Codnitive_OSC_Override_Checkout_OnepageController extends Mage_Checkout_OnepageController
{
	private function _getConfig()
	{
		return Mage::getModel('osc/config');
	}

	protected function _getCoreSession()
	{
		return Mage::getSingleton('core/session');
	}

	protected function _getCheckoutSession()
	{
		return Mage::getSingleton('checkout/session');
	}

	protected function _getCustomerSession()
	{
		return Mage::getSingleton('customer/session');
	}

	protected function _getCart()
	{
		return Mage::getSingleton('checkout/cart');
	}

	/**
	 * Get one step page checkout model
	 *
	 * @return Codnitive_OSC_Model_Type_Onestep
	 */
	public function getOnestep()
	{
		return Mage::getSingleton('osc/type_onestep');
	}

	public function loginAction()
	{
		if ($this->_getCustomerSession()->isLoggedIn()) {
			$this->_redirect('checkout/onepage');
			return;
		}

		$this->loadLayout();
		$this->_initLayoutMessages('customer/session');
		$this->getLayout()->getBlock('head')->setTitle($this->__('Login'));
		$this->renderLayout();
	}

//	protected function _ajaxExpireRedirect()
//	{
//		if (!$this->getOnepage()->getQuote()->hasItems()
//			|| $this->getOnepage()->getQuote()->getHasError()
//			|| $this->getOnepage()->getQuote()->getIsMultiShipping()) {
////			$this->_ajaxRedirectResponse();
//			return true;
//		}
//		$action = $this->getRequest()->getActionName();
//		if ($this->_getCheckoutSession()->getCartWasUpdated(true)
//			&& !in_array($action, array('index', 'progress'))) {
////			$this->_ajaxRedirectResponse();
//			return true;
//		}
//
//		return false;
//	}

	/**
	 * Checkout page
	 */
	public function indexAction()
	{
		if (!$this->_getConfig()->isActive()) {
			return parent::indexAction();
		}

		if ($this->_expireAjax()) {
			$this->getResponse()->setRedirect(Mage::getBaseUrl());
			return;
		}

		if (!Mage::helper('checkout')->canOnepageCheckout()) {
			Mage::getSingleton('checkout/session')->addError($this->__('The onepage checkout is disabled.'));
			if($this->_getConfig()->removeCart()) {
				$this->getResponse()->setRedirect($this->_getRefererUrl());
			}
			else {
				$this->_redirect('checkout/cart');
			}
			return;
		}
		$quote = $this->getOnepage()->getQuote();
		if (!$quote->hasItems() || $quote->getHasError()) {
			if($this->_getConfig()->removeCart()) {
				$this->getResponse()->setRedirect($this->_getRefererUrl());
			}
			else {
				$this->_redirect('checkout/cart');
			}
			return;
		}
		if (!$quote->validateMinimumAmount()) {
			$error = Mage::getStoreConfig('sales/minimum_order/error_message') ?
				Mage::getStoreConfig('sales/minimum_order/error_message') :
				Mage::helper('osc')->__('Subtotal must exceed minimum order amount');

			Mage::getSingleton('checkout/session')->addError($error);
			if($this->_getConfig()->removeCart()) {
				$this->getResponse()->setRedirect($this->_getRefererUrl());
			}
			else {
				$this->_redirect('checkout/cart');
			}
			return;
		}
		Mage::getSingleton('checkout/session')->setCartWasUpdated(false);
		Mage::getSingleton('customer/session')->setBeforeAuthUrl(Mage::getUrl('*/*/*', array('_secure'=>true)));
		$this->getOnepage()->initCheckout();
		$this->loadLayout();
		$this->_initLayoutMessages('customer/session');
		$this->getLayout()->getBlock('head')->setTitle($this->__('Checkout'));
		$this->renderLayout();
	}

	public function getPaymentMethodsAction()
	{
		if ($this->_expireAjax()) {
			return;
		}
//			if (!$this->getRequest()->isPost()) {
//				$this->_ajaxRedirectResponse();
//				return;
//			}
		if ($this->getRequest()->isPost()) {
//            $postData = $this->getRequest()->getPost('billing', array());
//            $data = $this->_filterPostData($postData);
			$data = $this->getRequest()->getPost('billing', array());
			$customerAddressId = $this->getRequest()->getPost('billing_address_id', false);

			if (isset($data['email'])) {
				$data['email'] = trim($data['email']);
			}
			$result = $this->getOnestep()->getPaymentMethods($data, $customerAddressId);

//            if (!isset($result['error'])) {
				/* check quote for virtual */
//                if ($this->getOnepage()->getQuote()->isVirtual()) {
//                    $result['goto_section'] = 'payment';
					if ($this->_getConfig()->removePayment()) {
						$paymentHtml = '<input type="hidden" name="payment[method]" value="'.$this->_getConfig()->getPaymentInstead().'" checked="checked" />';
					}
					else {
						$paymentHtml = $this->_getPaymentMethodsHtml();
					}
					$result['update_section'] = array(
						'name' => 'payment-method',
						'html' => $paymentHtml
					);
//				} elseif (isset($data['use_for_shipping']) && $data['use_for_shipping'] == 1) {
//                    $result['goto_section'] = 'shipping_method';
					$result['shipping_methods_html'] = array(
						'name' => 'shipping-method',
						'html' => $this->_getShippingMethodsHtml()
					);

//                    $result['allow_sections'] = array('shipping');
				if (isset($data['use_for_shipping']) && $data['use_for_shipping'] == 1) {
					$result['duplicateBillingInfo'] = 'true';
				}/* else {
					$result['goto_section'] = 'shipping';
				}*/
//            }
//				$this->loadLayout('checkout_onepage_review');
//				$result['review_html'] = array(
//					'name' => 'review',
//					'html' => $this->_getReviewHtml()
//				);

			$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
		}
	}

//    public function getShippingMethodsAction()
//    {
//        if ($this->_expireAjax()) {
//            return;
//        }
//        if ($this->getRequest()->isPost()) {
//            /*if ($this->_getConfig()->removeShippingAddress()) {
//                $data = $this->getRequest()->getPost('billing', array());
//                $customerAddressId = $this->getRequest()->getPost('billing_address_id', false);
//            }
//            else {*/
//                $data = $this->getRequest()->getPost('shipping', array());
//                $customerAddressId = $this->getRequest()->getPost('shipping_address_id', false);
//            /*}*/
//
//            /*if (isset($data['email'])) {
//                $data['email'] = trim($data['email']);
//            }*/
//            if (!isset($result['error'])) {
//                $result['goto_section'] = 'shipping_method';
//                $result['update_section'] = array(
//                    'name' => 'shipping-method',
//                    'html' => $this->_getShippingMethodsHtml()
//                );
//            }
//
//            $result = $this->getOnestep()->getShippingMethods($data, $customerAddressId);
//            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
//        }
//    }
	public function getShippingMethodsAction()
	{
		if ($this->_expireAjax()) {
			return;
		}
//			if (!$this->getRequest()->isPost()) {
//				$this->_ajaxRedirectResponse();
//				return;
//			}
		if ($this->getRequest()->isPost()) {
//            if ($this->_getConfig()->removeShippingAddress()/* || $this->getRequest()->getPost('use_for_shipping')*/) {
//                //$data = $this->getRequest()->getPost('billing', array());
////                $customerAddressId = $this->getRequest()->getPost('billing_address_id', false);
//            }
//            else {
				$data = $this->getRequest()->getPost('shipping', array());
				$customerAddressId = $this->getRequest()->getPost('shipping_address_id', false);
//            }
			$result = $this->getOneStep()->getShippingMethods($data, $customerAddressId);

//            if (!isset($result['error'])) {
//                $result['goto_section'] = 'shipping_method';
				$result['update_section'] = array(
					'name' => 'shipping-method',
					'html' => $this->_getShippingMethodsHtml()
				);
//            }
			/*if (!isset($result['error'])) {
				$this->loadLayout('checkout_onepage_review');
				$result['goto_section'] = 'review';
				$result['update_section'] = array(
					'name' => 'review',
					'html' => $this->_getReviewHtml()
				);
			}*/
			$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
		}
	}

	public function saveShippingMethodAction()
	{
		if ($this->_expireAjax()) {
			return;
		}
//			if (!$this->getRequest()->isPost()) {
//				$this->_ajaxRedirectResponse();
//				return;
//			}
		if ($this->getRequest()->isPost()) {
			$data = $this->getRequest()->getPost('shipping_method', '');
			$result = $this->getOnepage()->saveShippingMethod($data);
			/*
			$result will have erro data if shipping method is empty
			*/
			if(!$result) {
				Mage::dispatchEvent('checkout_controller_onepage_save_shipping_method',
						array('request'=>$this->getRequest(),
							'quote'=>$this->getOnepage()->getQuote()));
//                $this->getOnepage()->getQuote()->collectTotals();
//                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));

				//$result['goto_section'] = 'payment';
//                $result['update_section'] = array(
//                    'name' => 'payment-method',
//                    'html' => $this->_getPaymentMethodsHtml()
//                );
				$data = $this->getRequest()->getPost('payment', array());
				$paymentResult = $this->getOnestep()->updatePayment($data);
				$this->getOnepage()->getQuote()->collectTotals()->save();

				$this->loadLayout('checkout_onepage_review');
				$result['review_html'] = array(
					'name' => 'review',
					'html' => $this->_getReviewHtml()
				);
			}
			else {
				$this->getOnepage()->getQuote()->collectTotals()->save();
			}
			$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
		}
	}

	public function updateReviewShippingAction()
	{
		if ($this->_expireAjax()) {
			return;
		}
//			if (!$this->getRequest()->isPost()) {
//				$this->_ajaxRedirectResponse();
//				return;
//			}
		if ($this->getRequest()->isPost()) {

			$data = $this->getRequest()->getPost('shipping_method', '');
			$result = $this->getOnestep()->updateShippingMethod($data);
			/*
			$result will have erro data if shipping method is empty
			*/
//            if(!$result) {
//                Mage::dispatchEvent('checkout_controller_onepage_save_shipping_method',
//                        array('request'=>$this->getRequest(),
//                            'quote'=>$this->getOnepage()->getQuote()));
//				$this->getOnepage()->getQuote()->collectTotals();
//				$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));

				$data = $this->getRequest()->getPost('payment', array());
				$paymentResult = $this->getOnestep()->updatePayment($data);

				$this->loadLayout('checkout_onepage_review');
//                $result['goto_section'] = 'review';
				$result['update_section'] = array(
					'name' => 'review',
					'html' => $this->_getReviewHtml()
				);
//            }
			$this->getOnepage()->getQuote()->collectTotals()->save();
			$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
		}
	}

	public function updateReviewPaymentAction()
	{
		if ($this->_expireAjax()) {
			return;
		}
//        try {
//			if (!$this->getRequest()->isPost()) {
//				$this->_ajaxRedirectResponse();
//				return;
//			}
		if ($this->getRequest()->isPost()) {

			// set payment to quote
//            $result = array();
			$data = $this->getRequest()->getPost('payment', array());
			$result = $this->getOnestep()->updatePayment($data);

			// get section and redirect data
//            $redirectUrl = $this->getOnepage()->getQuote()->getPayment()->getCheckoutRedirectUrl();
//            if (empty($result['error'])/* && !$redirectUrl*/) {
				$this->loadLayout('checkout_onepage_review');
//                $result['goto_section'] = 'review';
				$result['update_section'] = array(
					'name' => 'review',
					'html' => $this->_getReviewHtml()
				);
//            }
			//if ($redirectUrl) {
//                $result['redirect'] = $redirectUrl;
//            }
//        } catch (Mage_Payment_Exception $e) {
//            if ($e->getFields()) {
//                $result['fields'] = $e->getFields();
//            }
//            $result['error'] = $e->getMessage();
//        } catch (Mage_Core_Exception $e) {
//            $result['error'] = $e->getMessage();
//        } catch (Exception $e) {
//            Mage::logException($e);
//            $result['error'] = $this->__('Unable to set Payment Method.');
//        }
			$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
		}
	}

//    public function updateReviewAction()
//    {
//        if ($this->_expireAjax()) {
//            return;
//        }
//        try {
//            if (!$this->getRequest()->isPost()) {
//                $this->_ajaxRedirectResponse();
//                return;
//            }
//
//            // set payment to quote
//            $result = array();
//            $data = $this->getRequest()->getPost('payment', array());
//            $result = $this->getOnestep()->getReview($data);
//
//            // get section and redirect data
//            $redirectUrl = $this->getOnepage()->getQuote()->getPayment()->getCheckoutRedirectUrl();
//            if (empty($result['error']) && !$redirectUrl) {
//                $this->loadLayout('checkout_onepage_review');
//                $result['goto_section'] = 'review';
//                $result['update_section'] = array(
//                    'name' => 'review',
//                    'html' => $this->_getReviewHtml()
//                );
//            }
//            if ($redirectUrl) {
//                $result['redirect'] = $redirectUrl;
//            }
//        } catch (Mage_Payment_Exception $e) {
//            if ($e->getFields()) {
//                $result['fields'] = $e->getFields();
//            }
//            $result['error'] = $e->getMessage();
//        } catch (Mage_Core_Exception $e) {
//            $result['error'] = $e->getMessage();
//        } catch (Exception $e) {
//            Mage::logException($e);
//            $result['error'] = $this->__('Unable to set Payment Method.');
//        }
//        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
//    }
	/**
	* Customer login
	*
	*/
	public function loginPostAction()
	{
		if ($this->_getCustomerSession()->isLoggedIn()) {
			$this->_redirect('*/*/');
			return;
		}
		$session = $this->_getCustomerSession();

		if ($this->getRequest()->isPost()) {
			$login = $this->getRequest()->getPost('login');
			if (!empty($login['username']) && !empty($login['password'])) {
				try {
					$session->login($login['username'], $login['password']);
					//if ($session->getCustomer()->getIsJustConfirmed()) {
//						$this->_welcomeCustomer($session->getCustomer(), true);
//					}
				} catch (Mage_Core_Exception $e) {
					switch ($e->getCode()) {
						case Mage_Customer_Model_Customer::EXCEPTION_EMAIL_NOT_CONFIRMED:
							$value = Mage::helper('customer')->getEmailConfirmationUrl($login['username']);
							$message = Mage::helper('customer')->__('This account is not confirmed. <a href="%s">Click here</a> to resend confirmation email.', $value);
							break;
						case Mage_Customer_Model_Customer::EXCEPTION_INVALID_EMAIL_OR_PASSWORD:
							$message = $e->getMessage();
							break;
						default:
							$message = $e->getMessage();
					}
					$this->_getCheckoutSession()->addError($message);
					$this->_getCheckoutSession()->setUsername($login['username']);
				} catch (Exception $e) {
					// Mage::logException($e); // PA DSS violation: this exception log can disclose customer password
				}
			} else {
				$this->_getCheckoutSession()->addError($this->__('Login and password are required.'));
			}
		}

		$this->_redirect('*/*/');
	}

	/**
	 * Customer forgot password action
	 */
	public function forgotPasswordPostAction()
	{
		$email = (string) $this->getRequest()->getPost('email');
		if ($email) {
			if (!Zend_Validate::is($email, 'EmailAddress')) {
				$this->_getCustomerSession()->setForgottenEmail($email);
				$this->_getCheckoutSession()->addError($this->__('Invalid email address.'));
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
					$this->_getCheckoutSession()->addError($exception->getMessage());
					$this->_redirect('*/*/forgotpassword');
					return;
				}
			}
			$this->_getCheckoutSession()
				->addSuccess(Mage::helper('customer')->__('If there is an account associated with %s you will receive an email with a link to reset your password.', Mage::helper('customer')->htmlEscape($email)));
			$this->_redirect('*/*/');
			return;
		} else {
			$this->_getCheckoutSession()->addError($this->__('Please enter your email.'));
			$this->_redirect('*/*/forgotpassword');
			return;
		}
	}

	protected function _getRefererUrl()
	{
		$url = Mage::getBaseUrl();
		$sessionData = $this->_getCoreSession()->getData();
		if (isset($sessionData['visitor_data'])) {
			$url = $sessionData['visitor_data']['http_referer'];
		}
		return $url;
	}

//	protected function _getUpdateSections()
//	{
//		$this->loadLayout('checkout_onepage_review');
//		$sections = array (
//			'payment' => array(
//				'name' => 'payment-method',
//				'html' => $this->_getPaymentMethodsHtml()
//			),
//			'shipping' => array(
//				'name' => 'shipping-method',
//				'html' => $this->_getShippingMethodsHtml()
//			),
//			'review' => array(
//				'name' => 'review',
//				'html' => $this->_getReviewHtml()
//			)
//		);
//		return $sections;
//	}
}
