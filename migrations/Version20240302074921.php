<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240302074921 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE text (id INT AUTO_INCREMENT NOT NULL, provider VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, text LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE text_term (id INT AUTO_INCREMENT NOT NULL, term VARCHAR(255) NOT NULL, score DOUBLE PRECISION NOT NULL, text_id INT DEFAULT NULL, INDEX IDX_A27199B8698D3548 (text_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE text_term ADD CONSTRAINT FK_A27199B8698D3548 FOREIGN KEY (text_id) REFERENCES text (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE text_term DROP FOREIGN KEY FK_A27199B8698D3548');
        $this->addSql('DROP TABLE text');
        $this->addSql('DROP TABLE text_term');
    }
}
