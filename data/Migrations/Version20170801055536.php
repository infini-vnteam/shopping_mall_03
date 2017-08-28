<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170801055536 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $table = $schema->getTable('notifications');
        $table->addForeignKeyConstraint(
            'users', 
            ['user_id'], 
            ['id'],
            [],
            'notification_user_id_fk'
            );

        $table = $schema->getTable('comments');
        $table->addForeignKeyConstraint(
            'users',
            ['user_id'],
            ['id'],
            [], 
            'comment_user_id_fk'
            );
        $table->addForeignKeyConstraint(
            'products',
            ['product_id'],
            ['id'],
            [],
            'comment_product_id_fk'
            );

        $table = $schema->getTable('messages');
        $table->addForeignKeyConstraint(
            'users',
            ['user_id'],
            ['id'],
            [], 
            'message_user_id_fk'
            );
        $table->addForeignKeyConstraint(
            'chat_boxs',
            ['chat_box_id'],
            ['id'],
            [],
            'message_chat_box_id_fk'
            );


        $table = $schema->getTable('products');
        $table->addForeignKeyConstraint(
            'categories',
            ['category_id'],
            ['id'],
            ['onDelete' => 'SET NULL'],
            'product_category_id_fk'
            );
        $table->addForeignKeyConstraint(
            'stores',
            ['store_id'],
            ['id'],
            ['onDelete' => 'SET NULL'],
            'product_store_id_fk'
            );

        $table = $schema->getTable('product_images');
        $table->addForeignKeyConstraint(
            'products',
            ['product_id'],
            ['id'],
            [],
            'product_image_product_id_fk'
            );

        $table = $schema->getTable('orders');
        $table->addForeignKeyConstraint(
            'users',
            ['user_id'],
            ['id'],
            [], 
            'order_user_id_fk'
            );

        $table = $schema->getTable('order_items');
        $table->addForeignKeyConstraint(
            'orders',
            ['order_id'],
            ['id'],
            [], 
            'orderitem_order_id_fk'
            );
        $table->addForeignKeyConstraint(
            'products',
            ['product_id'],
            ['id'], 
            [], 
            'orderitem_product_id_fk'
            );

        $table = $schema->getTable('product_keywords');
        $table->addForeignKeyConstraint(
            'products', 
            ['product_id'], 
            ['id'], 
            [], 
            'product_keyword_product_id_fk'
            );
        $table->addForeignKeyConstraint(
            'keywords', 
            ['keyword_id'], 
            ['id'], 
            [], 
            'product_keyword_keyword_id_fk'
            );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $table = $schema->getTable('product_keywords');
        $table->removeForeignKey('product_keyword_product_id_fk');
        $table->removeForeignKey('product_keyword_keyword_id_fk');


        $table = $schema->getTable('order_items');
        $table->removeForeignKey('orderitem_order_id_fk');
        $table->removeForeignKey('orderitem_product_id_fk');

        $table = $schema->getTable('orders');
        $table->removeForeignKey('order_user_id_fk');

        $table = $schema->getTable('product_images');
        $table->removeForeignKey('product_image_product_id_fk');

        $table = $schema->getTable('products');
        $table->removeForeignKey('product_store_id_fk');
        $table->removeForeignKey('product_category_id_fk');

        $table = $schema->getTable('messages');
        $table->removeForeignKey('message_user_id_fk');
        $table->removeForeignKey('message_chat_box_id_fk');

        $table = $schema->getTable('comments');
        $table->removeForeignKey('comment_product_id_fk');
        $table->removeForeignKey('comment_user_id_fk');

        $table = $schema->getTable('notifications');
        $table->removeForeignKey('notification_user_id_fk');

    }
}
