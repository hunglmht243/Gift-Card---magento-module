<?php
namespace Mageplaza\GiftCard\Model\ResourceModel\Customer;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'entity_id';


    protected function _construct()
    {
        $this->_init('Mageplaza\GiftCard\Model\Customer', 'Mageplaza\GiftCard\Model\ResourceModel\Customer');
    }

}