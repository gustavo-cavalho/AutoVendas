<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250128142503 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the `Vehicle` table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE vehicle (
                id INT AUTO_INCREMENT NOT NULL, 
                car_store_id INT NOT NULL, 
                brand VARCHAR(20) NOT NULL, 
                model VARCHAR(50) NOT NULL, 
                manufactured_year DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', 
                mileage VARCHAR(10) NOT NULL, 
                fuel_type VARCHAR(20) NOT NULL, 
                license_plate VARCHAR(20) NOT NULL, 
                INDEX IDX_1B80E4863FA49D15 (car_store_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE vehicle');
    }
}
