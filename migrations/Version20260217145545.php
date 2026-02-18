<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260217145545 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ligne_commande (id INT AUTO_INCREMENT NOT NULL, quantite INT NOT NULL, prix NUMERIC(10, 2) NOT NULL, commande_id INT DEFAULT NULL, articles_id INT DEFAULT NULL, chiot_id INT DEFAULT NULL, INDEX IDX_3170B74B82EA2E54 (commande_id), INDEX IDX_3170B74B1EBAF6CC (articles_id), INDEX IDX_3170B74BCADBEF54 (chiot_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE ligne_commande ADD CONSTRAINT FK_3170B74B82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE ligne_commande ADD CONSTRAINT FK_3170B74B1EBAF6CC FOREIGN KEY (articles_id) REFERENCES articles (id)');
        $this->addSql('ALTER TABLE ligne_commande ADD CONSTRAINT FK_3170B74BCADBEF54 FOREIGN KEY (chiot_id) REFERENCES chiots (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ligne_commande DROP FOREIGN KEY FK_3170B74B82EA2E54');
        $this->addSql('ALTER TABLE ligne_commande DROP FOREIGN KEY FK_3170B74B1EBAF6CC');
        $this->addSql('ALTER TABLE ligne_commande DROP FOREIGN KEY FK_3170B74BCADBEF54');
        $this->addSql('DROP TABLE ligne_commande');
    }
}
