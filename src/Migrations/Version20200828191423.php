<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200828191423 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE cheque (numero INT NOT NULL, facture_id INT DEFAULT NULL, intitule VARCHAR(255) NOT NULL, proprietaire VARCHAR(255) NOT NULL, montant DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_A0BBFDE97F2DEE08 (facture_id), PRIMARY KEY(numero)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, cni VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cmd_produit (id VARCHAR(255) NOT NULL, produit_id INT NOT NULL, commande_id INT NOT NULL, qte INT NOT NULL, INDEX IDX_BFE8CC8EF347EFB (produit_id), INDEX IDX_BFE8CC8E82EA2E54 (commande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, facture_id INT DEFAULT NULL, created_at DATETIME NOT NULL, etat VARCHAR(255) NOT NULL, INDEX IDX_6EEAA67D19EB6921 (client_id), INDEX IDX_6EEAA67D7F2DEE08 (facture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entreprise (id INT AUTO_INCREMENT NOT NULL, designation VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entreprise_fournisseur (entreprise_id INT NOT NULL, fournisseur_id INT NOT NULL, INDEX IDX_1E96659A4AEAFEA (entreprise_id), INDEX IDX_1E96659670C757F (fournisseur_id), PRIMARY KEY(entreprise_id, fournisseur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entreprise_client (entreprise_id INT NOT NULL, client_id INT NOT NULL, INDEX IDX_9E52B862A4AEAFEA (entreprise_id), INDEX IDX_9E52B86219EB6921 (client_id), PRIMARY KEY(entreprise_id, client_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE facture (id INT AUTO_INCREMENT NOT NULL, montant DOUBLE PRECISION NOT NULL, etat VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, montant_restant DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fournisseur (id INT AUTO_INCREMENT NOT NULL, designation VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE info_paiement_espece (id INT AUTO_INCREMENT NOT NULL, facture_id INT DEFAULT NULL, montant DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_662E82D87F2DEE08 (facture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, designation VARCHAR(255) NOT NULL, type VARCHAR(255) DEFAULT NULL, prix DOUBLE PRECISION NOT NULL, qte_stock INT NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit_fournisseur (id VARCHAR(255) NOT NULL, fournisseur_id INT NOT NULL, produit_id INT NOT NULL, qte INT DEFAULT NULL, montant DOUBLE PRECISION NOT NULL, INDEX IDX_48868EB6670C757F (fournisseur_id), INDEX IDX_48868EB6F347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cheque ADD CONSTRAINT FK_A0BBFDE97F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id)');
        $this->addSql('ALTER TABLE cmd_produit ADD CONSTRAINT FK_BFE8CC8EF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE cmd_produit ADD CONSTRAINT FK_BFE8CC8E82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D7F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id)');
        $this->addSql('ALTER TABLE entreprise_fournisseur ADD CONSTRAINT FK_1E96659A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE entreprise_fournisseur ADD CONSTRAINT FK_1E96659670C757F FOREIGN KEY (fournisseur_id) REFERENCES fournisseur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE entreprise_client ADD CONSTRAINT FK_9E52B862A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE entreprise_client ADD CONSTRAINT FK_9E52B86219EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE info_paiement_espece ADD CONSTRAINT FK_662E82D87F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id)');
        $this->addSql('ALTER TABLE produit_fournisseur ADD CONSTRAINT FK_48868EB6670C757F FOREIGN KEY (fournisseur_id) REFERENCES fournisseur (id)');
        $this->addSql('ALTER TABLE produit_fournisseur ADD CONSTRAINT FK_48868EB6F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D19EB6921');
        $this->addSql('ALTER TABLE entreprise_client DROP FOREIGN KEY FK_9E52B86219EB6921');
        $this->addSql('ALTER TABLE cmd_produit DROP FOREIGN KEY FK_BFE8CC8E82EA2E54');
        $this->addSql('ALTER TABLE entreprise_fournisseur DROP FOREIGN KEY FK_1E96659A4AEAFEA');
        $this->addSql('ALTER TABLE entreprise_client DROP FOREIGN KEY FK_9E52B862A4AEAFEA');
        $this->addSql('ALTER TABLE cheque DROP FOREIGN KEY FK_A0BBFDE97F2DEE08');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D7F2DEE08');
        $this->addSql('ALTER TABLE info_paiement_espece DROP FOREIGN KEY FK_662E82D87F2DEE08');
        $this->addSql('ALTER TABLE entreprise_fournisseur DROP FOREIGN KEY FK_1E96659670C757F');
        $this->addSql('ALTER TABLE produit_fournisseur DROP FOREIGN KEY FK_48868EB6670C757F');
        $this->addSql('ALTER TABLE cmd_produit DROP FOREIGN KEY FK_BFE8CC8EF347EFB');
        $this->addSql('ALTER TABLE produit_fournisseur DROP FOREIGN KEY FK_48868EB6F347EFB');
        $this->addSql('DROP TABLE cheque');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE cmd_produit');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE entreprise');
        $this->addSql('DROP TABLE entreprise_fournisseur');
        $this->addSql('DROP TABLE entreprise_client');
        $this->addSql('DROP TABLE facture');
        $this->addSql('DROP TABLE fournisseur');
        $this->addSql('DROP TABLE info_paiement_espece');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE produit_fournisseur');
        $this->addSql('DROP TABLE user');
    }
}
