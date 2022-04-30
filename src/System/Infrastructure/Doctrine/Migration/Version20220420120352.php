<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220420120352 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Introduces the locale table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE application_locale (
            id UUID NOT NULL, 
            thumbnail_id UUID DEFAULT NULL, 
            name VARCHAR(255) NOT NULL, 
            slug VARCHAR(255) NOT NULL, 
            selector_text VARCHAR(255) DEFAULT NULL, 
            enabled BOOLEAN NOT NULL,
            PRIMARY KEY(id)
          )');

        $this->addSql('CREATE INDEX IDX_F1B3CA5DFDFF2E92 ON application_locale (thumbnail_id)');

        $this->addSql('COMMENT ON COLUMN application_locale.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN application_locale.thumbnail_id IS \'(DC2Type:uuid)\'');

        $this->addSql('ALTER TABLE application_locale 
                       ADD CONSTRAINT FK_F1B3CA5DFDFF2E92 
                       FOREIGN KEY (thumbnail_id) 
                       REFERENCES media_file (id) 
                       ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE application_locale');
    }
}
