<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250128143229 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add the field `CarStore` in the `User` table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'ALTER TABLE user
                ADD car_store_id INT DEFAULT NULL,
                ADD INDEX IDX_8D93D6493FA49D15 (car_store_id)'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(
            'ALTER TABLE user
                DROP INDEX IDX_8D93D6493FA49D15,
                DROP car_store_id'
        );
    }
}
