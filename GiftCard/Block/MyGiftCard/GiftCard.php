<?php
namespace Mageplaza\GiftCard\Block\MyGiftCard;

class GiftCard extends \Magento\Framework\View\Element\Template
{
    protected $_cardFactory;
    protected $_customerFactory;
    protected $_historyFactory;
    protected $_currentCustomer;
    protected $_currency;
    protected $_helperData;
    protected $_date;

    public function __construct(
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $date,
        //\Mageplaza\GiftCard\Model\ResourceModel\History\Collection $history,
        \Mageplaza\GiftCard\Helper\Data $helperData,
        \Magento\Framework\Pricing\Helper\Data $currency,
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer,
        \Magento\Framework\View\Element\Template\Context $context,
        \Mageplaza\GiftCard\Model\CardFactory $cardFactory,
        \Mageplaza\GiftCard\Model\CustomerFactory $customerFactory,
        \Mageplaza\GiftCard\Model\HistoryFactory $historyFactory
    )
    {
        $this->_cardFactory = $cardFactory;
        $this->_currentCustomer = $currentCustomer;
        $this->_customerFactory = $customerFactory;
        $this->_historyFactory =  $historyFactory;
        $this->_currency =  $currency;
        $this->_helperData = $helperData;
        $this->_date =  $date;
        parent::__construct($context);
    }
    public function getCusBalance(){
        $cus=$this->_customerFactory->create();
        $currentCustomerId = $this->_currentCustomer->getCustomerId();
        return $cus->load($currentCustomerId)->getGiftcardBalance();
        //return $cus->getCollection();
    }

    public function getFormatedPrice($amount)
    {
        return $this->_currency->currency($amount,true,false);
    }

    public function getConfig(){
        return $this->_helperData->getConfigValue('giftcard/general/enable1');

    }

    public function getHisCollection(){
        $his=$this->_historyFactory->create();
        $collection=$his->getCollection();
        //select giftcard_history.*, giftcard_code.code form giftcard_history inner join gifcard_code
        // on giftcard_history.giftcard_id == giftcard_code.giftcard_id
    $collection->getSelect()->join(
    ['table_gcCode'=>$collection->getTable('mageplaza_giftcard_card')],
    'main_table.giftcard_id = table_gcCode.giftcard_id',['code']
    );
// echo $collection->getSelect()->__toString();
// die($this->_currentCustomer->getCustomerId());
        return $collection->addFieldToFilter('customer_id',$this->_currentCustomer->getCustomerId());
    }

//    public function getCode($id){
//        $card=$this->_cardFactory->create();
//        $card->load($id)->getGiftcarId();
//    }

    public function getFormatedDate($date){
        return $this->_date->date($date)->format('d/m/y');
    }



}