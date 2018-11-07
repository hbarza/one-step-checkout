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
 * Onestep chekcout controller
 */

require_once 'Mage/Checkout/controllers/OnepageController.php';
class Codnitive_OSC_Checkout_CheckoutController extends Mage_Checkout_OnepageController
{
	/*public function _contruct()
	{
		parent::_construct();
		$this->setTemplate('codnitive/osc/onestep.phtml');
	}*/

	public function indexAction()
	{
		var_dump('hello');
	}
}
