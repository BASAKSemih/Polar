<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211113125103 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE agent (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', birth_date DATE NOT NULL, biography LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE agent_speciality (agent_id INT NOT NULL, speciality_id INT NOT NULL, INDEX IDX_829171813414710B (agent_id), INDEX IDX_829171813B5A08D7 (speciality_id), PRIMARY KEY(agent_id, speciality_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE agent_nationality (agent_id INT NOT NULL, nationality_id INT NOT NULL, INDEX IDX_1A0BD0303414710B (agent_id), INDEX IDX_1A0BD0301C9DA55 (nationality_id), PRIMARY KEY(agent_id, nationality_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE agent_speciality ADD CONSTRAINT FK_829171813414710B FOREIGN KEY (agent_id) REFERENCES agent (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE agent_speciality ADD CONSTRAINT FK_829171813B5A08D7 FOREIGN KEY (speciality_id) REFERENCES speciality (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE agent_nationality ADD CONSTRAINT FK_1A0BD0303414710B FOREIGN KEY (agent_id) REFERENCES agent (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE agent_nationality ADD CONSTRAINT FK_1A0BD0301C9DA55 FOREIGN KEY (nationality_id) REFERENCES nationality (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agent_speciality DROP FOREIGN KEY FK_829171813414710B');
        $this->addSql('ALTER TABLE agent_nationality DROP FOREIGN KEY FK_1A0BD0303414710B');
        $this->addSql('DROP TABLE agent');
        $this->addSql('DROP TABLE agent_speciality');
        $this->addSql('DROP TABLE agent_nationality');
    }
}
