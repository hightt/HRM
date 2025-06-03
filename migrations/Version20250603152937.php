<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250603152937 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE leave_request (id INT AUTO_INCREMENT NOT NULL, employee_id INT NOT NULL, reviewed_by_id INT DEFAULT NULL, leave_type VARCHAR(255) NOT NULL, date_from DATE NOT NULL, date_to DATE NOT NULL, status VARCHAR(255) NOT NULL, comment LONGTEXT DEFAULT NULL, manager_comment LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7DC8F7788C03F15C (employee_id), INDEX IDX_7DC8F778FC6B21F1 (reviewed_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE leave_request ADD CONSTRAINT FK_7DC8F7788C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
        $this->addSql('ALTER TABLE leave_request ADD CONSTRAINT FK_7DC8F778FC6B21F1 FOREIGN KEY (reviewed_by_id) REFERENCES employee (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE leave_request DROP FOREIGN KEY FK_7DC8F7788C03F15C');
        $this->addSql('ALTER TABLE leave_request DROP FOREIGN KEY FK_7DC8F778FC6B21F1');
        $this->addSql('DROP TABLE leave_request');
    }
}
