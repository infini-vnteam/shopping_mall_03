<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170920083212 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        

        $table = $schema->createTable('messages');
        $table->addColumn('id', 'integer', ['autoincrement'=>true]);
        $table->addColumn('content', 'text');
        $table->addColumn('sender_id', 'integer');
        $table->addColumn('chatroom_id', 'integer');
        $table->addColumn('date_created', 'datetime', ['notnull'=>true]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine' , 'InnoDB');

        $chatroom = $schema->createTable('chatrooms');
        $chatroom->addColumn('id', 'integer', ['autoincrement'=>true]);
        $chatroom->addColumn('initor', 'integer');
        $chatroom->addColumn('user', 'integer');
        $chatroom->setPrimaryKey(['id']);
        $chatroom->addOption('engine' , 'InnoDB');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $schema->dropTable('messages');
        $schema->dropTable('chatrooms');
    }
}
