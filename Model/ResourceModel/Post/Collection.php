<?php
namespace Winkel\InstagramPost\Model\ResourceModel\Post;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	protected $_idFieldName = 'id';
	protected $_eventPrefix = 'winkel_instagram_post';
	protected $_eventObject = 'instagram_post';

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('Winkel\InstagramPost\Model\Post', 'Winkel\InstagramPost\Model\ResourceModel\Post');
	}

}
