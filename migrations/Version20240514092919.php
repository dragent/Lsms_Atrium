<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240514092919 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE care_sheet (id INT AUTO_INCREMENT NOT NULL, medic_id INT NOT NULL, partner_id INT DEFAULT NULL, is_paid TINYINT(1) NOT NULL, date_care DATETIME NOT NULL, is_far_away TINYINT(1) NOT NULL, invoice INT NOT NULL, INDEX IDX_197F6B90409615FE (medic_id), INDEX IDX_197F6B909393F8FE (partner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE care_sheet_item (id INT AUTO_INCREMENT NOT NULL, caresheet_id INT NOT NULL, care_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_BBB28462BF3B79ED (caresheet_id), INDEX IDX_BBB28462F270FD45 (care_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partner (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE care_sheet ADD CONSTRAINT FK_197F6B90409615FE FOREIGN KEY (medic_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE care_sheet ADD CONSTRAINT FK_197F6B909393F8FE FOREIGN KEY (partner_id) REFERENCES partner (id)');
        $this->addSql('ALTER TABLE care_sheet_item ADD CONSTRAINT FK_BBB28462BF3B79ED FOREIGN KEY (caresheet_id) REFERENCES care_sheet (id)');
        $this->addSql('ALTER TABLE care_sheet_item ADD CONSTRAINT FK_BBB28462F270FD45 FOREIGN KEY (care_id) REFERENCES care (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE care_sheet DROP FOREIGN KEY FK_197F6B90409615FE');
        $this->addSql('ALTER TABLE care_sheet DROP FOREIGN KEY FK_197F6B909393F8FE');
        $this->addSql('ALTER TABLE care_sheet_item DROP FOREIGN KEY FK_BBB28462BF3B79ED');
        $this->addSql('ALTER TABLE care_sheet_item DROP FOREIGN KEY FK_BBB28462F270FD45');
        $this->addSql('DROP TABLE care_sheet');
        $this->addSql('DROP TABLE care_sheet_item');
        $this->addSql('DROP TABLE partner');
    }
}
