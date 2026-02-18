<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260217150435 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ligne_commande DROP FOREIGN KEY `FK_3170B74B82EA2E54`');
        $this->addSql('DROP INDEX IDX_3170B74B82EA2E54 ON ligne_commande');
        $this->addSql('ALTER TABLE ligne_commande ADD id_commande INT NOT NULL, DROP commande_id');
        $this->addSql('ALTER TABLE ligne_commande ADD CONSTRAINT FK_3170B74B3E314AE8 FOREIGN KEY (id_commande) REFERENCES commande (id)');
        $this->addSql('CREATE INDEX IDX_3170B74B3E314AE8 ON ligne_commande (id_commande)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ligne_commande DROP FOREIGN KEY FK_3170B74B3E314AE8');
        $this->addSql('DROP INDEX IDX_3170B74B3E314AE8 ON ligne_commande');
        $this->addSql('ALTER TABLE ligne_commande ADD commande_id INT DEFAULT NULL, DROP id_commande');
        $this->addSql('ALTER TABLE ligne_commande ADD CONSTRAINT `FK_3170B74B82EA2E54` FOREIGN KEY (commande_id) REFERENCES commande (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_3170B74B82EA2E54 ON ligne_commande (commande_id)');
    }
}
