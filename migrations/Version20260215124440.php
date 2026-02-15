<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260215124440 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE navigation_item (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, type VARCHAR(50) NOT NULL, url VARCHAR(500) DEFAULT NULL, route VARCHAR(255) DEFAULT NULL, position INT NOT NULL, isExternal TINYINT(1) NOT NULL, isVisible TINYINT(1) NOT NULL, createdAt DATETIME NOT NULL, INDEX IDX_289BF06C727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE navigation_item ADD CONSTRAINT FK_289BF06C727ACA70 FOREIGN KEY (parent_id) REFERENCES navigation_item (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE navigation_item DROP FOREIGN KEY FK_289BF06C727ACA70');
        $this->addSql('DROP TABLE navigation_item');
    }
}
