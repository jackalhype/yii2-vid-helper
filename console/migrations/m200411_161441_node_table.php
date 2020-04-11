<?php

use yii\db\Migration;

/**
 * Class m200411_161441_node_table
 */
class m200411_161441_node_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->db->createCommand("
CREATE TABLE IF NOT EXISTS `node` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`title` VARCHAR(180) NOT NULL DEFAULT '',
	`descr` VARCHAR(650) NOT NULL DEFAULT '',
	`sort` MEDIUMINT(9) NULL DEFAULT '999',
	`status` TINYINT(4) NOT NULL DEFAULT '1',
	`parent_id` INT(11) UNSIGNED NULL DEFAULT NULL,
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `FK_node_node` (`parent_id`),
	CONSTRAINT `FK_node_node` FOREIGN KEY (`parent_id`) REFERENCES `node` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;

        ")->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200411_161441_node_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200411_161441_node_table cannot be reverted.\n";

        return false;
    }
    */
}
