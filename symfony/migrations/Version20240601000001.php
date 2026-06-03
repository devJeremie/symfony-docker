<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240601000001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Création des tables destination, travel et booking';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE destination (
            id INT AUTO_INCREMENT NOT NULL,
            name VARCHAR(100) NOT NULL,
            country VARCHAR(100) NOT NULL,
            description LONGTEXT DEFAULT NULL,
            iata_code VARCHAR(10) DEFAULT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE travel (
            id INT AUTO_INCREMENT NOT NULL,
            destination_id INT NOT NULL,
            title VARCHAR(150) NOT NULL,
            departure_date DATE NOT NULL,
            return_date DATE NOT NULL,
            price NUMERIC(10, 2) NOT NULL,
            max_participants INT NOT NULL,
            description LONGTEXT DEFAULT NULL,
            status VARCHAR(20) NOT NULL DEFAULT \'draft\',
            created_at DATETIME NOT NULL,
            INDEX IDX_26B7784B816C6140 (destination_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE booking (
            id INT AUTO_INCREMENT NOT NULL,
            travel_id INT NOT NULL,
            first_name VARCHAR(100) NOT NULL,
            last_name VARCHAR(100) NOT NULL,
            email VARCHAR(180) NOT NULL,
            phone VARCHAR(20) DEFAULT NULL,
            number_of_persons INT NOT NULL,
            total_price NUMERIC(10, 2) NOT NULL,
            status VARCHAR(20) NOT NULL DEFAULT \'pending\',
            booked_at DATETIME NOT NULL,
            INDEX IDX_E00CEDDE9D1C3019 (travel_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE travel
            ADD CONSTRAINT FK_26B7784B816C6140
            FOREIGN KEY (destination_id) REFERENCES destination (id)');

        $this->addSql('ALTER TABLE booking
            ADD CONSTRAINT FK_E00CEDDE9D1C3019
            FOREIGN KEY (travel_id) REFERENCES travel (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE9D1C3019');
        $this->addSql('ALTER TABLE travel DROP FOREIGN KEY FK_26B7784B816C6140');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE travel');
        $this->addSql('DROP TABLE destination');
    }
}
