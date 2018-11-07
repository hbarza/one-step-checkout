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
 * @package    Codnitive_Codall
 * @author     Hassan Barza <support@codnitive.com>
 * @copyright  Copyright (c) 2012 CODNITIVE Co. (http://www.codnitive.com)
 * @license    http://www.codnitive.com/en/terms-of-service-softwares/ End User License Agreement (EULA 1.0)
 */

class Codnitive_Pasargad_Model_Config
{
    const PATH_NAMESPACE      = 'payment';
    const EXTENSION_NAMESPACE = 'pasargad';
    
    const EXTENSION_NAME    = 'Pasargad Online Payment Pro';
    const EXTENSION_VERSION = '1.024';
    const EXTENSION_EDITION = 'Community';
    
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
    
    public function getCertType()
    {
        return Mage::getStoreConfig(self::getNamespace().'cert_type');
    }
    
    public function getDebugFlag()
    {
        return Mage::getStoreConfigFlag(self::getNamespace().'debug');
    }

}
