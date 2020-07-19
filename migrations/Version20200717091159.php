<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200717091159 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE blockchain (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fos_user (id INT AUTO_INCREMENT NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, telephone VARCHAR(20) DEFAULT NULL, adresse VARCHAR(150) DEFAULT NULL, iban VARCHAR(50) DEFAULT NULL, codepin VARCHAR(10) NOT NULL, date_naiss DATE DEFAULT NULL, username VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_957A647992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_957A6479A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_957A6479C05FB297 (confirmation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_chain (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, blockchain_id INT NOT NULL, seuil DOUBLE PRECISION NOT NULL, tranche DOUBLE PRECISION NOT NULL, cle VARCHAR(255) NOT NULL, active INT NOT NULL, INDEX IDX_53C0E392A76ED395 (user_id), INDEX IDX_53C0E39298073AE1 (blockchain_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_chain ADD CONSTRAINT FK_53C0E392A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE user_chain ADD CONSTRAINT FK_53C0E39298073AE1 FOREIGN KEY (blockchain_id) REFERENCES blockchain (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_chain DROP FOREIGN KEY FK_53C0E39298073AE1');
        $this->addSql('ALTER TABLE user_chain DROP FOREIGN KEY FK_53C0E392A76ED395');
        $this->addSql('DROP TABLE blockchain');
        $this->addSql('DROP TABLE fos_user');
        $this->addSql('DROP TABLE user_chain');
    }
}
