<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250324185621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employee CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE work_log CHANGE hours_number hours_number DOUBLE PRECISION DEFAULT NULL, CHANGE overtime_number overtime_number DOUBLE PRECISION DEFAULT NULL, CHANGE is_day_off is_day_off TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE work_log CHANGE hours_number hours_number DOUBLE PRECISION DEFAULT NULL, CHANGE overtime_number overtime_number DOUBLE PRECISION DEFAULT NULL, CHANGE is_day_off is_day_off TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE employee CHANGE user_id user_id INT DEFAULT NULL');
    }
}
