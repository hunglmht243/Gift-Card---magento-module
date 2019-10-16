<?php
namespace Mageplaza\GiftCard\Controller\Index;
//die('aa');
class Test extends \Magento\Framework\App\Action\Action
{
    //protected $_pageFactory;

    protected $_cardFactory;
    protected $request;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Request\Http $request,
        \Mageplaza\GiftCard\Model\CardFactory $cardFactory
    )
    {
        //$this->_pageFactory = $pageFactory;
        $this->request=$request;
        $this->_cardFactory = $cardFactory;
        return parent::__construct($context);
    }

    public function execute()
    {   //echo 'dd';
//        $s = substr(str_shuffle(str_repeat("ABCDEFGHIJKLMLOPQRSTUVXYZ0123456789", 5)), 0, 12);
//
//        die($s);
        $card = $this->_cardFactory->create();
        //var_dump($post);
        //die('dd');
        $param=$this->request->getParams();
        //var_dump();

        if ((!empty($param['them']) && ($param['them']==1) )  ){
            $data = [
                'code'          => $param['code'],
                'balance'       => $param['balance'],
                'amount_used'   => $param['amount_used'],
                //'create_from'         => 'admin',
            ];
            //echo 'aaaaaa';
           $check=$card->load($param['code'],'code')->getData();//var_dump($card->getData());//die('a');
            //if ($post->load($param['code'],'code')==null) die('aa');
            if (empty($check))
            $card->addData($data)->save();

            //$post->addData($data)->save();
        }
        if (!empty($param['sua']) && ($param['sua']==1)){
            $data = [
                'code'          => $param['code'],
                'balance'       => $param['balance'],
                'amount_used'   => $param['amount_used'],
                //'create_from'         => 'admin',
            ];
            $id=$param['id'];
           $card->load($id)->addData($data)->save();//print_r($post->getData());
            //$post->setData()
        }

        if (!empty($param['delete']) && ($param['delete']==1)){
            $id=$param['id'];
            $card->load($id)->delete();
        }

        $collection = $card->getCollection()->getData();
        echo "<pre>"; print_r($collection);  echo "</pre>";
        exit();

    }
}