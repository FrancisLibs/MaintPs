<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201220182106 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item CHANGE designation item_designation VARCHAR(255) NOT NULL, CHANGE reference item_reference VARCHAR(255) DEFAULT NULL, CHANGE quantitie item_quantitie INT NOT NULL');
        $this->addSql('ALTER TABLE `order` ADD delivery_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD first_name VARCHAR(255) NOT NULL, ADD last_name VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item CHANGE item_designation designation VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE item_reference reference VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE item_quantitie quantitie INT NOT NULL');
        $this->addSql('ALTER TABLE `order` DROP delivery_date');
        $this->addSql('ALTER TABLE `user` DROP first_name, DROP last_name');
    }
}
