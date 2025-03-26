<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250326170028 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE absence_symbol (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(500) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE department (id INT AUTO_INCREMENT NOT NULL, manager_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, location VARCHAR(255) DEFAULT NULL, INDEX IDX_CD1DE18A783E3463 (manager_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employee (id INT AUTO_INCREMENT NOT NULL, department_id INT DEFAULT NULL, user_id INT NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, birthday_date DATETIME NOT NULL, pesel VARCHAR(11) NOT NULL, employment_date DATETIME NOT NULL, position VARCHAR(255) NOT NULL, phone_number VARCHAR(100) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, salary DOUBLE PRECISION NOT NULL, status TINYINT(1) NOT NULL, gender VARCHAR(1) NOT NULL, INDEX IDX_5D9F75A1AE80F5DF (department_id), UNIQUE INDEX UNIQ_5D9F75A1A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work_log (id INT AUTO_INCREMENT NOT NULL, employee_id INT NOT NULL, absence_symbol_id INT DEFAULT NULL, date DATETIME NOT NULL, hour_start DATETIME DEFAULT NULL, hour_end DATETIME DEFAULT NULL, hours_number DOUBLE PRECISION NOT NULL, overtime_number DOUBLE PRECISION NOT NULL, is_day_off TINYINT(1) NOT NULL, notes VARCHAR(4000) DEFAULT NULL, INDEX IDX_F5513F598C03F15C (employee_id), INDEX IDX_F5513F5978A959F6 (absence_symbol_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE department ADD CONSTRAINT FK_CD1DE18A783E3463 FOREIGN KEY (manager_id) REFERENCES employee (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A1AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE work_log ADD CONSTRAINT FK_F5513F598C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
        $this->addSql('ALTER TABLE work_log ADD CONSTRAINT FK_F5513F5978A959F6 FOREIGN KEY (absence_symbol_id) REFERENCES absence_symbol (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE department DROP FOREIGN KEY FK_CD1DE18A783E3463');
        $this->addSql('ALTER TABLE employee DROP FOREIGN KEY FK_5D9F75A1AE80F5DF');
        $this->addSql('ALTER TABLE employee DROP FOREIGN KEY FK_5D9F75A1A76ED395');
        $this->addSql('ALTER TABLE work_log DROP FOREIGN KEY FK_F5513F598C03F15C');
        $this->addSql('ALTER TABLE work_log DROP FOREIGN KEY FK_F5513F5978A959F6');
        $this->addSql('DROP TABLE absence_symbol');
        $this->addSql('DROP TABLE department');
        $this->addSql('DROP TABLE employee');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE work_log');
    }
}
