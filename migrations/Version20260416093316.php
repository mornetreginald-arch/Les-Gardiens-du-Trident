<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260416093316 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, id_utilisateur INT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password_hash VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE articles CHANGE id id INT AUTO_INCREMENT NOT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE articles_commande ADD PRIMARY KEY (articles_id, commande_id)');
        $this->addSql('ALTER TABLE articles_commande ADD CONSTRAINT FK_CF7E50671EBAF6CC FOREIGN KEY (articles_id) REFERENCES articles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE articles_commande ADD CONSTRAINT FK_CF7E506782EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_CF7E50671EBAF6CC ON articles_commande (articles_id)');
        $this->addSql('CREATE INDEX IDX_CF7E506782EA2E54 ON articles_commande (commande_id)');
        $this->addSql('ALTER TABLE categorie CHANGE id id INT AUTO_INCREMENT NOT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE categorie_articles ADD PRIMARY KEY (categorie_id, articles_id)');
        $this->addSql('ALTER TABLE categorie_articles ADD CONSTRAINT FK_875A7002BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categorie_articles ADD CONSTRAINT FK_875A70021EBAF6CC FOREIGN KEY (articles_id) REFERENCES articles (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_875A7002BCF5E72D ON categorie_articles (categorie_id)');
        $this->addSql('CREATE INDEX IDX_875A70021EBAF6CC ON categorie_articles (articles_id)');
        $this->addSql('ALTER TABLE chiots CHANGE id id INT AUTO_INCREMENT NOT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE chiots ADD CONSTRAINT FK_111A483B82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('CREATE INDEX IDX_111A483B82EA2E54 ON chiots (commande_id)');
        $this->addSql('ALTER TABLE commande CHANGE id id INT AUTO_INCREMENT NOT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67DA76ED395 ON commande (user_id)');
        $this->addSql('ALTER TABLE ligne_commande CHANGE id id INT AUTO_INCREMENT NOT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE ligne_commande ADD CONSTRAINT FK_3170B74B82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE ligne_commande ADD CONSTRAINT FK_3170B74B1EBAF6CC FOREIGN KEY (articles_id) REFERENCES articles (id)');
        $this->addSql('ALTER TABLE ligne_commande ADD CONSTRAINT FK_3170B74BCADBEF54 FOREIGN KEY (chiot_id) REFERENCES chiots (id)');
        $this->addSql('CREATE INDEX IDX_3170B74B82EA2E54 ON ligne_commande (commande_id)');
        $this->addSql('CREATE INDEX IDX_3170B74B1EBAF6CC ON ligne_commande (articles_id)');
        $this->addSql('CREATE INDEX IDX_3170B74BCADBEF54 ON ligne_commande (chiot_id)');
        $this->addSql('ALTER TABLE ligne_panier CHANGE id id INT AUTO_INCREMENT NOT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE ligne_panier ADD CONSTRAINT FK_21691B41EBAF6CC FOREIGN KEY (articles_id) REFERENCES articles (id)');
        $this->addSql('ALTER TABLE ligne_panier ADD CONSTRAINT FK_21691B4CADBEF54 FOREIGN KEY (chiot_id) REFERENCES chiots (id)');
        $this->addSql('ALTER TABLE ligne_panier ADD CONSTRAINT FK_21691B4F77D927C FOREIGN KEY (panier_id) REFERENCES panier (id)');
        $this->addSql('CREATE INDEX IDX_21691B41EBAF6CC ON ligne_panier (articles_id)');
        $this->addSql('CREATE INDEX IDX_21691B4CADBEF54 ON ligne_panier (chiot_id)');
        $this->addSql('CREATE INDEX IDX_21691B4F77D927C ON ligne_panier (panier_id)');
        $this->addSql('ALTER TABLE messenger_messages CHANGE id id BIGINT AUTO_INCREMENT NOT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 ON messenger_messages (queue_name, available_at, delivered_at, id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('ALTER TABLE articles MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE articles CHANGE id id INT NOT NULL, DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE articles_commande DROP FOREIGN KEY FK_CF7E50671EBAF6CC');
        $this->addSql('ALTER TABLE articles_commande DROP FOREIGN KEY FK_CF7E506782EA2E54');
        $this->addSql('DROP INDEX IDX_CF7E50671EBAF6CC ON articles_commande');
        $this->addSql('DROP INDEX IDX_CF7E506782EA2E54 ON articles_commande');
        $this->addSql('ALTER TABLE articles_commande DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE categorie MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE categorie CHANGE id id INT NOT NULL, DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE categorie_articles DROP FOREIGN KEY FK_875A7002BCF5E72D');
        $this->addSql('ALTER TABLE categorie_articles DROP FOREIGN KEY FK_875A70021EBAF6CC');
        $this->addSql('DROP INDEX IDX_875A7002BCF5E72D ON categorie_articles');
        $this->addSql('DROP INDEX IDX_875A70021EBAF6CC ON categorie_articles');
        $this->addSql('ALTER TABLE categorie_articles DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE chiots DROP FOREIGN KEY FK_111A483B82EA2E54');
        $this->addSql('DROP INDEX IDX_111A483B82EA2E54 ON chiots');
        $this->addSql('ALTER TABLE chiots MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE chiots CHANGE id id INT NOT NULL, DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DA76ED395');
        $this->addSql('DROP INDEX IDX_6EEAA67DA76ED395 ON commande');
        $this->addSql('ALTER TABLE commande MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE commande CHANGE id id INT NOT NULL, DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE ligne_commande DROP FOREIGN KEY FK_3170B74B82EA2E54');
        $this->addSql('ALTER TABLE ligne_commande DROP FOREIGN KEY FK_3170B74B1EBAF6CC');
        $this->addSql('ALTER TABLE ligne_commande DROP FOREIGN KEY FK_3170B74BCADBEF54');
        $this->addSql('DROP INDEX IDX_3170B74B82EA2E54 ON ligne_commande');
        $this->addSql('DROP INDEX IDX_3170B74B1EBAF6CC ON ligne_commande');
        $this->addSql('DROP INDEX IDX_3170B74BCADBEF54 ON ligne_commande');
        $this->addSql('ALTER TABLE ligne_commande MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE ligne_commande CHANGE id id INT NOT NULL, DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE ligne_panier DROP FOREIGN KEY FK_21691B41EBAF6CC');
        $this->addSql('ALTER TABLE ligne_panier DROP FOREIGN KEY FK_21691B4CADBEF54');
        $this->addSql('ALTER TABLE ligne_panier DROP FOREIGN KEY FK_21691B4F77D927C');
        $this->addSql('DROP INDEX IDX_21691B41EBAF6CC ON ligne_panier');
        $this->addSql('DROP INDEX IDX_21691B4CADBEF54 ON ligne_panier');
        $this->addSql('DROP INDEX IDX_21691B4F77D927C ON ligne_panier');
        $this->addSql('ALTER TABLE ligne_panier MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE ligne_panier CHANGE id id INT NOT NULL, DROP PRIMARY KEY');
        $this->addSql('DROP INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 ON messenger_messages');
        $this->addSql('ALTER TABLE messenger_messages MODIFY id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE id id BIGINT NOT NULL, DROP PRIMARY KEY');
    }
}
