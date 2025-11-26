<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251126103317 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE coaster_categorie (coaster_id INT NOT NULL, categorie_id INT NOT NULL, INDEX IDX_B869E678216303C (coaster_id), INDEX IDX_B869E678BCF5E72D (categorie_id), PRIMARY KEY(coaster_id, categorie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE coaster_categorie ADD CONSTRAINT FK_B869E678216303C FOREIGN KEY (coaster_id) REFERENCES coaster (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE coaster_categorie ADD CONSTRAINT FK_B869E678BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE park CHANGE country country VARCHAR(20) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE coaster_categorie DROP FOREIGN KEY FK_B869E678216303C');
        $this->addSql('ALTER TABLE coaster_categorie DROP FOREIGN KEY FK_B869E678BCF5E72D');
        $this->addSql('DROP TABLE coaster_categorie');
        $this->addSql('ALTER TABLE park CHANGE country country VARCHAR(2) NOT NULL');
    }
}
