<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260217112219 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE articles ADD CONSTRAINT FK_BFDD3168F77D927C FOREIGN KEY (panier_id) REFERENCES panier (id)');
        $this->addSql('CREATE INDEX IDX_BFDD3168F77D927C ON articles (panier_id)');
        $this->addSql('ALTER TABLE ligne_panier ADD chiot_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ligne_panier ADD CONSTRAINT FK_21691B4CADBEF54 FOREIGN KEY (chiot_id) REFERENCES chiots (id)');
        $this->addSql('CREATE INDEX IDX_21691B4CADBEF54 ON ligne_panier (chiot_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE articles DROP FOREIGN KEY FK_BFDD3168F77D927C');
        $this->addSql('DROP INDEX IDX_BFDD3168F77D927C ON articles');
        $this->addSql('ALTER TABLE ligne_panier DROP FOREIGN KEY FK_21691B4CADBEF54');
        $this->addSql('DROP INDEX IDX_21691B4CADBEF54 ON ligne_panier');
        $this->addSql('ALTER TABLE ligne_panier DROP chiot_id');
    }
}
