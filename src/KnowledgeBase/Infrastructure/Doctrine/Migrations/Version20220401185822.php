<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Domain\Article\Article;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220401185822 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('CREATE TABLE article_revision (id UUID NOT NULL, article_id UUID DEFAULT NULL, created_by_id UUID DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, body TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_388be1a77294869c ON article_revision (article_id)');
        $this->addSql('CREATE INDEX idx_388be1a7b03a8386 ON article_revision (created_by_id)');
        $this->addSql('COMMENT ON COLUMN article_revision.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN article_revision.article_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN article_revision.created_by_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN article_revision.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('CREATE TABLE category (id UUID NOT NULL, last_revision_id UUID DEFAULT NULL, created_by_id UUID DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_64c19c1b03a8386 ON category (created_by_id)');
        $this->addSql('CREATE INDEX idx_64c19c15dcc0383 ON category (last_revision_id)');
        $this->addSql('COMMENT ON COLUMN category.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN category.last_revision_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN category.created_by_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN category.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('CREATE TABLE admin_user (id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN admin_user.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN admin_user.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('CREATE TABLE article_revision_category (article_revision_id UUID NOT NULL, category_id UUID NOT NULL, PRIMARY KEY(article_revision_id, category_id))');
        $this->addSql('CREATE INDEX idx_850badb312469de2 ON article_revision_category (category_id)');
        $this->addSql('CREATE INDEX idx_850badb3877916e8 ON article_revision_category (article_revision_id)');
        $this->addSql('COMMENT ON COLUMN article_revision_category.article_revision_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN article_revision_category.category_id IS \'(DC2Type:uuid)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('CREATE TABLE media_file_revision (id UUID NOT NULL, file_id UUID DEFAULT NULL, created_by_id UUID DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, name VARCHAR(255) NOT NULL, mime_type VARCHAR(255) NOT NULL, size INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_3854a4f0b03a8386 ON media_file_revision (created_by_id)');
        $this->addSql('CREATE INDEX idx_3854a4f093cb796c ON media_file_revision (file_id)');
        $this->addSql('COMMENT ON COLUMN media_file_revision.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN media_file_revision.file_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN media_file_revision.created_by_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN media_file_revision.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('CREATE TABLE category_revision (id UUID NOT NULL, category_id UUID DEFAULT NULL, created_by_id UUID DEFAULT NULL, thumbnail_id UUID DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_da1991b7fdff2e92 ON category_revision (thumbnail_id)');
        $this->addSql('CREATE INDEX idx_da1991b712469de2 ON category_revision (category_id)');
        $this->addSql('CREATE INDEX idx_da1991b7b03a8386 ON category_revision (created_by_id)');
        $this->addSql('COMMENT ON COLUMN category_revision.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN category_revision.category_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN category_revision.created_by_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN category_revision.thumbnail_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN category_revision.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('CREATE TABLE article (id UUID NOT NULL, last_revision_id UUID DEFAULT NULL, created_by_id UUID DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_23a0e665dcc0383 ON article (last_revision_id)');
        $this->addSql('CREATE INDEX idx_23a0e66b03a8386 ON article (created_by_id)');
        $this->addSql('COMMENT ON COLUMN article.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN article.last_revision_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN article.created_by_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN article.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('CREATE TABLE media_file (id UUID NOT NULL, last_revision_id UUID DEFAULT NULL, created_by_id UUID DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_4fd8e9c3b03a8386 ON media_file (created_by_id)');
        $this->addSql('CREATE INDEX idx_4fd8e9c35dcc0383 ON media_file (last_revision_id)');
        $this->addSql('COMMENT ON COLUMN media_file.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN media_file.last_revision_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN media_file.created_by_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN media_file.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('CREATE TABLE article_revision_view (id UUID NOT NULL, article_revision_id UUID DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, ip_address VARCHAR(255) NOT NULL, user_agent VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_f5e3f6d4877916e8 ON article_revision_view (article_revision_id)');
        $this->addSql('COMMENT ON COLUMN article_revision_view.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN article_revision_view.article_revision_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN article_revision_view.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('CREATE TABLE category_revision_view (id UUID NOT NULL, category_revision_id UUID DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, ip_address VARCHAR(255) NOT NULL, user_agent VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_6ce33c3aaa613ee4 ON category_revision_view (category_revision_id)');
        $this->addSql('COMMENT ON COLUMN category_revision_view.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN category_revision_view.category_revision_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN category_revision_view.created_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('DROP TABLE article_revision');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('DROP TABLE category');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('DROP TABLE admin_user');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('DROP TABLE article_revision_category');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('DROP TABLE media_file_revision');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('DROP TABLE category_revision');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('DROP TABLE article');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('DROP TABLE media_file');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('DROP TABLE article_revision_view');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('DROP TABLE category_revision_view');
    }
}
