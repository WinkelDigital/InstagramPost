<?php
namespace Winkel\InstagramPost\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;

class Collections extends Template implements BlockInterface
{
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Winkel\InstagramPost\Model\PostFactory $postFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        $data = []
   ){
    $this->_postFactory = $postFactory;
    $this->_storeManager = $storeManager;
    parent::__construct($context, $data);

   }
   protected $_template = "widget/post/collections.phtml";

   public function getCollection(){
        $posts = $this->_postFactory->create();
        $posts = $posts->getCollection();
        if($this->getData('ids')){
          $posts = $posts->addFieldToFilter('id',['in' => explode(',',$this->getData('ids'))]);
        }
        return $posts;
   }

   public function getImageUrl($image){
        $mediaUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $storageUrl = 'wysiwyg/instagram-post/';
        return $mediaUrl . $storageUrl . $image;
   }

}