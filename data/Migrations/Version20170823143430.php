<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170823143430 extends AbstractMigration
{
/**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs

        $table = $schema->getTable('districts');
        $table->addForeignKeyConstraint(
            'provinces',
            ['province_id'],
            ['id'],
            [], 
            'district_province_id_fk'
            );
        $table = $schema->getTable('addresses');
        $table->addForeignKeyConstraint(
            'districts',
            ['district_id'],
            ['id'],
            [], 
            'address_district_id_fk'
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

        // $table = $schema->getTable('messages');
        // $table->addForeignKeyConstraint(
        //     'users',
        //     ['user_id'],
        //     ['id'],
        //     [], 
        //     'message_user_id_fk'
        //     );
        // $table->addForeignKeyConstraint(
        //     'chat_boxs',
        //     ['chat_box_id'],
        //     ['id'],
        //     [],
        //     'message_chat_box_id_fk'
        //     );


        $table = $schema->getTable('products');
        $table->addForeignKeyConstraint(
            'categories',
            ['category_id'],
            ['id'],
            ['onDelete' => 'SET NULL'],
            'product_category_id_fk'
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
            'product_masters',
            ['product_master_id'],
            ['id'], 
            [], 
            'orderitem_product_master_id_fk'
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

        $table = $schema->getTable('reviews');
        $table->addForeignKeyConstraint(
            'products', 
            ['product_id'], 
            ['id'], 
            [], 
            'review_product_id_fk'
            );
        $table->addForeignKeyConstraint(
            'users', 
            ['user_id'], 
            ['id'], 
            [], 
            'review_user_id_fk'
            );

        $table = $schema->getTable('product_masters');
        $table->addForeignKeyConstraint(
            'products', 
            ['product_id'], 
            ['id'], 
            [], 
            'product_master_product_id_fk'
            );

        $table = $schema->getTable('product_color_images');
        $table->addForeignKeyConstraint(
            'products', 
            ['product_id'], 
            ['id'], 
            [], 
            'product_color_image_product_id_fk'
            );

        $table = $schema->getTable('sales');
        $table->addForeignKeyConstraint(
            'sale_programs', 
            ['sale_program_id'], 
            ['id'], 
            [], 
            'sale_sale_program_id_fk'
            );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $table = $schema->getTable('sales');
        $table->removeForeignKey('sale_sale_program_id_fk');

        $table = $schema->getTable('product_color_images');
        $table->removeForeignKey('product_color_image_product_id_fk');

        $table = $schema->getTable('product_masters');
        $table->removeForeignKey('product_master_product_id_fk');
        

        $table = $schema->getTable('reviews');
        $table->removeForeignKey('review_product_id_fk');
        $table->removeForeignKey('review_user_id_fk');

        $table = $schema->getTable('product_keywords');
        $table->removeForeignKey('product_keyword_product_id_fk');
        $table->removeForeignKey('product_keyword_keyword_id_fk');


        $table = $schema->getTable('order_items');
        $table->removeForeignKey('orderitem_order_id_fk');
        $table->removeForeignKey('orderitem_product_master_id_fk');

        $table = $schema->getTable('orders');
        $table->removeForeignKey('order_user_id_fk');

        $table = $schema->getTable('products');
        $table->removeForeignKey('product_category_id_fk');

        // $table = $schema->getTable('messages');
        // $table->removeForeignKey('message_user_id_fk');
        // $table->removeForeignKey('message_chat_box_id_fk');

        $table = $schema->getTable('comments');
        $table->removeForeignKey('comment_product_id_fk');
        $table->removeForeignKey('comment_user_id_fk');

        $table = $schema->getTable('addresses');
        $table->removeForeignKey('address_district_id_fk');

        $table = $schema->getTable('districts');
        $table->removeForeignKey('district_province_id_fk');
    }
}
