<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211116205523 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE nationality ADD country_id INT NOT NULL');
        $this->addSql('ALTER TABLE nationality ADD CONSTRAINT FK_8AC58B70F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8AC58B70F92F3E70 ON nationality (country_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE nationality DROP FOREIGN KEY FK_8AC58B70F92F3E70');
        $this->addSql('DROP INDEX UNIQ_8AC58B70F92F3E70 ON nationality');
        $this->addSql('ALTER TABLE nationality DROP country_id');
    }
}
