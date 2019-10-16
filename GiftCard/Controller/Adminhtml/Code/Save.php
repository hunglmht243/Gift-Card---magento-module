<?php
namespace Mageplaza\GiftCard\Controller\Adminhtml\code;
use Magento\Framework\Math\Random;

class Save extends \Magento\Backend\App\Action
{
    protected $pageFactory=false;
    protected $_random;
    protected $_cardFactory;


    public function __construct(
        Random $random,
        \Mageplaza\GiftCard\Model\CardFactory $cardFactory,
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory

    )
    {   parent::__construct($context);

        $this->_pageFactory = $pageFactory;
        $this->_random = $random;
        $this->_cardFactory = $cardFactory;
    }

    public function execute()
    {
          $card = $this->_cardFactory->create();

        $balance=$this->getRequest()->getParams()['balance'];

        if (!empty($this->getRequest()->getParams()['id'])) {
            $id=$this->getRequest()->getParams()['id'];
            $card->load($id)->setBalance($balance)->save();
            $this->messageManager->addSuccess('chỉnh sửa thành công');

           if (!empty($this->getRequest()->getParam('back'))) {
               return $this->_redirect('*/*/edit',['id'=>$id]);
           }else {
               return $this->_redirect('*/*');
           }

        }


        if (!empty($this->getRequest()->getParams()['code_length'])){
            $code_length=$this->getRequest()->getParams()['code_length'];
            $code=$this->_random->getRandomString($code_length,'ABCDEFGHIJKLMLOPQRSTUVXYZ0123456789');

            $card->addData([
                'code' => $code,
                'balance' => $balance,
                'amount_used' => 0,
                'create_from'=>'admin'
            ])->save();
            $id=$card->getId();
            $this->messageManager->addSuccess('thêm thành công');

            if (!empty($this->getRequest()->getParam('back'))) {
                return $this->_redirect('*/*/edit',['id'=>$id]);
            }else {
                return $this->_redirect('*/*');
            }
        }


    }
}