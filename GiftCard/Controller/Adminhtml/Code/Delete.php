<?php
namespace Mageplaza\GiftCard\Controller\Adminhtml\code;

class Delete extends \Magento\Backend\App\Action
{
    protected $pageFactory=false;
    protected $_cardFactory;


    public function __construct(
        \Mageplaza\GiftCard\Model\CardFactory $cardFactory,
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory

    )
    {   parent::__construct($context);

        $this->_pageFactory = $pageFactory;
        $this->_cardFactory = $cardFactory;
    }

    public function execute()
    {
        $card = $this->_cardFactory->create();
//        echo '<pre>';
//        print_r($this->getRequest()->getParams());
//        echo '<pre>';die();
        $id= $this->getRequest()->getParams()['id'];
        $card->load($id)->delete()->save();
            $this->messageManager->addSuccess('xóa thành công');

                return $this->_redirect('*/*');
    }
}