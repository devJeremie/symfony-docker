<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260603080213 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE testimonial (id INT AUTO_INCREMENT NOT NULL, author_name VARCHAR(100) NOT NULL, content LONGTEXT NOT NULL, rating INT NOT NULL, destination VARCHAR(100) DEFAULT NULL, created_at DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE booking CHANGE status status VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE booking RENAME INDEX idx_e00cedde9d1c3019 TO IDX_E00CEDDEECAB15B3');
        $this->addSql('ALTER TABLE travel ADD category VARCHAR(30) DEFAULT NULL, CHANGE status status VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE travel RENAME INDEX idx_26b7784b816c6140 TO IDX_2D0B6BCE816C6140');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE testimonial');
        $this->addSql('ALTER TABLE booking CHANGE status status VARCHAR(20) DEFAULT \'pending\' NOT NULL');
        $this->addSql('ALTER TABLE booking RENAME INDEX idx_e00ceddeecab15b3 TO IDX_E00CEDDE9D1C3019');
        $this->addSql('ALTER TABLE travel DROP category, CHANGE status status VARCHAR(20) DEFAULT \'draft\' NOT NULL');
        $this->addSql('ALTER TABLE travel RENAME INDEX idx_2d0b6bce816c6140 TO IDX_26B7784B816C6140');
    }
}
