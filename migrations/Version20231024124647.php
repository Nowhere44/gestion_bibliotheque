<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231024124647 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE emprunt_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE emprunt (id INT NOT NULL, borrower_id INT NOT NULL, livre_id INT NOT NULL, date_emprunt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, date_retour TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_364071D711CE312B ON emprunt (borrower_id)');
        $this->addSql('CREATE INDEX IDX_364071D737D925CB ON emprunt (livre_id)');
        $this->addSql('ALTER TABLE emprunt ADD CONSTRAINT FK_364071D711CE312B FOREIGN KEY (borrower_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE emprunt ADD CONSTRAINT FK_364071D737D925CB FOREIGN KEY (livre_id) REFERENCES livre (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SCHEMA heroku_ext');
        $this->addSql('DROP SEQUENCE emprunt_id_seq CASCADE');
        $this->addSql('ALTER TABLE emprunt DROP CONSTRAINT FK_364071D711CE312B');
        $this->addSql('ALTER TABLE emprunt DROP CONSTRAINT FK_364071D737D925CB');
        $this->addSql('DROP TABLE emprunt');
    }
}
