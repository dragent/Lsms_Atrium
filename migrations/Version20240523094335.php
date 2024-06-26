<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240523094335 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE care ADD component_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE care ADD CONSTRAINT FK_6113A845E2ABAFFF FOREIGN KEY (component_id) REFERENCES objects (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6113A845E2ABAFFF ON care (component_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE care DROP FOREIGN KEY FK_6113A845E2ABAFFF');
        $this->addSql('DROP INDEX UNIQ_6113A845E2ABAFFF ON care');
        $this->addSql('ALTER TABLE care DROP component_id');
    }
}
