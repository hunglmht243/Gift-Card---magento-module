<?php
namespace Mageplaza\GiftCard\Controller\Index;
//die();
class Them extends \Magento\Framework\App\Action\Action
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
//        $giftcard=$card->load(35)->getData();
//        echo "<pre>";print_r($giftcard);var_dump($giftcard);echo "<pre>";
//        print_r(empty($giftcard));
//        var_dump(empty($giftcard));
//        if(!empty($giftcard)) echo '1';
//        else echo '0';
        if((!empty($param['code'])) && (!empty($param['balance'])) && (!empty($param['amount_used']))){
            $data = [
                'code'          => $param['code'],
                'balance'       => $param['balance'],
                'amount_used'   => $param['amount_used'],
            ];
            $check=$card->load($param['code'],'code');
            if ($check->getId())
                $card->addData($data)->save();
        }

        $collection = $card->getCollection()->getData();
        echo "<pre>"; print_r($collection);  echo "</pre>";
        exit();

    }
}