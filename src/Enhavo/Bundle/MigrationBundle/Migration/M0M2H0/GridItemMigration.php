<?php

/**
 * GridItems.php
 *
 * @since 25/07/16
 * @author gseidel
 */

namespace Enhavo\Bundle\MigrationBundle\Migration\M0M2H0;

use Enhavo\Bundle\MigrationBundle\Migration\AbstractMigration;

class GridItemMigration extends AbstractMigration
{
    public function migrate()
    {
        $this->executeSql("CREATE TABLE grid_item_three_picture (id INT AUTO_INCREMENT NOT NULL, titleLeft VARCHAR(255) DEFAULT NULL, titleRight VARCHAR(255) DEFAULT NULL, titleCenter VARCHAR(255) DEFAULT NULL, captionLeft VARCHAR(255) DEFAULT NULL, captionRight VARCHAR(255) DEFAULT NULL, captionCenter VARCHAR(255) DEFAULT NULL, fileLeft_id INT DEFAULT NULL, fileRight_id INT DEFAULT NULL, fileCenter_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_33689F6E6CF536A2 (fileLeft_id), UNIQUE INDEX UNIQ_33689F6E82CF5225 (fileRight_id), UNIQUE INDEX UNIQ_33689F6E4453BB29 (fileCenter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;");
        $this->executeSql("ALTER TABLE grid_item_three_picture ADD CONSTRAINT FK_33689F6E6CF536A2 FOREIGN KEY (fileLeft_id) REFERENCES media_file (id);");
        $this->executeSql("ALTER TABLE grid_item_three_picture ADD CONSTRAINT FK_33689F6E82CF5225 FOREIGN KEY (fileRight_id) REFERENCES media_file (id);");
        $this->executeSql("ALTER TABLE grid_item_three_picture ADD CONSTRAINT FK_33689F6E4453BB29 FOREIGN KEY (fileCenter_id) REFERENCES media_file (id);");
        $this->executeSql("ALTER TABLE grid_item_picture_picture DROP frame;");
        $this->executeSql("ALTER TABLE grid_item_text_picture ADD caption VARCHAR(255) DEFAULT NULL, CHANGE textLeft textLeft TINYINT(1) DEFAULT NULL, CHANGE frame `float` TINYINT(1) DEFAULT NULL;");
        $this->executeSql("ALTER TABLE search_dataset ADD rawdata LONGTEXT DEFAULT NULL;");
        $this->executeSql("ALTER TABLE workflow_workflow CHANGE entity entity LONGTEXT DEFAULT NULL COMMENT '(DC2Type:array)';");
    }
}