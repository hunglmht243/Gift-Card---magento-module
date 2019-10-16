<?php
namespace Mageplaza\GiftCard\Model\Total\Quote;
/**
 * Class Custom
 * @package Mageplaza\HelloWorld\Model\Total\Quote
 */
use Mageplaza\GiftCard\Model\CardFactory;
use Magento\Checkout\Model\Session;

class Custom extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{
    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    protected $_session;
    protected $_giftCardFactory;
    protected $_priceCurrency;
    /**
     * Custom constructor.
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     */
    public function __construct(
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        Session $session,
        CardFactory $giftCardFactory
    ){
        $this->_priceCurrency = $priceCurrency;
        $this->_session = $session;
        $this->_giftCardFactory = $giftCardFactory;
    }
    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return $this|bool
     */
    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    )
    {
        parent::collect($quote, $shippingAssignment, $total);
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/test.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);

        $codePost = $this->_session->getCodePost();
        $giftCard = $this->_giftCardFactory->create()->load($codePost, 'code');
        $balance = $giftCard->getBalance();
        $amountUsed = $giftCard->getAmountUsed();
        $baseDiscount = $balance > $amountUsed ? ($balance-$amountUsed) : 0 ;
        $total->setGiftCardBalance($balance-$amountUsed);

        $discount =  $this->_priceCurrency->convert($baseDiscount);
        //$logger->info($discount);

        $subTotal = $total->getSubtotal();
            $tax = $total->getTaxAmount();
            $ship = $total->getShippingAmount();
            $totalCharge = $tax + $ship;
            //if ($total->getDiscountAmount())
            $totalCheck = $subTotal + $totalCharge - $discount;
            if ($totalCheck  > 0){
                $total->addTotalAmount('customdiscount', -$discount);
            }else{
                $total->addTotalAmount('customdiscount', -($subTotal + $totalCharge));
            }
         //$logger->info($total->getData());

        return $this;
    }
    public function fetch(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Model\Quote\Address\Total $total
    )
    {
        $result = null;
        $remove = $this->_session->getRemove();
        if($remove == 0){
            $amount = $total->getGiftCardBalance();
            $codePost = $this->_session->getCodePost();
            return $result = [
                'code' => 'customdiscounttotal',
                'title' => __('Gift Card (' . $codePost . ')'),
                'value' => $amount
            ];
        }
        return $result = [
            'code' => 'customdiscounttotal',
            'title' => __(''),
            'value' => 0
        ];
    }
}