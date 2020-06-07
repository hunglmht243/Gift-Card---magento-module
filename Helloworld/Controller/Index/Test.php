<?php

namespace Mageplaza\HelloWorld\Controller\Index;

class Test extends \Magento\Framework\App\Action\Action
{

    public function execute()
    {
        $textDisplay = new \Magento\Framework\DataObject(
            array(
                'text' => 'Mageplaza',
                'hung' => '20'
            )
        );
        $this->_eventManager->dispatch('mageplaza_helloworld_display_text', ['mp_text' => $textDisplay]);
        echo $textDisplay->getText();
        echo '<br>';
        echo $textDisplay->getHung();
        exit;
    }
}