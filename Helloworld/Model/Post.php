<?php
namespace Mageplaza\HelloWorld\Model;
class Post extends \Magento\Framework\Model\AbstractModel
{
    const CACHE_TAG = 'mageplaza_helloworld_post';

    protected $_cacheTag = 'mageplaza_helloworld_post';

    protected $_eventPrefix = 'mageplaza_helloworld_post';

    protected function _construct()
    {
        $this->_init('Mageplaza\HelloWorld\Model\ResourceModel\Post');
    }



    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }
}