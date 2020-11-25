<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201125020626 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE events_items (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, item_id INT NOT NULL, is_checked TINYINT(1) NOT NULL, INDEX IDX_51A2216B71F7E88B (event_id), INDEX IDX_51A2216B126F525E (item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE events_items ADD CONSTRAINT FK_51A2216B71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE events_items ADD CONSTRAINT FK_51A2216B126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
        $this->addSql('CREATE UNIQUE INDEX invitation_invitation_token ON invitation (invitation_token)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE events_items');
        $this->addSql('DROP INDEX invitation_invitation_token ON invitation');
    }
}
