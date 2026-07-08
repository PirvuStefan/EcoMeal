<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260708104850 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, consumer_id INT DEFAULT NULL, business_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D64937FDBD6D (consumer_id), UNIQUE INDEX UNIQ_8D93D649A89DB457 (business_id), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64937FDBD6D FOREIGN KEY (consumer_id) REFERENCES consumer (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649A89DB457 FOREIGN KEY (business_id) REFERENCES business (id)');
        $this->addSql('ALTER TABLE package CHANGE photo photo VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64937FDBD6D');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649A89DB457');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE package CHANGE photo photo VARCHAR(255) DEFAULT NULL');
    }
}
