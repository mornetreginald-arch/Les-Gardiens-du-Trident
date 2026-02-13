<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260213155040 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE articles (id INT AUTO_INCREMENT NOT NULL, id_produit INT NOT NULL, nom_produit VARCHAR(255) NOT NULL, prix NUMERIC(10, 2) NOT NULL, stock INT NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE articles_commande (articles_id INT NOT NULL, commande_id INT NOT NULL, INDEX IDX_CF7E50671EBAF6CC (articles_id), INDEX IDX_CF7E506782EA2E54 (commande_id), PRIMARY KEY (articles_id, commande_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE articles_commande ADD CONSTRAINT FK_CF7E50671EBAF6CC FOREIGN KEY (articles_id) REFERENCES articles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE articles_commande ADD CONSTRAINT FK_CF7E506782EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE articles_commande DROP FOREIGN KEY FK_CF7E50671EBAF6CC');
        $this->addSql('ALTER TABLE articles_commande DROP FOREIGN KEY FK_CF7E506782EA2E54');
        $this->addSql('DROP TABLE articles');
        $this->addSql('DROP TABLE articles_commande');
    }
}
