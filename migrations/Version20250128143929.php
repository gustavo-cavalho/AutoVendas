<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250128143929 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add the constraints and the foreign keys betwwen tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE ad ADD CONSTRAINT FK_77E0ED58988C9E4B FOREIGN KEY (vehicle_advertised_id) REFERENCES vehicle (id)');
        $this->addSql('ALTER TABLE ad ADD CONSTRAINT FK_77E0ED582652A766 FOREIGN KEY (advertiser_store_id) REFERENCES car_store (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6493FA49D15 FOREIGN KEY (car_store_id) REFERENCES car_store (id)');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E4863FA49D15 FOREIGN KEY (car_store_id) REFERENCES car_store (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE ad DROP FOREIGN KEY FK_77E0ED58988C9E4B');
        $this->addSql('ALTER TABLE ad DROP FOREIGN KEY FK_77E0ED582652A766');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6493FA49D15');
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E4863FA49D15');
    }
}
