<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240517152208 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE component (id INT AUTO_INCREMENT NOT NULL, object_id INT DEFAULT NULL, quantity INT NOT NULL, INDEX IDX_49FEA157232D562B (object_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE component ADD CONSTRAINT FK_49FEA157232D562B FOREIGN KEY (object_id) REFERENCES objects (id)');
        $this->addSql('ALTER TABLE objects ADD item_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE objects ADD CONSTRAINT FK_B21ACCF3126F525E FOREIGN KEY (item_id) REFERENCES component (id)');
        $this->addSql('CREATE INDEX IDX_B21ACCF3126F525E ON objects (item_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE objects DROP FOREIGN KEY FK_B21ACCF3126F525E');
        $this->addSql('ALTER TABLE component DROP FOREIGN KEY FK_49FEA157232D562B');
        $this->addSql('DROP TABLE component');
        $this->addSql('DROP INDEX IDX_B21ACCF3126F525E ON objects');
        $this->addSql('ALTER TABLE objects DROP item_id');
    }
}
