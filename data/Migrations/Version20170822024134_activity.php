<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170822024134_activity extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $table = $schema->createTable('activities');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);        
        $table->addColumn('sender_id', 'integer', ['notnull' => false]);
        $table->addColumn('order_id', 'integer', ['notnull' => false]);
        $table->addColumn('comment_id', 'integer', ['notnull' => false]);
        $table->addColumn('review_id', 'integer', ['notnull' => false]);
        $table->addColumn('receiver_id', 'integer', ['notnull' => false]);
        $table->addColumn('type', 'integer');
        $table->addColumn('status', 'integer');
        $table->addColumn('date_created', 'datetime');
        $table->setPrimaryKey(['id']);
        $table->addOption('engine', 'InnoDB');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $schema->dropTable('activities');
    }
}
