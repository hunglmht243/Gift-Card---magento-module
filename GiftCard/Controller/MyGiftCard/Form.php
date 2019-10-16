<?php
namespace Mageplaza\GiftCard\Controller\MyGiftCard;
class Form extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
    protected $_customerFactory;
    protected $_historyFactory;
    protected $_cardFactory;
    protected $currentCustomer;
    protected $request;

    public function __construct(
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Request\Http $request,
        \Mageplaza\GiftCard\Model\CardFactory $cardFactory,
        \Mageplaza\GiftCard\Model\CustomerFactory $customerFactory,
        \Mageplaza\GiftCard\Model\HistoryFactory $historyFactory,
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer

    )
    {
        $this->_pageFactory = $pageFactory;
        $this->request=$request;
        $this->_cardFactory = $cardFactory;
        $this->_customerFactory = $customerFactory;
        $this->_historyFactory =  $historyFactory;
        $this->currentCustomer = $currentCustomer;
        return parent::__construct($context);
    }

    public function execute()
    {
        $history=$this->_historyFactory->create();
        $cus=$this->_customerFactory->create();
        $post = $this->getRequest()->getParams();
        //die($post['code']);
        //if (!empty($post['code'])) echo '1';die();
        if ($post['code']) {

            $code = $post['code'];
            //die($code);
            $currentCustomerId = $this->currentCustomer->getCustomerId();
            $card=$this->_cardFactory->create();
            $cardRedeem=$card->load($code,'code');
            $cusRedeem=$cus->load($currentCustomerId,'entity_id');
            $recentBalance=$cardRedeem->getBalance();
            $recentCusBalance=$cus->getGiftcardBalance(); $cusBalance=$recentCusBalance+$recentBalance;
            $recentAmountUsed=$cardRedeem->getAmountUsed(); $amountUsed=$recentAmountUsed + $recentBalance;

            $giftcardID=$cardRedeem->getData('giftcard_id');

            $check=$cardRedeem->getData();
            $check1=$history->load($giftcardID,'giftcard_id')->getData();
            if($check1) {
                $data = [
                    'giftcard_id'          => $giftcardID,
                    'amount'       => $recentBalance,
                    'action' => 'redeem',
                    'customer_id' => $currentCustomerId,
                ];
            }
            else {
                $data = [
                    'giftcard_id'          => $giftcardID,
                    'amount'       => $recentBalance,
                    'action' => 'create',
                    'customer_id' => $currentCustomerId,
                ];
            }

            if ($check){
                if($cardRedeem->getBalance() > $cardRedeem->getAmountUsed()){

                    if ($check1) { $history->load($giftcardID,'giftcard_id')->addData($data)->save(); }
                    else { $history->addData($data)->save(); }

                    $cardRedeem->setData('amount_used',$amountUsed)->save();
                    $cusRedeem->setData('giftcard_balance',$cusBalance)->save();
                    $this->messageManager->addSuccess('redeem thành công');
                }
                else $this->messageManager->addErrorMessage('balance không đủ');
            }
            else {
                $this->messageManager->addErrorMessage('gift code sai');
            }
        }
        else {
            $this->messageManager->addErrorMessage('chưa nhập code');
        }

        return $this->_redirect('*/*');


    }
}
