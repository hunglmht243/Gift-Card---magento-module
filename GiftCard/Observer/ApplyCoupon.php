<?php

namespace Mageplaza\GiftCard\Observer;
//die('â');
use Magento\Framework\Event\Observer;
use Mageplaza\GiftCard\Model\CardFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ActionFlag;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Checkout\Model\Session;

class ApplyCoupon implements \Magento\Framework\Event\ObserverInterface
{
    protected $_CardFactory;
    protected $_observer;
    protected $_request;
    protected $_productFactory;
    protected $_messageManager;
    protected $_actionFlag;
    protected $_redirect;
    protected $_session;
    public function __construct(
        CardFactory $CardFactory,
        Observer $observer,
        RequestInterface $request,
        ActionFlag $actionFlag,
        RedirectInterface $redirect,
        ManagerInterface $messageManager,
        Session $session
    )
    {
        $this->_CardFactory = $CardFactory;
        $this->_observer = $observer;
        $this->_messageManager = $messageManager;
        $this->_request=$request;
        $this->_actionFlag = $actionFlag;
        $this->_redirect = $redirect;
        $this->_session = $session;
    }

    public function execute(Observer $observer)
    {
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/test.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        //$logger->info('bb');

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $cart = $objectManager->get('\Magento\Checkout\Model\Cart');
        $totalItems = $cart->getQuote()->getItemsCount();
//        $logger->info('hey'. $totalItems);
//        $logger->info($totalItems);
        if ($totalItems>0) {
            $giftCard = $this->_CardFactory->create();
            $controller = $observer->getControllerAction();

            $remove = $this->_request->getParam('remove');
            $codePost = $this->_request->getParam('coupon_code');
            $check= 0;
            if ($remove == 0){
                $card=$giftCard->load($codePost, 'code');
                $codeCheck = $card->getData('code');
                $balanceCode = $card->getData('balance');
                $amountUsedCode = $card->getData('amount_used');
                $this->_session->setRemove('0');
                if ($codeCheck) {
                    if ($balanceCode > $amountUsedCode) {
                        $check=1;
                        $this->_messageManager->addSuccessMessage(('You used gift card code "' . $codePost . '".'));
                    }
                    else {
                        $this->_messageManager->addErrorMessage(('Error! Gift card has been expired!'));
                    }
                }
                else {
                    $this->_messageManager->addErrorMessage(('The gift card code "' . $codePost . '" is not valid.'));
                }
            }
            else {
                $this->_session->setRemove('1');
                $this->_session->unsCodePost();
                //$this->_messageManager->addSuccessMessage(('You canceled the giftcard code "codePost".'));
            }

            if ($check == 1) {
                $this->_session->setCodePost($codePost);
                $this->_actionFlag->set('', \Magento\Framework\App\Action\Action::FLAG_NO_DISPATCH, true);
            }

             $this->_redirect->redirect($controller->getResponse(), 'checkout/cart/index');

        } else{
            $this->_session->unsCodePost();
        }


        return $this;
    }
}
