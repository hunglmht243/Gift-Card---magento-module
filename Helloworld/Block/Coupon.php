<?php
namespace Mageplaza\HelloWorld\Block;


//die('aa');
class Coupon extends \Magento\Checkout\Block\Cart\Coupon
{
    protected $_NewsletterSession;
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Newsletter\Model\Session $NewsletterSession,
        array $data = []
    ) {
        parent::__construct($context, $customerSession, $checkoutSession, $data);
        $this->_NewsletterSession=$NewsletterSession;
        //$this->_isScopePrivate = true;
    }

   public function checkSeesion(){
        $Coupon=$this->_NewsletterSession->getCoupon();
        if($Coupon) return $Coupon;
        return false;
    }

}