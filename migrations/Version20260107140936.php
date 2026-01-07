<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260107140936 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(80) NOT NULL, color VARCHAR(7) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE coaster_categorie (coaster_id INT NOT NULL, categorie_id INT NOT NULL, INDEX IDX_B869E678216303C (coaster_id), INDEX IDX_B869E678BCF5E72D (categorie_id), PRIMARY KEY(coaster_id, categorie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE park (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(80) NOT NULL, country VARCHAR(2) NOT NULL, opening_year INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE coaster_categorie ADD CONSTRAINT FK_B869E678216303C FOREIGN KEY (coaster_id) REFERENCES coaster (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE coaster_categorie ADD CONSTRAINT FK_B869E678BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE coaster ADD author_id INT DEFAULT NULL, ADD max_height INT DEFAULT NULL, ADD published TINYINT(1) DEFAULT NULL, ADD image_file_name VARCHAR(255) DEFAULT NULL, CHANGE mex_height park_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE coaster ADD CONSTRAINT FK_F6312A7844990C25 FOREIGN KEY (park_id) REFERENCES park (id)');
        $this->addSql('ALTER TABLE coaster ADD CONSTRAINT FK_F6312A78F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_F6312A7844990C25 ON coaster (park_id)');
        $this->addSql('CREATE INDEX IDX_F6312A78F675F31B ON coaster (author_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE coaster DROP FOREIGN KEY FK_F6312A7844990C25');
        $this->addSql('ALTER TABLE coaster DROP FOREIGN KEY FK_F6312A78F675F31B');
        $this->addSql('ALTER TABLE coaster_categorie DROP FOREIGN KEY FK_B869E678216303C');
        $this->addSql('ALTER TABLE coaster_categorie DROP FOREIGN KEY FK_B869E678BCF5E72D');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE coaster_categorie');
        $this->addSql('DROP TABLE park');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX IDX_F6312A7844990C25 ON coaster');
        $this->addSql('DROP INDEX IDX_F6312A78F675F31B ON coaster');
        $this->addSql('ALTER TABLE coaster ADD mex_height INT DEFAULT NULL, DROP park_id, DROP author_id, DROP max_height, DROP published, DROP image_file_name');
    }
}
