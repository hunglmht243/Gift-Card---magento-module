<?php
namespace Mageplaza\GiftCard\Model\ResourceModel;


class Card extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{


    protected function _construct()
    {
        $this->_init('mageplaza_giftcard_card', 'giftcard_id');
    }

}