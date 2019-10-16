<?php

namespace Mageplaza\GiftCard\Controller\Adminhtml\code;

use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Mageplaza\GiftCard\Model\ResourceModel\Card\CollectionFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\RequestInterface;

class MassDelete extends \Magento\Backend\App\Action
{
    protected $request;

    protected $filter;

    protected $collectionFactory;

    public function __construct(Context $context, Filter $filter, CollectionFactory $collectionFactory,  RequestInterface $request)
    {
        $this->filter = $filter;
        $this->request = $request;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    public function execute()
    {$collection = $this->filter->getCollection($this->collectionFactory->create());
//        echo '<pre>';
//        print_r($this->request->getParams());
//    echo '</pre>'; die('a');

        $collectionSize = $collection->getSize();

        foreach ($collection as $item) {
//            echo get_class($item);
//            var_dump($item->getData());
            $item->delete();

        }


        $this->messageManager->addSuccess(__('A total of %1 element(s) have been deleted.', $collectionSize));

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('mageplaza_giftcard/code/index');
    }
}