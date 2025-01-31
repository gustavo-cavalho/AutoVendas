<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250130145959 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creation of the system\'s main tables, including advertisements, addresses, users, vehicles and vehicle stores, together with their respective foreign keys.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(
            'CREATE TABLE ad (
                id INT AUTO_INCREMENT NOT NULL,
                vehicle_advertised_id INT NOT NULL,
                advertiser_store_id INT NOT NULL,
                description LONGTEXT DEFAULT NULL,
                price NUMERIC(10, 2) NOT NULL,
                status VARCHAR(20) NOT NULL,
                created_at DATETIME NOT NULL COMMENT "(DC2Type:datetime_immutable)",
                updated_at DATETIME NOT NULL COMMENT "(DC2Type:datetime_immutable)",
                UNIQUE INDEX UNIQ_77E0ED58988C9E4B (vehicle_advertised_id),
                INDEX IDX_77E0ED582652A766 (advertiser_store_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );

        $this->addSql(
            'CREATE TABLE address (
                id INT AUTO_INCREMENT NOT NULL,
                vehicle_store_id INT NOT NULL,
                cep INT NOT NULL,
                street VARCHAR(255) NOT NULL,
                number INT NOT NULL,
                neighborhood VARCHAR(255) NOT NULL,
                city VARCHAR(255) NOT NULL,
                state VARCHAR(255) NOT NULL,
                complement VARCHAR(255) DEFAULT NULL,
                UNIQUE INDEX UNIQ_D4E6F816D80A785 (vehicle_store_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );

        $this->addSql(
            'CREATE TABLE user (
                id INT AUTO_INCREMENT NOT NULL,
                vehicle_store_id INT DEFAULT NULL,
                name VARCHAR(180) NOT NULL,
                email VARCHAR(180) NOT NULL,
                password VARCHAR(255) NOT NULL,
                roles JSON NOT NULL,
                UNIQUE INDEX UNIQ_8D93D649E7927C74 (email),
                INDEX IDX_8D93D6496D80A785 (vehicle_store_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );

        $this->addSql(
            'CREATE TABLE vehicle (
                id INT AUTO_INCREMENT NOT NULL,
                vehicle_store_id INT NOT NULL,
                type VARCHAR(50) NOT NULL,
                brand VARCHAR(20) NOT NULL,
                model VARCHAR(50) NOT NULL,
                manufactured_year INT NOT NULL,
                mileage INT NOT NULL,
                license_plate VARCHAR(7) NOT NULL,
                brand_integration INT NOT NULL,
                model_integration INT NOT NULL,
                year_integration VARCHAR(7) NOT NULL,
                INDEX IDX_1B80E4866D80A785 (vehicle_store_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );

        $this->addSql(
            'CREATE TABLE vehicle_store (
                id INT AUTO_INCREMENT NOT NULL,
                credencial VARCHAR(20) NOT NULL,
                phone VARCHAR(15) NOT NULL,
                email VARCHAR(255) NOT NULL,
                name VARCHAR(255) NOT NULL,
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );

        $this->addSql('ALTER TABLE ad ADD CONSTRAINT FK_77E0ED58988C9E4B FOREIGN KEY (vehicle_advertised_id) REFERENCES vehicle (id)');
        $this->addSql('ALTER TABLE ad ADD CONSTRAINT FK_77E0ED582652A766 FOREIGN KEY (advertiser_store_id) REFERENCES vehicle_store (id)');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F816D80A785 FOREIGN KEY (vehicle_store_id) REFERENCES vehicle_store (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6496D80A785 FOREIGN KEY (vehicle_store_id) REFERENCES vehicle_store (id)');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E4866D80A785 FOREIGN KEY (vehicle_store_id) REFERENCES vehicle_store (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ad DROP FOREIGN KEY FK_77E0ED58988C9E4B');
        $this->addSql('ALTER TABLE ad DROP FOREIGN KEY FK_77E0ED582652A766');
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F816D80A785');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6496D80A785');
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E4866D80A785');
        $this->addSql('DROP TABLE ad');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE vehicle');
        $this->addSql('DROP TABLE vehicle_store');
    }
}
