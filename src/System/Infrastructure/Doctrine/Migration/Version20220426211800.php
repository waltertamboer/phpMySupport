<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220426211800 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Introduces the connection between articles and categories and locales.';
    }

    public function up(Schema $schema): void
    {
        $this->renameArticleRevision($schema);
        $this->renameCategoryRevision($schema);
    }

    private function renameArticleRevision(Schema $schema): void
    {
        $this->addSql('ALTER TABLE article_revision ADD locale_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN article_revision.locale_id IS \'(DC2Type:uuid)\'');

        $this->addSql('ALTER TABLE article_revision 
                       ADD CONSTRAINT FK_388BE1A7E559DFD1 
                       FOREIGN KEY (locale_id) 
                       REFERENCES application_locale (id) 
                       ON DELETE RESTRICT NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql('CREATE INDEX IDX_388BE1A7E559DFD1 ON article_revision (locale_id)');
    }

    private function renameCategoryRevision(Schema $schema): void
    {
        $this->addSql('ALTER TABLE category_revision ADD locale_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN category_revision.locale_id IS \'(DC2Type:uuid)\'');

        $this->addSql('ALTER TABLE category_revision 
                       ADD CONSTRAINT FK_DA1991B7E559DFD1 
                       FOREIGN KEY (locale_id) 
                       REFERENCES application_locale (id) 
                       ON DELETE RESTRICT NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql('CREATE INDEX IDX_DA1991B7E559DFD1 ON category_revision (locale_id)');
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
