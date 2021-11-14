<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211114120227 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mission (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, country VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, speciality VARCHAR(255) NOT NULL, date_start DATE NOT NULL, date_end DATE NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE agent ADD mission_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE agent ADD CONSTRAINT FK_268B9C9DBE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id)');
        $this->addSql('CREATE INDEX IDX_268B9C9DBE6CAE90 ON agent (mission_id)');
        $this->addSql('ALTER TABLE contact ADD mission_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E638BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id)');
        $this->addSql('CREATE INDEX IDX_4C62E638BE6CAE90 ON contact (mission_id)');
        $this->addSql('ALTER TABLE hiding_place ADD mission_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hiding_place ADD CONSTRAINT FK_924939C1BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id)');
        $this->addSql('CREATE INDEX IDX_924939C1BE6CAE90 ON hiding_place (mission_id)');
        $this->addSql('ALTER TABLE target ADD mission_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE target ADD CONSTRAINT FK_466F2FFCBE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id)');
        $this->addSql('CREATE INDEX IDX_466F2FFCBE6CAE90 ON target (mission_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agent DROP FOREIGN KEY FK_268B9C9DBE6CAE90');
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E638BE6CAE90');
        $this->addSql('ALTER TABLE hiding_place DROP FOREIGN KEY FK_924939C1BE6CAE90');
        $this->addSql('ALTER TABLE target DROP FOREIGN KEY FK_466F2FFCBE6CAE90');
        $this->addSql('DROP TABLE mission');
        $this->addSql('DROP INDEX IDX_268B9C9DBE6CAE90 ON agent');
        $this->addSql('ALTER TABLE agent DROP mission_id');
        $this->addSql('DROP INDEX IDX_4C62E638BE6CAE90 ON contact');
        $this->addSql('ALTER TABLE contact DROP mission_id');
        $this->addSql('DROP INDEX IDX_924939C1BE6CAE90 ON hiding_place');
        $this->addSql('ALTER TABLE hiding_place DROP mission_id');
        $this->addSql('DROP INDEX IDX_466F2FFCBE6CAE90 ON target');
        $this->addSql('ALTER TABLE target DROP mission_id');
    }
}
