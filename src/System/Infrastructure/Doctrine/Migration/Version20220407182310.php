<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220407182310 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Introduces the locale for articles and categories.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE article_revision ADD locale VARCHAR(255) DEFAULT NULL');
        $this->addSql('UPDATE article_revision SET locale = ?', ['en_US']);
        $this->addSql('ALTER TABLE article_revision ALTER COLUMN locale SET NOT NULL');

        $this->addSql('ALTER TABLE category_revision ADD locale VARCHAR(255) DEFAULT NULL');
        $this->addSql('UPDATE category_revision SET locale = ?', ['en_US']);
        $this->addSql('ALTER TABLE category_revision ALTER COLUMN locale SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE category_revision DROP locale');
        $this->addSql('ALTER TABLE article_revision DROP locale');
    }
}
