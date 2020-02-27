<?php
namespace Winkel\InstagramPost\Controller\Adminhtml\Post;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Winkel\InstagramPost\Model\PostFactory;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Cache\Frontend\Pool as FrontendPool;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;

class Save extends \Magento\Backend\App\Action
{
    protected $_filesystem;

    /**
     * @var PostFactory
     */
    protected $_postFactory;
    /**
     * @var TypeListInterface
     */
    protected $_cacheTypeList;
    /**
     * @var FrontendPool
     */
    protected $_cacheFrontendPool;
    /**
     * @var DateTime
     */
    protected $_dateTime;
    /**
     * Constructor
     *
     * @param Context $context
     * @param PostFactory $postFactory
     * @param TypeListInterface $cacheTypeList
     * @param FrontendPool $cacheFrontendPool
     * @param DateTime $dateTime
     */
    public function __construct(
        Context $context,
        PostFactory $postFactory,
        TypeListInterface $cacheTypeList,
        FrontendPool $cacheFrontendPool,
        Filesystem $filesystem,
        DateTime $dateTime
    )
    {
        $this->_postFactory = $postFactory;
        $this->_cacheTypeList = $cacheTypeList;
        $this->_cacheFrontendPool = $cacheFrontendPool;
        $this->_dateTime = $dateTime;
        $this->_filesystem = $filesystem;
        parent::__construct($context);
    }
    /**
     * Save action
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $data = $this->getRequest()->getPostValue();
        if ($data) {
            // $id = isset($data['id'])?$data['id']:false;
            $model = $this->_postFactory->create();
            if (isset($data['id'])) {
                $model->load($data['id']);
            }
            
            //  Initiate curl
            $url = $data['url'] . '?__a=1';
            $result=file_get_contents($url);
            $igData = json_decode($result);
            $imgUrl = $igData->graphql->shortcode_media->display_url;
            $thumbUrl = $igData->graphql->shortcode_media->display_resources[0]->src;
            $imgName = $igData->graphql->shortcode_media->id;
            $data['image'] = $this->downloadImage($imgUrl,$imgName);
            $data['thumb'] = $this->downloadImage($thumbUrl,$imgName . '-thumb');
            $caption = $igData->graphql->shortcode_media->edge_media_to_caption->edges;
            if(isset($caption[0])){
                $data['caption'] = $caption[0]->node->text;
            }
            $data['owner'] = $igData->graphql->shortcode_media->owner->username;
            $data['profile_pic'] = $igData->graphql->shortcode_media->owner->profile_pic_url;
            $model->addData($data);
            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('Successfully saved data'));
                $this->_cacheTypeList->cleanType('config');
                foreach ($this->_cacheFrontendPool as $cacheFrontend) {
                    $cacheFrontend->getBackend()->clean();
                }
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving lookbook.'));
            }
            return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    function downloadImage($url,$fname){
        $mediapath = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath();
        $pathInMedia = 'wysiwyg/instagram-post/';
        $dir = $mediapath . $pathInMedia;
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        $filename = $fname . '.jpg';
        $img = $dir . $filename;
        if (!file_exists($img)) {
            file_put_contents($img, file_get_contents($url));
        }
        return $filename;
    }
}