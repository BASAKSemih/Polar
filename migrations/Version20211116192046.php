<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211116192046 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hiding_place ADD country_id INT NOT NULL');
        $this->addSql('ALTER TABLE hiding_place ADD CONSTRAINT FK_924939C1F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('CREATE INDEX IDX_924939C1F92F3E70 ON hiding_place (country_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hiding_place DROP FOREIGN KEY FK_924939C1F92F3E70');
        $this->addSql('DROP INDEX IDX_924939C1F92F3E70 ON hiding_place');
        $this->addSql('ALTER TABLE hiding_place DROP country_id');
    }
}
