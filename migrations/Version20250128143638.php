<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250128143638 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the `Ad` table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE ad (
                id INT AUTO_INCREMENT NOT NULL, 
                vehicle_advertised_id INT NOT NULL, 
                advertiser_store_id INT NOT NULL, 
                description LONGTEXT DEFAULT NULL, 
                price NUMERIC(10, 2) NOT NULL, 
                status VARCHAR(20) NOT NULL, 
                created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', 
                updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', 
                UNIQUE INDEX UNIQ_77E0ED58988C9E4B (vehicle_advertised_id), 
                INDEX IDX_77E0ED582652A766 (advertiser_store_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE ad');
    }
}
