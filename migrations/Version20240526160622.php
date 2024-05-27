<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240526160622 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comptability (id INT AUTO_INCREMENT NOT NULL, treasury INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, comptability_id INT NOT NULL, reason VARCHAR(255) NOT NULL, price INT NOT NULL, done_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', income TINYINT(1) NOT NULL, INDEX IDX_723705D11E2BBDC3 (comptability_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D11E2BBDC3 FOREIGN KEY (comptability_id) REFERENCES comptability (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D11E2BBDC3');
        $this->addSql('DROP TABLE comptability');
        $this->addSql('DROP TABLE transaction');
    }
}
