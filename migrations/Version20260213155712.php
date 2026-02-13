<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260213155712 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, id_categorie INT NOT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE categorie_articles (categorie_id INT NOT NULL, articles_id INT NOT NULL, INDEX IDX_875A7002BCF5E72D (categorie_id), INDEX IDX_875A70021EBAF6CC (articles_id), PRIMARY KEY (categorie_id, articles_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE categorie_articles ADD CONSTRAINT FK_875A7002BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categorie_articles ADD CONSTRAINT FK_875A70021EBAF6CC FOREIGN KEY (articles_id) REFERENCES articles (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie_articles DROP FOREIGN KEY FK_875A7002BCF5E72D');
        $this->addSql('ALTER TABLE categorie_articles DROP FOREIGN KEY FK_875A70021EBAF6CC');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE categorie_articles');
    }
}
