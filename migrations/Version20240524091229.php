<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240524091229 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, medic_id INT NOT NULL, order_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', receive_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', invoice INT NOT NULL, INDEX IDX_F5299398409615FE (medic_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_items (id INT AUTO_INCREMENT NOT NULL, order_link_id INT NOT NULL, object_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_62809DB05BDEAB3A (order_link_id), INDEX IDX_62809DB0232D562B (object_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398409615FE FOREIGN KEY (medic_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE order_items ADD CONSTRAINT FK_62809DB05BDEAB3A FOREIGN KEY (order_link_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE order_items ADD CONSTRAINT FK_62809DB0232D562B FOREIGN KEY (object_id) REFERENCES objects (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398409615FE');
        $this->addSql('ALTER TABLE order_items DROP FOREIGN KEY FK_62809DB05BDEAB3A');
        $this->addSql('ALTER TABLE order_items DROP FOREIGN KEY FK_62809DB0232D562B');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_items');
    }
}
