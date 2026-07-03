<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260702113649 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE business (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, city VARCHAR(50) NOT NULL, street VARCHAR(255) NOT NULL, house_number VARCHAR(10) NOT NULL, phone_number VARCHAR(20) NOT NULL, business_type_id INT NOT NULL, INDEX IDX_8D36E38987F37DE (business_type_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE business_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, package_id INT NOT NULL, consumer_id INT NOT NULL, UNIQUE INDEX UNIQ_F5299398F44CABFF (package_id), INDEX IDX_F529939837FDBD6D (consumer_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE package (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(100) NOT NULL, price DOUBLE PRECISION NOT NULL, photo VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, category_id INT NOT NULL, business_id INT NOT NULL, INDEX IDX_DE68679512469DE2 (category_id), INDEX IDX_DE686795A89DB457 (business_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE business ADD CONSTRAINT FK_8D36E38987F37DE FOREIGN KEY (business_type_id) REFERENCES business_type (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398F44CABFF FOREIGN KEY (package_id) REFERENCES package (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939837FDBD6D FOREIGN KEY (consumer_id) REFERENCES consumer (id)');
        $this->addSql('ALTER TABLE package ADD CONSTRAINT FK_DE68679512469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE package ADD CONSTRAINT FK_DE686795A89DB457 FOREIGN KEY (business_id) REFERENCES business (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE business DROP FOREIGN KEY FK_8D36E38987F37DE');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398F44CABFF');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F529939837FDBD6D');
        $this->addSql('ALTER TABLE package DROP FOREIGN KEY FK_DE68679512469DE2');
        $this->addSql('ALTER TABLE package DROP FOREIGN KEY FK_DE686795A89DB457');
        $this->addSql('DROP TABLE business');
        $this->addSql('DROP TABLE business_type');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE package');
    }
}
