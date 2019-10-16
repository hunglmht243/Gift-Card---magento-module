<?php
namespace Mageplaza\GiftCard\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\DB\Ddl;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade( SchemaSetupInterface $setup, ModuleContextInterface $context ) {
        $installer = $setup;

        $installer->startSetup();
         // if dưới để bỏ qua lần upgrade trước đó
        if(version_compare($context->getVersion(), '2.0.0', '<')) {
            if (!$installer->tableExists('mageplaza_giftcard_card')) {
                $table = $installer->getConnection()->newTable(
                    $installer->getTable('mageplaza_giftcard_card')
                )
                    ->addColumn(
                        'giftcard_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        [
                            'identity' => true,
                            'nullable' => false,
                            'primary'  => true,
                            'unsigned' => true,
                        ],
                        'card ID'
                    )
                    ->addColumn(
                        'code',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        'code'
                    )
                    ->addColumn(
                        'balance',
                        \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                        '12,2',
                        [],
                        'giá trị của giftcard'
                    )
                    ->addColumn(
                        'amount_used',
                        \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                        '12,2',
                        [],
                        'lượng amount đã bị sử dụng của giftcard'
                    )
                    ->addColumn(
                        'create_from',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        [],
                        'Ngày tạo gift code'
                    )

                    ->addColumn(
                        'created_at',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,
                        ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                        'Created At'
                    )
                    ->setComment('card Table');
                $installer->getConnection()->createTable($table);


            }
        }
        if(version_compare($context->getVersion(), '2.1.7', '<')) {
            if (!$installer->tableExists('giftcard_history')) {
                $table = $installer->getConnection()->newTable(
                    $installer->getTable('giftcard_history')
                )
                    ->addColumn(
                        'history_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        [
                            'identity' => true,
                            'nullable' => false,
                            'primary' => true,
                            'unsigned' => true,
                        ],
                        'History Id'
                    )
                    ->addColumn(
                        'giftcard_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        [
                            'nullable'=>true,
                            'unsigned'=>true
                        ],
                        'Gift Card Id'
                    )
                    ->addColumn(
                        'customer_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        [
                            'nullable'=>true,
                            'unsigned'=>true
                        ],
                        'Customer Id'
                    )
                    ->addColumn(
                        'amount',
                        \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                        '12,4',
                        [],
                        'Amount'
                    )
                    ->addColumn(
                        'action',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        null,
                        [],
                        'Action'
                    )
                    ->addColumn(
                        'action_time',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,
                        ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                        'Action Time'
                    )
                    ->addForeignKey(
                        $setup->getFkName('giftcard_history', 'giftcard_id', 'mageplaza_giftcard_card', 'giftcard_id'),
                        'giftcard_id',
                        $setup->getTable('mageplaza_giftcard_card'),
                        'giftcard_id',
                        \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                    )
                    ->addForeignKey(
                        $setup->getFkName('giftcard_history', 'customer_id', 'customer_entity', 'entity_id'),
                        'customer_id',
                        $setup->getTable('customer_entity'),
                        'entity_id',
                        \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                    )
                    ->setComment('Gift Card History');
                $installer->getConnection()->createTable($table);
            }
        }


        if (version_compare($context->getVersion(), '2.1.3', '<')) {
            $installer->getConnection()->addColumn(
                $installer->getTable('customer_entity'),
                'giftcard_balance',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                    'length' => '12,2',

                    'comment' => 'balance'
                ]
            );

        }

//        if (version_compare($context->getVersion(), '2.1.6', '<')) {
//            $installer->getConnection()->addForeignKey(
//                $setup->getFkName('giftcard_history', 'giftcard_id', 'mageplaza_giftcard_card', 'giftcard_id'),
//                $installer->getTable('giftcard_history'),
//                'giftcard_id',
//                $setup->getTable('mageplaza_giftcard_card'),
//                'giftcard_id',
//                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
//            );
//
//        }


        $installer->endSetup();
    }
}