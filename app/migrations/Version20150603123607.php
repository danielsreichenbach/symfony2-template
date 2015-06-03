<?php

namespace App\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Adds Invitation entity as a requirement for creating a User entity
 *
 * @author  Daniel S. Reichenbach <daniel@kogitoapp.com>
 */
class Version20150603123607 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE fos_invitations (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(6) NOT NULL, email VARCHAR(256) DEFAULT NULL, sent TINYINT(1) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_6C2FDD5C77153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fos_users ADD invitation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fos_users ADD CONSTRAINT FK_32427CF7A35D7AF0 FOREIGN KEY (invitation_id) REFERENCES fos_invitations (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_32427CF7A35D7AF0 ON fos_users (invitation_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fos_users DROP FOREIGN KEY FK_32427CF7A35D7AF0');
        $this->addSql('DROP TABLE fos_invitations');
        $this->addSql('DROP INDEX UNIQ_32427CF7A35D7AF0 ON fos_users');
        $this->addSql('ALTER TABLE fos_users DROP invitation_id');
    }
}
