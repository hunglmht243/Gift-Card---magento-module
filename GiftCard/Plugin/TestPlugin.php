<?php

namespace Mageplaza\GiftCard\Plugin;

class TestPlugin
{

    public function beforeSetTitle(\Mageplaza\GiftCard\Controller\Index\Testplugin $subject, $titlee)
    {
        $title = $titlee . " too ";
        echo __METHOD__ . "</br>";
        var_dump($titlee);
        return $title.'a'."</br>";
    }


    public function afterGetTitle(\Mageplaza\GiftCard\Controller\Index\Testplugin $subject, $resultt)
    {

        echo __METHOD__ . "</br>";
        var_dump($resultt);
        return '<h1>'. $resultt . 'Mageplaza.com' .'</h1>';

    }


    public function aroundGetTitle(\Mageplaza\GiftCard\Controller\Index\Testplugin $subject, callable $proceed)
    {

        echo __METHOD__ . " - Before proceed() </br>";
        $result = $proceed();
        var_dump($proceed());
        //echo __METHOD__ . " - After proceed() </br>";


        return $result.'ii';
    }

}