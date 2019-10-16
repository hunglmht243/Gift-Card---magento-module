<?php

namespace Mageplaza\GiftCard\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;


class Actions extends Column
{

    const URL_PATH_EDIT = 'mageplaza_giftcard/code/edit';



    protected $urlBuilder;


    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }


    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
//            echo '<pre>';
//        print_r($dataSource);
//    echo '</pre>';
// die();
            foreach ($dataSource['data']['items'] as & $item){

                if (isset($item['giftcard_id'])) {
//                    echo '<pre>';
//                      print_r($this->getData());
//                    echo '</pre>';


                    $item[$this->getData('name')] = [
                        'edit' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_EDIT,
                                [
                                    'id' => $item['giftcard_id']
                                ]
                            ),
                            'label' => __('Edit')
                        ]
                    ];
//                    echo '<pre>';
//                    print_r($dataSource);
//                    echo '</pre>';die();
                }
            }
        }

        return $dataSource;
    }
}