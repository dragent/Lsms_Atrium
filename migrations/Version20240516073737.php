<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240516073737 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE care_sheet DROP FOREIGN KEY FK_197F6B909393F8FE');
        $this->addSql('DROP INDEX IDX_197F6B909393F8FE ON care_sheet');
        $this->addSql('ALTER TABLE care_sheet ADD partner VARCHAR(255) NOT NULL, DROP partner_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE care_sheet ADD partner_id INT DEFAULT NULL, DROP partner');
        $this->addSql('ALTER TABLE care_sheet ADD CONSTRAINT FK_197F6B909393F8FE FOREIGN KEY (partner_id) REFERENCES partner (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_197F6B909393F8FE ON care_sheet (partner_id)');
    }
}
