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
 * One page checkout order review
 *
 * @category   Codnitive
 * @package    Codnitive_OSC
 * @author     Hassan Barza <support@codnitive.com>
 */
class Codnitive_OSC_Block_Checkout_Onepage_Review_Info extends Mage_Checkout_Block_Onepage_Review_Info
{

	public function getContinueShoppingUrl()
	{
		$url = Mage::getSingleton('customer/session')->getContinueShoppingUrl();
		if (is_null($url)) {
			$url = $this->getData('continue_shopping_url');
			if (!$url) {
				$url = Mage::getUrl();
			}
			$this->setData('continue_shopping_url', $url);
		}
		return $url;
	}
}
