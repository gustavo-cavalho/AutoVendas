<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250130182125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add a `status` field on the `vehicleStore` table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE vehicle_store ADD status VARCHAR(20) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE vehicle_store DROP status');
    }
}
