<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170801053710_user extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $table = $schema->createTable('users');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);        
        $table->addColumn('email', 'text');
        $table->addColumn('password', 'text');
        $table->addColumn('name', 'text');
        $table->addColumn('phone', 'text', ['notnull' => false]);
        $table->addColumn('role', 'integer', ['default' => 1]);
        $table->addColumn('address_id', 'integer', ['notnull' => false]);
        $table->addColumn('status', 'integer', ['notnull' => false]);
        $table->addColumn('token', 'text', ['notnull' => false]);
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
        $schema->dropTable('users');

    }
}
