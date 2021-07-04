<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210709192453 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__message AS SELECT id, date, name, to_recipients, text_content, html_content, raw_content, cc_recipients, bcc_recipients, attachments, headers, read, mail_group, subject FROM message');
        $this->addSql('DROP TABLE message');
        $this->addSql('CREATE TABLE message (id BLOB NOT NULL --(DC2Type:uuid)
        , date DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , name CLOB NOT NULL COLLATE BINARY --(DC2Type:address)
        , to_recipients CLOB NOT NULL COLLATE BINARY --(DC2Type:addresses)
        , text_content CLOB NOT NULL COLLATE BINARY, html_content CLOB DEFAULT NULL COLLATE BINARY, raw_content CLOB NOT NULL COLLATE BINARY, cc_recipients CLOB NOT NULL COLLATE BINARY --(DC2Type:addresses)
        , bcc_recipients CLOB NOT NULL COLLATE BINARY --(DC2Type:addresses)
        , attachments CLOB NOT NULL COLLATE BINARY --(DC2Type:attachments)
        , headers CLOB NOT NULL COLLATE BINARY --(DC2Type:headers)
        , read BOOLEAN NOT NULL, subject CLOB DEFAULT NULL COLLATE BINARY, mail_group VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO message (id, date, name, to_recipients, text_content, html_content, raw_content, cc_recipients, bcc_recipients, attachments, headers, read, mail_group, subject) SELECT id, date, name, to_recipients, text_content, html_content, raw_content, cc_recipients, bcc_recipients, attachments, headers, read, mail_group, subject FROM __temp__message');
        $this->addSql('DROP TABLE __temp__message');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__message AS SELECT id, date, name, subject, to_recipients, text_content, html_content, raw_content, headers, cc_recipients, bcc_recipients, attachments, read, mail_group FROM message');
        $this->addSql('DROP TABLE message');
        $this->addSql('CREATE TABLE message (id BLOB NOT NULL --(DC2Type:uuid)
        , date DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , name CLOB NOT NULL --(DC2Type:address)
        , subject CLOB DEFAULT NULL, to_recipients CLOB NOT NULL --(DC2Type:addresses)
        , text_content CLOB NOT NULL, html_content CLOB DEFAULT NULL, raw_content CLOB NOT NULL, headers CLOB NOT NULL --(DC2Type:headers)
        , cc_recipients CLOB NOT NULL --(DC2Type:addresses)
        , bcc_recipients CLOB NOT NULL --(DC2Type:addresses)
        , attachments CLOB NOT NULL --(DC2Type:attachments)
        , read BOOLEAN NOT NULL, mail_group VARCHAR(255) DEFAULT NULL COLLATE BINARY, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO message (id, date, name, subject, to_recipients, text_content, html_content, raw_content, headers, cc_recipients, bcc_recipients, attachments, read, mail_group) SELECT id, date, name, subject, to_recipients, text_content, html_content, raw_content, headers, cc_recipients, bcc_recipients, attachments, read, mail_group FROM __temp__message');
        $this->addSql('DROP TABLE __temp__message');
    }
}
