<?php

use Core\Database\DatabaseInterface;
use Core\Database\Migration\MigrationInterface;

class m1_create_tasks_table implements MigrationInterface
{

    public function up(DatabaseInterface $db): void
    {
       $db->execute("
            CREATE TABLE IF NOT EXISTS tasks (
                id UNSIGNED BIGINT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                description TEXT,
                status ENUM('done', 'undone') DEFAULT 'undone',
                parent_id UNSIGNED BIGINT DEFAULT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        ");
         $db->execute("
                ALTER TABLE tasks ADD CONSTRAINT fk_parent_task
                FOREIGN KEY (parent_id) REFERENCES tasks(id);
        ");

    }

    public function down(DatabaseInterface $db): void
    {
        $db->execute("ALTER TABLE tasks DROP FOREIGN KEY fk_parent_task");
        $db->execute("DROP TABLE IF EXISTS tasks");
    }
}