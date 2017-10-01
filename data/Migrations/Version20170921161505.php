<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170921161505 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $chatroom = $schema->createTable('notifications');
        $chatroom->addColumn('id', 'integer', ['autoincrement'=>true]);
        $chatroom->addColumn('content', 'text');
        $chatroom->addColumn('status', 'integer');
        $chatroom->setPrimaryKey(['id']);
        $chatroom->addOption('engine' , 'InnoDB');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $schema->dropTable('notifications');

    }
}
