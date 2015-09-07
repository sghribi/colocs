<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150908003930 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE house_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE fos_user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE house (id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, summary TEXT DEFAULT NULL, type INT NOT NULL, address TEXT NOT NULL, latitude NUMERIC(10, 6) DEFAULT NULL, longitude NUMERIC(10, 6) DEFAULT NULL, createdAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updatedAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, createdFromIp VARCHAR(45) DEFAULT NULL, updatedFromIp VARCHAR(45) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE fos_user (id INT NOT NULL, house_id INT DEFAULT NULL, username VARCHAR(255) NOT NULL, username_canonical VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, email_canonical VARCHAR(255) NOT NULL, enabled BOOLEAN NOT NULL, salt VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, last_login TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, locked BOOLEAN NOT NULL, expired BOOLEAN NOT NULL, expires_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, password_requested_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, roles TEXT NOT NULL, credentials_expired BOOLEAN NOT NULL, credentials_expire_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, photo TEXT DEFAULT NULL, createdAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updatedAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, createdFromIp VARCHAR(45) DEFAULT NULL, updatedFromIp VARCHAR(45) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_957A647992FC23A8 ON fos_user (username_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_957A6479A0D96FBF ON fos_user (email_canonical)');
        $this->addSql('CREATE INDEX IDX_957A64796BB74515 ON fos_user (house_id)');
        $this->addSql('COMMENT ON COLUMN fos_user.roles IS \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE fos_user ADD CONSTRAINT FK_957A64796BB74515 FOREIGN KEY (house_id) REFERENCES house (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE fos_user DROP CONSTRAINT FK_957A64796BB74515');
        $this->addSql('DROP SEQUENCE house_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE fos_user_id_seq CASCADE');
        $this->addSql('DROP TABLE house');
        $this->addSql('DROP TABLE fos_user');
    }
}
