<?php

namespace Mageplaza\GiftCard\Plugin;
use Magento\Framework\App\RequestInterface;
use Magento\Checkout\Model\Session;

class CouponPlugin
{   protected $_session;
    protected $_request;
    public function __construct(
        Session $session,
        RequestInterface $request
    ){
        $this->_session = $session;
        $this->_request=$request;
    }

    public function afterGetCouponCode(\Magento\Checkout\Block\Cart\Coupon $subject, $result)
    {
        $codePost=$this->_session->getCodePost();
        if($codePost) {
            $result=$codePost;
        }
        return $result;
    }

}