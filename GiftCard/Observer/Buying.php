<?php

namespace Mageplaza\GiftCard\Observer;
use Magento\Framework\Math\Random;
use Mageplaza\GiftCard\Helper\Data;
use Mageplaza\GiftCard\Model\HistoryFactory;
use Mageplaza\GiftCard\Model\CardFactory;
use Magento\Catalog\Model\ProductFactory;

class Buying implements \Magento\Framework\Event\ObserverInterface
{
    protected $_cardFactory;
    protected $_productFactory;
    protected $_historyFactory;
    protected $_helperData;
    protected $_random;


    public function __construct(
        CardFactory $CardFactory,
        HistoryFactory $historyFactory,
        Data $data,
        Random $random,
        ProductFactory $productFactory

    )
    {
        $this->_cardFactory = $CardFactory;
        $this->_productFactory = $productFactory;
        $this->_historyFactory = $historyFactory;
        $this->_random = $random;
        $this->_helperData = $data;

    }


    public function execute(\Magento\Framework\Event\Observer $observer)
     {
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/log01.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info('class aaaaaa: '.get_class($observer->getData('order')));


          $product = $this->_productFactory->create();

        $customerId = $observer->getData('order')->getData('customer_id');
        $orderId = $observer->getData('order')->getData('increment_id');
        $codeLength = $this->_helperData->getConfigValue('giftcard/code/display_text');
        //$logger->info($codeLength);
        $itemCart = $observer->getData('order')->getAllItems();
        $method=$observer->getData('order');

        foreach($itemCart as $item) {
            $logger->info(get_class($item).'bbb');
              $idItemCart = $item->getProductId();
            $qtyItemCart = $item->getQtyOrdered();
            $giftCardAmount = $product->load($idItemCart)->getData('gift_card_amount');
            if ($giftCardAmount) {
                for ($i = 1; $i <= $qtyItemCart; $i++){
                    $giftCard = $this->_cardFactory->create();
                    $history = $this->_historyFactory->create();

                    do{
                        $code = $this->_random->getRandomString($codeLength, 'ABCDEFGHIJKLMLOPQRSTUVXYZ0123456789');
                        $codeCheck = $giftCard->load($code,'code')->getData('code');
                    } while(!empty($codeCheck));

                    $giftCard->addData([
                        'code' => $code,
                        'balance' => $giftCardAmount,
                        'amount_used' => 0,
                        'create_from' => $orderId
                    ])->save();

                    $newGiftCardId = $giftCard->load($code,'code')->getData('giftcard_id');
                    $history->addData([
                        'customer_id' => $customerId,
                        'giftcard_id' => $newGiftCardId,
                        'amount' => $giftCardAmount,
                        'action' => 'create'
                    ])->save();
                }
            }
        }

        return $this;

    }
}