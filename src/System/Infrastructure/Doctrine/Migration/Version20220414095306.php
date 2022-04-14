<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220414095306 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Introduces the Google Translate setting.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('INSERT INTO application_setting (name, value) VALUES (?, ?)', [
            'googleTranslateEnabled',
            '0',
        ]);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE application_setting');
    }
}
