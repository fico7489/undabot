<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240228124020 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE provider_text (id INT AUTO_INCREMENT NOT NULL, provider VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, text LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE provider_text_term (id INT AUTO_INCREMENT NOT NULL, term VARCHAR(255) NOT NULL, score DOUBLE PRECISION NOT NULL, provider_text_id INT DEFAULT NULL, INDEX IDX_7BEF810A99D597EF (provider_text_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE provider_text_term ADD CONSTRAINT FK_7BEF810A99D597EF FOREIGN KEY (provider_text_id) REFERENCES provider_text (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE provider_text_term DROP FOREIGN KEY FK_7BEF810A99D597EF');
        $this->addSql('DROP TABLE provider_text');
        $this->addSql('DROP TABLE provider_text_term');
    }
}
