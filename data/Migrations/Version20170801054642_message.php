<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170801054642_message extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $table = $schema->createTable('messages');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);        
        $table->addColumn('content', 'text');
        $table->addColumn('sent_at', 'datetime', ['notnull' => false]);
        $table->addColumn('status', 'integer', ['default' => 1]);
        $table->addColumn('user_id', 'integer', ['notnull' => true]);
        $table->addColumn('chat_box_id', 'integer');
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
        $schema->dropTable('messages');
    }
}
