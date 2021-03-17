<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210102080254 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE delivery_form DROP FOREIGN KEY FK_831435D52B1C2395');
        $this->addSql('DROP INDEX IDX_831435D52B1C2395 ON delivery_form');
        $this->addSql('ALTER TABLE delivery_form CHANGE related_order_id order_id INT NOT NULL');
        $this->addSql('ALTER TABLE delivery_form ADD CONSTRAINT FK_831435D58D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id)');
        $this->addSql('CREATE INDEX IDX_831435D58D9F6D38 ON delivery_form (order_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE delivery_form DROP FOREIGN KEY FK_831435D58D9F6D38');
        $this->addSql('DROP INDEX IDX_831435D58D9F6D38 ON delivery_form');
        $this->addSql('ALTER TABLE delivery_form CHANGE order_id related_order_id INT NOT NULL');
        $this->addSql('ALTER TABLE delivery_form ADD CONSTRAINT FK_831435D52B1C2395 FOREIGN KEY (related_order_id) REFERENCES orders (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_831435D52B1C2395 ON delivery_form (related_order_id)');
    }
}
