<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260217101138 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE articles ADD panier_id INT NOT NULL');
        $this->addSql('ALTER TABLE articles ADD CONSTRAINT FK_BFDD3168F77D927C FOREIGN KEY (panier_id) REFERENCES panier (id)');
        $this->addSql('CREATE INDEX IDX_BFDD3168F77D927C ON articles (panier_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE articles DROP FOREIGN KEY FK_BFDD3168F77D927C');
        $this->addSql('DROP INDEX IDX_BFDD3168F77D927C ON articles');
        $this->addSql('ALTER TABLE articles DROP panier_id');
    }
}
