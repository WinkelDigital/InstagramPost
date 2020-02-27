<?php
namespace Winkel\InstagramPost\Setup;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{

    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
	{
		$installer = $setup;
        $installer->startSetup();
		$installer = $this->create_instagram_post_table($installer,$setup);
		$installer->endSetup();
    }
    
    private function create_instagram_post_table($installer, $setup){
		$tbName = 'winkel_instagram_post';
		if (!$installer->tableExists($tbName)) {
			$table = $installer->getConnection()->newTable(
				$installer->getTable($tbName)
			)
				->addColumn(
					'id',
					Table::TYPE_INTEGER,
					null,
					[
						'identity' => true,
						'nullable' => false,
						'primary'  => true,
						'unsigned' => true,
					],
					'ID'
				)
				->addColumn(
					'url',
					Table::TYPE_TEXT,
					255,
					['nullable => false'],
					'Url'
				)
				->addColumn(
					'caption',
					Table::TYPE_TEXT,
					'64k',
					[],
					'Room Setup Content'
				)
				->addColumn(
					'image',
					Table::TYPE_TEXT,
					255,
					[],
					'Image'
				)
				->addColumn(
					'thumb',
					Table::TYPE_TEXT,
					255,
					[],
					'Thumbnail'
				)
				->addColumn(
					'owner',
					Table::TYPE_TEXT,
					255,
					[],
					'Owner Username'
				)
				->addColumn(
					'profile_pic',
					Table::TYPE_TEXT,
					'64k',
					[],
					'Profile Picture'
				)
				->addColumn(
						'created_at',
						Table::TYPE_TIMESTAMP,
						null,
						['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
						'Created At'
				)->addColumn(
					'updated_at',
					Table::TYPE_TIMESTAMP,
					null,
					['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
					'Updated At')
				->setComment('Instagram Post Table');
			$installer->getConnection()->createTable($table);
        }
        return $installer;
    }

}