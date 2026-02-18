<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260217150938 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY `FK_6EEAA67DFB88E14F`');
        $this->addSql('DROP INDEX IDX_6EEAA67DFB88E14F ON commande');
        $this->addSql('ALTER TABLE commande DROP id_commande, DROP quantite, DROP prix_unitaire, DROP utilisateur_id');
        $this->addSql('ALTER TABLE ligne_commande DROP FOREIGN KEY `FK_3170B74B3E314AE8`');
        $this->addSql('DROP INDEX IDX_3170B74B3E314AE8 ON ligne_commande');
        $this->addSql('ALTER TABLE ligne_commande CHANGE id_commande commande_id INT NOT NULL');
        $this->addSql('ALTER TABLE ligne_commande ADD CONSTRAINT FK_3170B74B82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('CREATE INDEX IDX_3170B74B82EA2E54 ON ligne_commande (commande_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande ADD id_commande INT NOT NULL, ADD quantite INT NOT NULL, ADD prix_unitaire NUMERIC(10, 2) NOT NULL, ADD utilisateur_id INT NOT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT `FK_6EEAA67DFB88E14F` FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_6EEAA67DFB88E14F ON commande (utilisateur_id)');
        $this->addSql('ALTER TABLE ligne_commande DROP FOREIGN KEY FK_3170B74B82EA2E54');
        $this->addSql('DROP INDEX IDX_3170B74B82EA2E54 ON ligne_commande');
        $this->addSql('ALTER TABLE ligne_commande CHANGE commande_id id_commande INT NOT NULL');
        $this->addSql('ALTER TABLE ligne_commande ADD CONSTRAINT `FK_3170B74B3E314AE8` FOREIGN KEY (id_commande) REFERENCES commande (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_3170B74B3E314AE8 ON ligne_commande (id_commande)');
    }
}
