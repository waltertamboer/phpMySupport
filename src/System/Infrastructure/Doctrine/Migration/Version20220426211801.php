<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Ramsey\Uuid\Uuid;

final class Version20220426211801 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Converts the locales to uuids.';
    }

    public function up(Schema $schema): void
    {
        $registry = [];

        $this->loadArticleRevisions($registry);
        $this->loadCategoryRevisions($registry);

        $this->updateArticleRevisions($schema, $registry);
        $this->updateCategoryRevisions($schema, $registry);

        $this->addSql('ALTER TABLE article_revision DROP locale');
        $this->addSql('ALTER TABLE category_revision DROP locale');
    }

    private function loadArticleRevisions(array &$registry): void
    {
        $query = 'SELECT DISTINCT cr.locale, al.slug
                  FROM article_revision cr
                  LEFT JOIN application_locale al ON al.slug = cr.locale
                  WHERE al.slug IS NULL';
        $locales = $this->connection->fetchAllAssociative($query);

        $this->processLocales($locales, $registry);
    }

    private function loadCategoryRevisions(array &$registry): void
    {
        $query = 'SELECT DISTINCT cr.locale, al.slug
                  FROM category_revision cr
                  LEFT JOIN application_locale al ON al.slug = cr.locale
                  WHERE al.slug IS NULL';
        $locales = $this->connection->fetchAllAssociative($query);

        $this->processLocales($locales, $registry);
    }

    private function processLocales(array $locales, array &$registry): void
    {
        foreach ($locales as $item) {
            $locale = $item['locale'];

            if (array_key_exists($locale, $registry)) {
                continue;
            }

            $id = Uuid::uuid4()->toString();
            $registry[$locale] = $id;

            $sql = 'INSERT INTO application_locale 
                   (id, thumbnail_id, name, slug, selector_text, enabled)
                   VALUES
                   (?, ?, ?, ?, ?, ?)';

            $params = [];
            $params[] = $id;
            $params[] = null;
            $params[] = $locale;
            $params[] = $locale;
            $params[] = $locale;
            $params[] = true;

            $this->addSql($sql, $params);
        }
    }

    private function updateArticleRevisions(Schema $schema, array &$registry): void
    {
        foreach ($registry as $locale => $localeId) {
            $this->addSql('UPDATE article_revision SET locale_id = ? WHERE locale = ?', [
                $localeId,
                $locale,
            ]);
        }
    }

    private function updateCategoryRevisions(Schema $schema, array &$registry): void
    {
        foreach ($registry as $locale => $localeId) {
            $this->addSql('UPDATE category_revision SET locale_id = ? WHERE locale = ?', [
                $localeId,
                $locale,
            ]);
        }
    }

    public function down(Schema $schema): void
    {
    }
}
