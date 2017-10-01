<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170801055016_product extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $table = $schema->createTable('products');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);        
        $table->addColumn('name', 'text');
        $table->addColumn('alias', 'text', ['notnull' => false]);
        $table->addColumn('price', 'integer');
        $table->addColumn('current_price', 'integer', ['notnull' => false]);
        $table->addColumn('intro', 'text');
        $table->addColumn('image', 'string', ['notnull' => false]);
        $table->addColumn('description', 'text', ['notnull' => false]);
        $table->addColumn('status', 'integer', ['default' => 1]);
        $table->addColumn('views', 'integer', ['default' => 0]);
        $table->addColumn('rate_sum', 'integer');
        $table->addColumn('rate_count', 'integer', ['default' => 0]);
        $table->addColumn('popular_level', 'integer', ['default' => 0]);
        $table->addColumn('category_id', 'integer', ['notnull' => false]);
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
        $schema->dropTable('products');
    }
}
