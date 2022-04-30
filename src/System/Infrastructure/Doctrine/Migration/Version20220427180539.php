<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220427180539 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Removes the restriction of deleting locales if they are still in use.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE article_revision DROP CONSTRAINT FK_388BE1A7E559DFD1');
        $this->addSql('ALTER TABLE article_revision 
                       ADD CONSTRAINT FK_388BE1A7E559DFD1 
                       FOREIGN KEY (locale_id) 
                       REFERENCES application_locale (id) 
                       ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql('ALTER TABLE category_revision DROP CONSTRAINT FK_DA1991B7E559DFD1');
        $this->addSql('ALTER TABLE category_revision  
                       ADD CONSTRAINT FK_DA1991B7E559DFD1  
                       FOREIGN KEY (locale_id)  
                       REFERENCES application_locale (id)  
                       ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE category_revision DROP CONSTRAINT fk_da1991b7e559dfd1');
        $this->addSql('ALTER TABLE category_revision 
                       ADD CONSTRAINT fk_da1991b7e559dfd1 
                       FOREIGN KEY (locale_id) 
                       REFERENCES application_locale (id) 
                       ON DELETE RESTRICT NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql('ALTER TABLE article_revision DROP CONSTRAINT fk_388be1a7e559dfd1');
        $this->addSql('ALTER TABLE article_revision  
                       ADD CONSTRAINT fk_388be1a7e559dfd1  
                       FOREIGN KEY (locale_id)  
                       REFERENCES application_locale (id)  
                       ON DELETE RESTRICT NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
