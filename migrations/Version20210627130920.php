<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210627130920 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message ADD COLUMN headers CLOB NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__message AS SELECT id, date, name, to_recipients, text_content, html_content, raw_content, cc_recipients, bcc_recipients, attachments FROM message');
        $this->addSql('DROP TABLE message');
        $this->addSql('CREATE TABLE message (id BLOB NOT NULL --(DC2Type:uuid)
        , date DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , name CLOB NOT NULL --(DC2Type:address)
        , to_recipients CLOB NOT NULL --(DC2Type:addresses)
        , text_content CLOB NOT NULL, html_content CLOB DEFAULT NULL, raw_content CLOB NOT NULL, cc_recipients CLOB NOT NULL --(DC2Type:addresses)
        , bcc_recipients CLOB NOT NULL --(DC2Type:addresses)
        , attachments CLOB NOT NULL --(DC2Type:attachments)
        , PRIMARY KEY(id))');
        $this->addSql('INSERT INTO message (id, date, name, to_recipients, text_content, html_content, raw_content, cc_recipients, bcc_recipients, attachments) SELECT id, date, name, to_recipients, text_content, html_content, raw_content, cc_recipients, bcc_recipients, attachments FROM __temp__message');
        $this->addSql('DROP TABLE __temp__message');
    }
}
