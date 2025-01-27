<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250127140600 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds name and transaction_history to user table and create a index for name.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('
            ALTER TABLE user
                ADD name VARCHAR(180) NOT NULL,
                ADD transaction_history JSON NOT NULL;
            CREATE INDEX name_idx ON user (name);
        ');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('
            ALTER TABLE user DROP name, DROP transaction_history;
            DROP INDEX name_idx;
        ');
    }
}
