<?php
namespace Mageplaza\HelloWorld\Observer;
use Magento\Framework\Event\Observer;

class MyObserver implements \Magento\Framework\Event\ObserverInterface
{
    protected $_NewsletterSession;
    public function __construct(
        \Magento\Newsletter\Model\Session $NewsletterSession

    ){
        $this->_NewsletterSession=$NewsletterSession;
    }
    public function execute(Observer $observer)
    {
        $writer1 = new \Zend\Log\Writer\Stream(BP . '/var/log/log02.log');
        $logger1 = new \Zend\Log\Logger();
        $logger1->addWriter($writer1);
        try {
            $this->_NewsletterSession->setCoupon('hun');
            $event = $observer->getEvent();
            $customer = $event->getSubscriber();
            $customerEmail = $customer->getSubscriberEmail();
            $logger1->info('class bbb: '.$customerEmail);
        } catch (\Exception $e) {
            $logger1->info($e->getMessage());
        }

//        $email = $subscriber->getEmail();
//        $subscriberStatus = $subscriber->getSubscriberStatus();
        // subscriberStatus = 1 subscribe
        // subscriberStatus = 3 unsubscribed
//        if ( $subscriberStatus == '1') {
//        }
        //echo 'â';
    }
}