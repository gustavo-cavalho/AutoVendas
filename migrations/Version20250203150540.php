<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250203150540 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Fix the "vehicle.plate" length and change de "vehicle.year" type, cause it can contains letters';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'ALTER TABLE vehicle
                CHANGE license_plate license_plate VARCHAR(8) NOT NULL,
                CHANGE manufactured_year manufactured_year VARCHAR(20) NOT NULL
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql(
            'ALTER TABLE vehicle
                CHANGE license_plate license_plate VARCHAR(7) NOT NULL,
                CHANGE manufactured_year manufactured_year INT NOT NULL
        ');
    }
}
