<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260329133819 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__house AS SELECT id, external_id, title, monthly_rent, energy_label, city, title_grade, rent_grade, energy_grade, overall_grade, status FROM house');
        $this->addSql('DROP TABLE house');
        $this->addSql('CREATE TABLE house (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, external_id VARCHAR(8) NOT NULL, title VARCHAR(255) DEFAULT NULL, monthly_rent INTEGER DEFAULT NULL, energy_label VARCHAR(5) DEFAULT NULL, city VARCHAR(255) NOT NULL, title_grade VARCHAR(255) DEFAULT NULL, rent_grade VARCHAR(255) DEFAULT NULL, energy_grade VARCHAR(255) DEFAULT NULL, overall_grade VARCHAR(255) DEFAULT NULL, status VARCHAR(255) NOT NULL, area INTEGER DEFAULT NULL, room_count INTEGER DEFAULT NULL);');
        $this->addSql('INSERT INTO house (id, external_id, title, monthly_rent, energy_label, city, title_grade, rent_grade, energy_grade, overall_grade, status) SELECT id, external_id, title, monthly_rent, energy_label, city, title_grade, rent_grade, energy_grade, overall_grade, status FROM __temp__house');
        $this->addSql('DROP TABLE __temp__house');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__house AS SELECT id, external_id, title, monthly_rent, energy_label, city, title_grade, rent_grade, energy_grade, overall_grade, status FROM house');
        $this->addSql('DROP TABLE house');
        $this->addSql('CREATE TABLE house (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, external_id VARCHAR(8) NOT NULL, title VARCHAR(255) DEFAULT NULL, monthly_rent INTEGER DEFAULT NULL, energy_label VARCHAR(5) DEFAULT NULL, city VARCHAR(255) NOT NULL, title_grade VARCHAR(255) DEFAULT NULL, rent_grade VARCHAR(255) DEFAULT NULL, energy_grade VARCHAR(255) DEFAULT NULL, overall_grade VARCHAR(255) DEFAULT NULL, status VARCHAR(255) DEFAULT \'"Pending"\')');
        $this->addSql('INSERT INTO house (id, external_id, title, monthly_rent, energy_label, city, title_grade, rent_grade, energy_grade, overall_grade, status) SELECT id, external_id, title, monthly_rent, energy_label, city, title_grade, rent_grade, energy_grade, overall_grade, status FROM __temp__house');
        $this->addSql('DROP TABLE __temp__house');
    }
}
