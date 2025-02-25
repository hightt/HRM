<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250225181339 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE department DROP FOREIGN KEY FK_CD1DE18A783E3463');
        $this->addSql('ALTER TABLE department ADD CONSTRAINT FK_CD1DE18A783E3463 FOREIGN KEY (manager_id) REFERENCES employee (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE department DROP FOREIGN KEY FK_CD1DE18A783E3463');
        $this->addSql('ALTER TABLE department ADD CONSTRAINT FK_CD1DE18A783E3463 FOREIGN KEY (manager_id) REFERENCES employee (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
