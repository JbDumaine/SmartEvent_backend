<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201126093304 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE auth_access_tokens (id VARCHAR(255) NOT NULL, user_id INT UNSIGNED NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX auth_access_tokens_unique (id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, organizer_id INT DEFAULT NULL, type_id INT DEFAULT NULL, event_title VARCHAR(255) NOT NULL, event_date DATETIME NOT NULL, event_description LONGTEXT NOT NULL, picture_path VARCHAR(255) DEFAULT NULL, event_address LONGTEXT NOT NULL, INDEX IDX_3BAE0AA7876C4DDA (organizer_id), INDEX IDX_3BAE0AA7C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_type (id INT AUTO_INCREMENT NOT NULL, type_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE events_items (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, item_id INT NOT NULL, is_checked TINYINT(1) NOT NULL, INDEX IDX_51A2216B71F7E88B (event_id), INDEX IDX_51A2216B126F525E (item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invitation (id INT AUTO_INCREMENT NOT NULL, event_id INT DEFAULT NULL, guest_id INT DEFAULT NULL, status_id INT DEFAULT NULL, email VARCHAR(255) NOT NULL, invitation_token VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, INDEX IDX_F11D61A271F7E88B (event_id), INDEX IDX_F11D61A29A4AA658 (guest_id), INDEX IDX_F11D61A26BF700BD (status_id), UNIQUE INDEX invitation_invitation_token (invitation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, item_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE status (id INT AUTO_INCREMENT NOT NULL, status_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(180) NOT NULL, first_name VARCHAR(50) NOT NULL, last_name VARCHAR(100) NOT NULL, phone_number VARCHAR(15) NOT NULL, avatar_path VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contacts (user_id INT NOT NULL, contact_id INT NOT NULL, INDEX IDX_33401573A76ED395 (user_id), INDEX IDX_33401573E7A1254A (contact_id), PRIMARY KEY(user_id, contact_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7876C4DDA FOREIGN KEY (organizer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7C54C8C93 FOREIGN KEY (type_id) REFERENCES event_type (id)');
        $this->addSql('ALTER TABLE events_items ADD CONSTRAINT FK_51A2216B71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE events_items ADD CONSTRAINT FK_51A2216B126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE invitation ADD CONSTRAINT FK_F11D61A271F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE invitation ADD CONSTRAINT FK_F11D61A29A4AA658 FOREIGN KEY (guest_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE invitation ADD CONSTRAINT FK_F11D61A26BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE contacts ADD CONSTRAINT FK_33401573A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE contacts ADD CONSTRAINT FK_33401573E7A1254A FOREIGN KEY (contact_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE events_items DROP FOREIGN KEY FK_51A2216B71F7E88B');
        $this->addSql('ALTER TABLE invitation DROP FOREIGN KEY FK_F11D61A271F7E88B');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7C54C8C93');
        $this->addSql('ALTER TABLE events_items DROP FOREIGN KEY FK_51A2216B126F525E');
        $this->addSql('ALTER TABLE invitation DROP FOREIGN KEY FK_F11D61A26BF700BD');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7876C4DDA');
        $this->addSql('ALTER TABLE invitation DROP FOREIGN KEY FK_F11D61A29A4AA658');
        $this->addSql('ALTER TABLE contacts DROP FOREIGN KEY FK_33401573A76ED395');
        $this->addSql('ALTER TABLE contacts DROP FOREIGN KEY FK_33401573E7A1254A');
        $this->addSql('DROP TABLE auth_access_tokens');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_type');
        $this->addSql('DROP TABLE events_items');
        $this->addSql('DROP TABLE invitation');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE contacts');
    }
}
