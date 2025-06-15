<?php

use Core\Database\DatabaseInterface;
use Core\Database\Migration\MigrationInterface;

return new class implements MigrationInterface {

    public function up(DatabaseInterface $db): void
    {
        $db->execute("
            CREATE TABLE IF NOT EXISTS tasks (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                task TEXT,
                status ENUM('done', 'undone') DEFAULT 'undone',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) character set utf8mb4 collate utf8mb4_unicode_ci;
        ");


    }

    public function down(DatabaseInterface $db): void
    {
        $db->execute("DROP TABLE IF EXISTS tasks");
    }
};
