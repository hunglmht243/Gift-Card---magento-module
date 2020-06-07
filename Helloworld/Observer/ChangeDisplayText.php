<?php

namespace Mageplaza\HelloWorld\Observer;

class ChangeDisplayText implements \Magento\Framework\Event\ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $displayText = $observer->getData('mp_text');
        echo $displayText->getHung() . " - Event </br>";
        $displayText->setHung('Execute event successfully.');

        return $this;
    }
}