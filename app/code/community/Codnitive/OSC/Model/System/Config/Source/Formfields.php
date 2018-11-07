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

class Codnitive_OSC_Model_System_Config_Source_Formfields extends Mage_Core_Model_Config_Data
{
	protected $_options = array(
		'none'      => 'None',
		'prefix'    => 'Prefix',
		'firstname' => 'First Name',
		'middlename' => 'Middle Name (initial)',
		'lastname'  => 'Last Name',
		'suffix'    => 'Suffix',
//		'company'   => 'Company',
//		'email'     => 'Email Address',
//		'address'   => 'Address',
//		'country'   => 'Country',
//		'region'    => 'State/Province',
//		'city'      => 'City',
//		'postcode'  => 'Zip/Postal Code',
//		'telephone' => 'Telephone',
//		'cellphone' => 'Cellphone',
//		'fax'       => 'Fax',
//		'vat'       => 'VAT Number',
//		'dob'       => 'Date of Birth',
//		'gender'    => 'Gender',
//		'taxvat'    => 'Tax/VAT Number'
	);

	public function toOptionArray()
	{
		$fieldsOrderArray = Mage::getModel('osc/system_config_source_fieldsorder')->getArray();
		unset($fieldsOrderArray['none']);
		unset($fieldsOrderArray['name']);
		$allOptions = array_merge($this->_options, $fieldsOrderArray);

		$options = array();
		foreach ($allOptions as $key => $val) {
			$options[] = array(
				'value' => $key,
				'label' => Mage::helper('osc')->__($val)
			);
		}
		return $options;
	}

}
