<?php
namespace Mageplaza\HelloWorld\Controller\Index;

class Hello1 extends \Magento\Framework\App\Action\Action
{
    private $str='';
    public function __construct(
        \Magento\Framework\App\Action\Context $context

    )
    {

        return parent::__construct($context);
    }
    public function addA()
    {
        $this->str .= "a";
        return $this;
    }

    public function addB()
    {
        $this->str .= "b";
        return $this;
    }

    public function getStr()
    {
        return $this->str;
    }

    public function execute()
    {

        echo $this->addA()->addB()->getStr();
    }





}