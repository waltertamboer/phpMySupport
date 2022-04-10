<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220407193918 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Introduces application settings.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE application_setting (
            name VARCHAR(255) NOT NULL, 
            value TEXT NOT NULL, 
            PRIMARY KEY(name)
          )');

        $this->addSql('INSERT INTO application_setting (name, value) VALUES (?, ?)', [
            'defaultLocale',
            'en_US',
        ]);

        $this->addSql('INSERT INTO application_setting (name, value) VALUES (?, ?)', [
            'title',
            'phpMySupport',
        ]);

        $this->addSql('INSERT INTO application_setting (name, value) VALUES (?, ?)', [
            'theme',
            'default',
        ]);

        $this->addSql('INSERT INTO application_setting (name, value) VALUES (?, ?)', [
            'themeAdmin',
            'admin',
        ]);

        $this->addSql('INSERT INTO application_setting (name, value) VALUES (?, ?)', [
            'basePath',
            '/',
        ]);

        $this->addSql('INSERT INTO application_setting (name, value) VALUES (?, ?)', [
            'homepage',
            '/',
        ]);

        $this->addSql('INSERT INTO application_setting (name, value) VALUES (?, ?)', [
            'searchEnabled',
            '1',
        ]);

        $this->addSql('INSERT INTO application_setting (name, value) VALUES (?, ?)', [
            'tinyMceApiKey',
            '',
        ]);

        $this->addSql('INSERT INTO application_setting (name, value) VALUES (?, ?)', [
            'tinyMceConfig',
            '{
    "selector": "#bodyContent",
    "toolbar_mode": "floating",
    "image_advtab": true,
    "images_upload_url": "\/admin\/media\/create?tinymce=true",
    "automatic_uploads": true,
    "plugins": "a11ychecker advlist advcode advtable autolink casechange checklist colorpicker export formatpainter image imagetools link linkchecker lists media mediaembed pageembed powerpaste table tinymcespellchecker wordcount",
    "toolbar": [
        {
            "name": "history",
            "items": [
                "undo",
                "redo"
            ]
        },
        {
            "name": "styles",
            "items": [
                "styleselect",
                "backcolor",
                "forecolor"
            ]
        },
        {
            "name": "formatting",
            "items": [
                "bold",
                "italic",
                "underline",
                "strikethrough",
                "subscript",
                "superscript"
            ]
        },
        {
            "name": "alignment",
            "items": [
                "alignnone",
                "alignleft",
                "aligncenter",
                "alignright",
                "alignjustify"
            ]
        },
        {
            "name": "lists",
            "items": [
                "bullist",
                "numlist"
            ]
        },
        {
            "name": "indentation",
            "items": [
                "outdent",
                "indent"
            ]
        },
        {
            "name": "media",
            "items": [
                "link",
                "unlink",
                "blockquote",
                "image",
                "table",
                "pageembed"
            ]
        },
        {
            "name": "tools",
            "items": [
                "casechange",
                "checklist",
                "code"
            ]
        }
    ]
}',
        ]);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE application_setting');
    }
}
