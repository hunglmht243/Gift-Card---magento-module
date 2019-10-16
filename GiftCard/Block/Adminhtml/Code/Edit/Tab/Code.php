<?php
namespace Mageplaza\GiftCard\Block\Adminhtml\Code\Edit\Tab;
use Magento\Store\Model\ScopeInterface;
class Code extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{

    protected $helperData;
    protected $_cardFactory;

    public function __construct(

        \Mageplaza\GiftCard\Model\CardFactory $cardFactory,
        \Mageplaza\GiftCard\Helper\Data $helperData,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        array $data = []
    )
    {

        $this->_cardFactory = $cardFactory;
        $this->helperData = $helperData;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _prepareForm()
    {
        $form = $this->_formFactory->create();
        $card = $this->_cardFactory->create();
        $fieldset = $form->addFieldset('gift_card_form', ['legend' => __('Gift Card Information ttt')]);
        if ($this->getRequest()->getParam('id')==null){
            $value=$this->helperData->getGeneralConfig('display_text');
            $fieldset->addField(
                'code length',
                'text',
                [
                    'label' =>'Code Length',
                    'name' => 'code_length',
                    'value' => $value,
                    //'class' => 'hidden'
                ]
            );
            $fieldset->addField(
                'balance',
                'text',
                [
                    'label' =>'Balance',
                    'name' => 'balance',
                    'required' => 'true'

                ]
            );



        }
         else {
             $id=$this->getRequest()->getParam('id');
             $fieldset->addField(
                 'id',
                 'hidden',
                 [
                     'label' =>'Id',
                     'name' => 'id',
                     'value' => $id ,
                     //'disabled' => 'true'

                 ]
             );
             $code=$card->load($id)->getData('code');
             $fieldset->addField(
                 'code',
                 'text',
                 [
                     'label' =>'Code',
                     'name' => 'code',
                     'value' =>  $code,
                     'disabled' => 'true'

                 ]
             );
             $balance=$card->load($id)->getData('balance');
             $fieldset->addField(
                 'balance',
                 'text',
                 [
                     'label' =>'Balance',
                     'name' => 'balance',
                     'required' => 'true',
                     'value' => $balance
                     //'value' => '',
                     //'class' => 'hidden'
                 ]
             );
              $create_from=  $card->load($id)->getData('create_from');
             $fieldset->addField(
                 'create_from',
                 'text',
                 [
                     'label' =>'Create From',
                     'name' => 'create_from',
                     //'required' => 'true',
                     'value' => 'admin',
                     'disabled' => 'true'
                     //'value' => '',
                     //'class' => 'hidden'
                 ]
             );


         }


        $this->setForm($form);
        return parent::_prepareForm();
    }


    public function getTabLabel()
    {
        return __('Gift card information tab');
    }

    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
    }
}
