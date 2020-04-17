<?php

use yii\db\Migration;

/**
 * Class m200417_192200_img
 */
class m200417_192200_img extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->db->createCommand("
CREATE TABLE `img` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`entity_type` VARCHAR(20) NULL DEFAULT NULL COMMENT 'e.g. node',
	`entity_id` INT(10) UNSIGNED NULL DEFAULT NULL,
	`sort` MEDIUMINT(8) NOT NULL DEFAULT '99',
	`created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `entity_type_entity_id_sort` (`entity_type`, `entity_id`, `sort`)
)
COMMENT='Image, all entities could be connected.'
COLLATE='utf8_general_ci'
ENGINE=InnoDB
        ")->execute();

        $this->db->createCommand("
CREATE TABLE `images` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`img_id` INT(10) UNSIGNED NULL DEFAULT NULL,
	`type` VARCHAR(10) NULL DEFAULT 'default' COMMENT 'icon small medium large',
	`resolution` VARCHAR(10) NOT NULL COMMENT 'e.g. 1200x900',
	`file_size` INT(11) NULL DEFAULT NULL COMMENT 'bytes',
	`path` VARCHAR(200) NOT NULL COMMENT 'hdd full path to file from img_root folder',
	`created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `img_id_type` (`img_id`, `type`),
	CONSTRAINT `FK_images_img` FOREIGN KEY (`img_id`) REFERENCES `img` (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB        
        ")->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200417_192200_img cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200417_192200_img cannot be reverted.\n";

        return false;
    }
    */
}
