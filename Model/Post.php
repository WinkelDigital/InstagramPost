<?php
namespace Winkel\InstagramPost\Model;

class Post extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
	const CACHE_TAG = 'winkel_instagram_post';

	protected $_cacheTag = 'winkel_instagram_post';

	protected $_eventPrefix = 'winkel_instagram_post';

	protected function _construct()
	{
		$this->_init('Winkel\InstagramPost\Model\ResourceModel\Post');
	}

	public function getIdentities()
	{
		return [self::CACHE_TAG . '_' . $this->getId()];
	}

	public function getDefaultValues()
	{
		$values = [];

		return $values;
	}
}