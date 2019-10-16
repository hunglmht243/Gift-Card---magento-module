<?php
namespace Mageplaza\GiftCard\Model\ResourceModel;


class History extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{


    protected function _construct()
    {
        $this->_init('giftcard_history', 'history_id');
    }

}