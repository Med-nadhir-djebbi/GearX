<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250528180154 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Replace ratings array with rating float in Product entity';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP ratings');
        $this->addSql('ALTER TABLE product ADD rating DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP rating');
        $this->addSql('ALTER TABLE product ADD ratings JSON NOT NULL');
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
    }
}
