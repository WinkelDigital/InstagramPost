<?php
namespace Winkel\InstagramPost\Controller\Adminhtml\Post;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action;
use Winkel\InstagramPost\Model\PostFactory;


class Delete extends \Magento\Backend\App\Action
{
	protected $resultPageFactory = false;

	public function __construct(
		\Magento\Backend\App\Action\Context $context,
        PostFactory $postFactory,
		\Magento\Framework\View\Result\PageFactory $resultPageFactory
	)
	{
		parent::__construct($context);
        $this->_postFactory = $postFactory;
		$this->resultPageFactory = $resultPageFactory;
	}

	public function execute()
	{
        $id = $this->getRequest()->getParam('id');
        if (!($post = $this->_postFactory->create()->load($id))) {
            $this->messageManager->addError(__('Unable to proceed. Please, try again.'));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/index', array('_current' => true));
        }
        try{
            $post->delete();
            $this->messageManager->addSuccess(__('Your data has been deleted !'));
        } catch (Exception $e) {
            $this->messageManager->addError(__('Error while trying to data contact: '));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/index', array('_current' => true));
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/index', array('_current' => true));
	}


}