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

class Codnitive_OSC_Model_System_Config_Backend_Multiselectlimit extends Mage_Core_Model_Config_Data
{

	protected function _beforeSave()
	{
		try{
			$value = $this->getValue();
			$fieldConf = $this->getFieldConfig();
			$count = in_array('name', $value) || in_array('dob', $value) ? 1 : 2;
			if (count($value) > $count) {
				Mage::throwException(Mage::helper('osc')->__('You selected more than %d options for %s.',
						$count,
						Mage::helper('osc')->__((string)$fieldConf->label)));
			}
		}
		catch (Exception $e) {
			Mage::throwException($e->getMessage());
			return $this;
		}

		return $this;
	}

}
