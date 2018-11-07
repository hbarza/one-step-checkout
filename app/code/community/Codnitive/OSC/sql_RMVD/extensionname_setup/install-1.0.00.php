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
 * @package    Codnitive_ExtensionName
 * @author     Hassan Barza <support@codnitive.com>
 * @copyright  Copyright (c) 2012 CODNITIVE Co. (http://www.codnitive.com)
 * @license    http://www.codnitive.com/en/terms-of-service-softwares/ End User License Agreement (EULA 1.0)
 */

$installer = $this;

$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('extensionname/TABLEREFERENCE'))
    ->addColumn('value_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Value Id')
    ->addColumn('user_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Reward ID')
    ->addColumn('user_ip', Varien_Db_Ddl_Table::TYPE_TEXT, 64, array(
        'nullable'  => true,
        ), 'User IP Address')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable'  => false,
        ), 'Created At')
//    ->addColumn('change_points', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
//        'nullable'  => false,
//        'default'   => '0.0000',
//        ), 'Change Points')
//    ->addColumn('state', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
//        'unsigned'  => true,
//        'nullable'  => false,
//        'default'   => '0',
//        ), 'State')
//    ->addColumn('reward_points', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(
//        'unsigned'  => true,
//        'nullable'  => false,
//        'default'   => '0.0000',
//        ), 'Customer Reward Points')
    ->addIndex($installer->getIdxName('extensionname/TABLEREFERENCE', array('user_id')),
        array('user_id'))
    ->addForeignKey($installer->getFkName('extensionname/TABLEREFERENCE', 'user_id', 'admin/entity', 'entity_id'),
        'user_id', $installer->getTable('customer/entity'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Customer ExtensionName Report TABLEREFERENCE');
$installer->getConnection()->createTable($table);

$installer->endSetup();
