<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210321141152 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` RENAME INDEX idx_e52ffdeea53a8aa TO IDX_F5299398A53A8AA');
        $this->addSql('ALTER TABLE `order` RENAME INDEX idx_e52ffdeea76ed395 TO IDX_F5299398A76ED395');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` RENAME INDEX idx_f5299398a53a8aa TO IDX_E52FFDEEA53A8AA');
        $this->addSql('ALTER TABLE `order` RENAME INDEX idx_f5299398a76ed395 TO IDX_E52FFDEEA76ED395');
    }
}
