<?php
namespace Mageplaza\GiftCard\Controller\Index;
//die();
class Xoa extends \Magento\Framework\App\Action\Action
{

    protected $_cardFactory;
    protected $request;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Request\Http $request,
        \Mageplaza\GiftCard\Model\CardFactory $cardFactory
    )
    {
        $this->request=$request;
        $this->_cardFactory = $cardFactory;
        return parent::__construct($context);
    }

    public function execute()
    {

        $card = $this->_cardFactory->create();
        $param=$this->request->getParams();

        if(!empty($param['id']) ){
            $id=$param['id'];
            $card->load($id)->delete();
        }

        $collection = $card->getCollection()->getData();
        echo "<pre>"; print_r($collection);  echo "</pre>";
        exit();

    }
}