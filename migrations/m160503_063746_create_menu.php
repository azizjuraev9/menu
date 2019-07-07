<?php

use yii\db\Migration;
use juraev\menu\helpers\InitHelper;

class m160503_063746_create_menu extends Migration
{
    public function safeUp()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull(),
        ]);
        $this->createTable('menu_links', [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer(),
            'menu_id' => $this->integer()->notNull(),
            'link' => $this->string(150)->notNull(),
            'thumb' => $this->string(100),
            'sort' => $this->integer(),
            'icon' => $this->string(50),
        ]);
        $this->createTable('menu_links_lang', [
            'id' => $this->primaryKey(),
            'main_id' => $this->integer()->notNull(),
            'lang' => $this->string(5)->notNull(),
            'name' => $this->string(150)->notNull(),
        ]);
        $this->createIndex(
            'idx-menu_links-parent_id',
            'menu_links',
            'parent_id'
        );
        $this->addForeignKey(
            'fk-menu_links-parent_id',
            'menu_links',
            'parent_id',
            'menu_links',
            'id',
            'SET NULL',
            'NO ACTION'
            );
        $this->createIndex(
            'idx-menu_links-menu_id',
            'menu_links',
            'menu_id'
        );
        $this->addForeignKey('fk-menu_links-menu_id',
            'menu_links',
            'menu_id',
            'menu',
            'id',
            'RESTRICT',
            'NO ACTION'
            );
        $this->createIndex(
            'idx-menu_links_lang-main_id',
            'menu_links_lang',
            'main_id'
        );
        $this->addForeignKey('fk-menu_links_lang-main_id',
            'menu_links_lang',
            'main_id',
            'menu_links',
            'id',
            'CASCADE',
            'NO ACTION'
            );
    }

    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-menu_links-parent_id',
            'menu_links'
        );
        $this->dropIndex(
            'idx-menu_links-parent_id',
            'menu_links'
        );
        $this->dropForeignKey(
            'fk-menu_links-menu_id',
            'menu_links'
        );
        $this->dropIndex(
            'idx-menu_links-menu_id',
            'menu_links'
        );
        $this->dropForeignKey(
            'fk-menu_links_lang-main_id',
            'menu_links_lang'
        );
        $this->dropIndex(
            'idx-menu_links_lang-main_id',
            'menu_links_lang'
        );
        $this->dropTable('menu');
        $this->dropTable('menu_links');
        $this->dropTable('menu_links_lang');
    }
}
