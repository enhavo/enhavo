<?php

namespace Enhavo\Bundle\MigrationBundle\Service;

use Doctrine\ORM\EntityManager;
use Enhavo\Bundle\MigrationBundle\Exception\DatabaseVersionException;
use Symfony\Component\Console\Output\OutputInterface;

class DbMediaConnectionsConverter
{
    /**
     * @var DatabaseModifier
     */
    protected $databaseModifier;

    private $nrQueries;

    public function __construct(DatabaseModifier $databaseModifier)
    {
        $this->databaseModifier = $databaseModifier;
    }

    public function convertDatabase(OutputInterface $output)
    {
        $dbVersion = $this->databaseModifier->getDatabaseVersion();
        if ($dbVersion !== null) {
            throw new DatabaseVersionException(sprintf('This script is not meant to run on the current database version (%s).', $dbVersion));
        }

        $this->nrQueries = 0;

        // Add new columns
        $this->addColumnsToTableIfExists('article_article', array('picture_id'));
        $this->addColumnsToTableIfExists('page_page', array('picture_id'));
        $this->addColumnsToTableIfExists('category_category', array('picture_id'));
        $this->addColumnsToTableIfExists('content_type_picture', array('file_id'));
        $this->addColumnsToTableIfExists('content_type_text_picture', array('file_id'));
        $this->addColumnsToTableIfExists('content_type_picture_picture', array('fileLeft_id', 'fileRight_id'));
        $this->addColumnsToTableIfExists('content_item_download', array('file_id'));
        $this->addColumnsToTableIfExists('download_download', array('file_id'));
        $this->addColumnsToTableIfExists('slider_slide', array('image_id'));
        $this->addColumnsToTableIfExists('calendar_appointment', array('picture_id'));

        // Copy connections
        $this->copyConnectionsIfTableExists('article_article', 'id', 'picture_id', 'article_article_picture', 'article_id', 'file_id');
        $this->copyConnectionsIfTableExists('page_page', 'id', 'picture_id', 'page_page_picture', 'page_id', 'file_id');
        $this->copyConnectionsIfTableExists('category_category', 'id', 'picture_id', 'category_category_picture', 'category_id', 'file_id');
        $this->copyConnectionsIfTableExists('content_type_picture', 'id', 'file_id', 'content_picture_files', 'picture_id', 'file_id');
        $this->copyConnectionsIfTableExists('content_type_text_picture', 'id', 'file_id', 'content_textpicture_files', 'textpicture_id', 'file_id');
        $this->copyConnectionsIfTableExists('content_type_picture_picture', 'id', 'fileLeft_id', 'content_pictureleft_files', 'picturepicture_id', 'file_id');
        $this->copyConnectionsIfTableExists('content_type_picture_picture', 'id', 'fileRight_id', 'content_pictureright_files', 'picturepicture_id', 'file_id');
        $this->copyConnectionsIfTableExists('content_item_download', 'id', 'file_id', 'content_item_download_file', 'download_id', 'file_id');
        $this->copyConnectionsIfTableExists('download_download', 'id', 'file_id', 'download_download_file', 'download_id', 'file_id');
        $this->copyConnectionsIfTableExists('slider_slide', 'id', 'image_id', 'slider_slide_image', 'slider_id', 'file_id');
        $this->copyConnectionsIfTableExists('calendar_appointment', 'id', 'picture_id', 'calendar_appointment_picture', 'appointment_id', 'file_id');

        // Drop old join tables
        $this->dropJoinTableIfExists('article_article_picture');
        $this->dropJoinTableIfExists('page_page_picture');
        $this->dropJoinTableIfExists('category_category_picture');
        $this->dropJoinTableIfExists('content_picture_files');
        $this->dropJoinTableIfExists('content_textpicture_files');
        $this->dropJoinTableIfExists('content_pictureleft_files');
        $this->dropJoinTableIfExists('content_pictureright_files');
        $this->dropJoinTableIfExists('content_item_download_file');
        $this->dropJoinTableIfExists('download_download_file');
        $this->dropJoinTableIfExists('slider_slide_image');
        $this->dropJoinTableIfExists('calendar_appointment_picture');

        $this->databaseModifier->setDatabaseVersion("1.0.0");

        $output->writeln("Successfully converted media connection tables. $this->nrQueries SQL queries executed.");
    }

    /**
     * @param string $tableName
     * @param array $columnNames
     */
    private function addColumnsToTableIfExists($tableName, $columnNames)
    {
        if (!$this->databaseModifier->tableExists($tableName)) {
            return;
        }

        $sql = sprintf('ALTER TABLE %s ADD %s INT DEFAULT NULL', $tableName, $columnNames[0]);
        if (count($columnNames) > 1) {
            foreach($columnNames as $key => $columnName) {
                if ($key > 0) {
                    $sql .= sprintf(', ADD %s INT DEFAULT NULL', $columnName);
                }
            }
        }
        $this->databaseModifier->executeQuery($sql);
        $this->nrQueries++;

        foreach($columnNames as $columnName) {
            $this->databaseModifier->executeQuery(
                sprintf('ALTER TABLE %s ADD CONSTRAINT %s FOREIGN KEY (%s) REFERENCES media_file (id)',
                    $tableName,
                    $this->databaseModifier->generateDoctrineSchemaIdentifierName($tableName, $columnName, 'fk'),
                    $columnName
                )
            );
            $this->databaseModifier->executeQuery(
                sprintf('CREATE INDEX %s ON %s (%s)',
                    $this->databaseModifier->generateDoctrineSchemaIdentifierName($tableName, $columnName, 'idx'),
                    $tableName,
                    $columnName
                )
            );
            $this->nrQueries++;
        }
    }

    /**
     * @param string $targetTable
     * @param string $targetTableIdColumn
     * @param string $targetTableTargetColumn
     * @param string $sourceTable
     * @param string $sourceTableJoinColumn
     * @param string $sourceTableSourceColumn
     */
    private function copyConnectionsIfTableExists($targetTable, $targetTableIdColumn, $targetTableTargetColumn, $sourceTable, $sourceTableJoinColumn, $sourceTableSourceColumn)
    {
        if (!($this->databaseModifier->tableExists($targetTable) && $this->databaseModifier->tableExists($sourceTable))) {
            return;
        }

        $statement = $this->databaseModifier->executeQuery(
            sprintf('SELECT t0.%s, t0.%s FROM %s t0 INNER JOIN %s t1 ON t1.%s = t0.%s',
                $sourceTableJoinColumn,
                $sourceTableSourceColumn,
                $sourceTable,
                $targetTable,
                $targetTableIdColumn,
                $sourceTableJoinColumn
            )
        );
        $values = $statement->fetchAll();
        $this->nrQueries++;

        foreach($values as $value) {
            $this->databaseModifier->executeQuery(
                sprintf('UPDATE %s SET %s = :value WHERE %s = :id',
                    $targetTable,
                    $targetTableTargetColumn,
                    $targetTableIdColumn
                ),
                array(
                    'value' => $value[$sourceTableSourceColumn],
                    'id'    => $value[$sourceTableJoinColumn]
                )
            );
            $this->nrQueries++;
        }
    }

    private function dropJoinTableIfExists($joinTable)
    {
        if (!$this->databaseModifier->tableExists($joinTable)) {
            return;
        }

        $this->databaseModifier->executeQuery(
            sprintf('DROP TABLE %s',
                $joinTable
            )
        );
        $this->nrQueries++;
    }
}
