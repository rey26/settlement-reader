<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231012164155 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE settlement_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE settlement (id INT NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, mayor_name VARCHAR(255) NOT NULL, city_hall_address VARCHAR(255) NOT NULL, phone VARCHAR(130) DEFAULT NULL, fax VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, coat_of_arms_path VARCHAR(255) DEFAULT NULL, web_address VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DD9F1B51727ACA70 ON settlement (parent_id)');
        $this->addSql('CREATE INDEX IDX_DD9F1B515E237E06 ON settlement (name)');
        $this->addSql('ALTER TABLE settlement ADD CONSTRAINT FK_DD9F1B51727ACA70 FOREIGN KEY (parent_id) REFERENCES settlement (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE settlement_id_seq CASCADE');
        $this->addSql('ALTER TABLE settlement DROP CONSTRAINT FK_DD9F1B51727ACA70');
        $this->addSql('DROP TABLE settlement');
    }
}
