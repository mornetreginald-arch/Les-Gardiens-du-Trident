<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260213154354 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chiots (id INT AUTO_INCREMENT NOT NULL, id_chiot INT NOT NULL, sexe VARCHAR(255) NOT NULL, couleur_collier VARCHAR(255) NOT NULL, commande_id INT DEFAULT NULL, INDEX IDX_111A483B82EA2E54 (commande_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE chiots ADD CONSTRAINT FK_111A483B82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chiots DROP FOREIGN KEY FK_111A483B82EA2E54');
        $this->addSql('DROP TABLE chiots');
    }
}
