<?php

declare(strict_types=1);

namespace Support\Admin\Infrastructure\Laminas;

use Mezzio\Authentication\DefaultUserFactory;
use Mezzio\Authentication\UserInterface;
use Support\Admin\Application\RequestHandler;
use Support\Admin\Infrastructure\Factory;
use Support\Admin\Infrastructure\Mezzio\UserFactory;

final class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'doctrine' => $this->getDoctrine(),
            'mezzio-authorization-rbac' => [
                'roles' => [
                    'admin' => [],
                    'editor' => [ 'admin' ],
                    'contributor' => [ 'editor' ],
                ],
                'permissions' => [
                    'contributor' => [
                        'admin.dashboard',
                        'admin.posts',
                    ],
                    'editor' => [
                        'admin.publish',
                    ],
                    'admin' => [
                        'admin.settings',
                    ],
                ],
            ],
            'routes' => $this->getRoutes(),
        ];
    }

    private function getDependencies(): array
    {
        return [
            'factories' => [
                RequestHandler\Article\Create::class => Factory\Article\CreateRequestHandlerFactory::class,
                RequestHandler\Article\Delete::class => Factory\Article\DeleteRequestHandlerFactory::class,
                RequestHandler\Article\Update::class => Factory\Article\UpdateRequestHandlerFactory::class,
                RequestHandler\Article\Overview::class => Factory\Article\OverviewRequestHandlerFactory::class,

                RequestHandler\Authentication\Login::class => Factory\Authenticate\LoginRequestHandlerFactory::class,
                RequestHandler\Authentication\Logout::class => Factory\Authenticate\LogoutRequestHandlerFactory::class,

                RequestHandler\Category\Create::class => Factory\Category\CreateRequestHandlerFactory::class,
                RequestHandler\Category\Delete::class => Factory\Category\DeleteRequestHandlerFactory::class,
                RequestHandler\Category\Update::class => Factory\Category\UpdateRequestHandlerFactory::class,
                RequestHandler\Category\Overview::class => Factory\Category\OverviewRequestHandlerFactory::class,

                RequestHandler\Media\Create::class => Factory\Media\CreateRequestHandlerFactory::class,
                RequestHandler\Media\Delete::class => Factory\Media\DeleteRequestHandlerFactory::class,
                RequestHandler\Media\Update::class => Factory\Media\UpdateRequestHandlerFactory::class,
                RequestHandler\Media\Overview::class => Factory\Media\OverviewRequestHandlerFactory::class,
                RequestHandler\Media\TinyMceDialog::class => Factory\Media\TinyMceDialogHandlerFactory::class,

                RequestHandler\User\Create::class => Factory\User\CreateRequestHandlerFactory::class,
                RequestHandler\User\Delete::class => Factory\User\DeleteRequestHandlerFactory::class,
                RequestHandler\User\Update::class => Factory\User\UpdateRequestHandlerFactory::class,
                RequestHandler\User\Overview::class => Factory\User\OverviewRequestHandlerFactory::class,

                RequestHandler\Settings\Overview::class => Factory\Settings\OverviewRequestHandlerFactory::class,
                RequestHandler\Settings\ExportImport::class => Factory\Settings\ExportImportRequestHandlerFactory::class,

                RequestHandler\Dashboard\View::class => Factory\Dashboard\ViewRequestHandlerFactory::class,

                UserInterface::class => UserFactory::class,
            ],
        ];
    }

    private function getDoctrine(): array
    {
        return [
            'metadata' => [
                'type' => 'xml',
                'paths' => [
                    __DIR__ . '/../Doctrine/ORM/',
                ],
            ],
        ];
    }

    private function getRoutes(): array
    {
        return [
            'admin-login' => [
                'name' => 'admin-login',
                'path' => '/admin/login',
                'middleware' => [
                    RequestHandler\Authentication\Login::class,
                ],
                'allowed_methods' => [ 'GET', 'POST' ],
            ],
            'admin-logout' => [
                'name' => 'admin-logout',
                'path' => '/admin/logout',
                'middleware' => [
                    \Mezzio\Authentication\AuthenticationMiddleware::class,
                    \Support\System\Infrastructure\Twig\DefaultTemplateParamsMiddleware::class,
                    RequestHandler\Authentication\Logout::class,
                ],
                'allowed_methods' => [ 'GET' ],
            ],


            'admin-dashboard' => [
                'name' => 'admin-dashboard',
                'path' => '/admin/dashboard',
                'middleware' => [
                    \Mezzio\Authentication\AuthenticationMiddleware::class,
                    \Support\System\Infrastructure\Twig\DefaultTemplateParamsMiddleware::class,
                    RequestHandler\Dashboard\View::class,
                ],
                'allowed_methods' => [ 'GET' ],
            ],


            'admin-article-overview' => [
                'name' => 'admin-article-overview',
                'path' => '/admin/articles',
                'middleware' => [
                    \Mezzio\Authentication\AuthenticationMiddleware::class,
                    \Support\System\Infrastructure\Twig\DefaultTemplateParamsMiddleware::class,
                    RequestHandler\Article\Overview::class,
                ],
                'allowed_methods' => [ 'GET' ],
            ],
            'admin-article-create' => [
                'name' => 'admin-article-create',
                'path' => '/admin/articles/create',
                'middleware' => [
                    \Mezzio\Authentication\AuthenticationMiddleware::class,
                    \Support\System\Infrastructure\Twig\DefaultTemplateParamsMiddleware::class,
                    RequestHandler\Article\Create::class,
                ],
                'allowed_methods' => [ 'GET', 'POST' ],
            ],
            'admin-article-delete' => [
                'name' => 'admin-article-delete',
                'path' => '/admin/articles/delete/{id}',
                'middleware' => [
                    \Mezzio\Authentication\AuthenticationMiddleware::class,
                    \Support\System\Infrastructure\Twig\DefaultTemplateParamsMiddleware::class,
                    RequestHandler\Article\Delete::class,
                ],
                'allowed_methods' => [ 'GET', 'POST' ],
            ],
            'admin-article-update' => [
                'name' => 'admin-article-update',
                'path' => '/admin/articles/update/{id}',
                'middleware' => [
                    \Mezzio\Authentication\AuthenticationMiddleware::class,
                    \Support\System\Infrastructure\Twig\DefaultTemplateParamsMiddleware::class,
                    RequestHandler\Article\Update::class,
                ],
                'allowed_methods' => [ 'GET', 'POST' ],
            ],


            'admin-category-overview' => [
                'name' => 'admin-category-overview',
                'path' => '/admin/categories',
                'middleware' => [
                    \Mezzio\Authentication\AuthenticationMiddleware::class,
                    \Support\System\Infrastructure\Twig\DefaultTemplateParamsMiddleware::class,
                    RequestHandler\Category\Overview::class,
                ],
                'allowed_methods' => [ 'GET' ],
            ],
            'admin-category-create' => [
                'name' => 'admin-category-create',
                'path' => '/admin/categories/create',
                'middleware' => [
                    \Mezzio\Authentication\AuthenticationMiddleware::class,
                    \Support\System\Infrastructure\Twig\DefaultTemplateParamsMiddleware::class,
                    RequestHandler\Category\Create::class,
                ],
                'allowed_methods' => [ 'GET', 'POST' ],
            ],
            'admin-category-delete' => [
                'name' => 'admin-category-delete',
                'path' => '/admin/categories/delete/{id}',
                'middleware' => [
                    \Mezzio\Authentication\AuthenticationMiddleware::class,
                    \Support\System\Infrastructure\Twig\DefaultTemplateParamsMiddleware::class,
                    RequestHandler\Category\Delete::class,
                ],
                'allowed_methods' => [ 'GET', 'POST' ],
            ],
            'admin-category-update' => [
                'name' => 'admin-category-update',
                'path' => '/admin/categories/update/{id}',
                'middleware' => [
                    \Mezzio\Authentication\AuthenticationMiddleware::class,
                    \Support\System\Infrastructure\Twig\DefaultTemplateParamsMiddleware::class,
                    RequestHandler\Category\Update::class,
                ],
                'allowed_methods' => [ 'GET', 'POST' ],
            ],


            'admin-media-overview' => [
                'name' => 'admin-media-overview',
                'path' => '/admin/media',
                'middleware' => [
                    \Mezzio\Authentication\AuthenticationMiddleware::class,
                    \Support\System\Infrastructure\Twig\DefaultTemplateParamsMiddleware::class,
                    RequestHandler\Media\Overview::class,
                ],
                'allowed_methods' => [ 'GET' ],
            ],
            'admin-media-dialog' => [
                'name' => 'admin-media-dialog',
                'path' => '/admin/media/dialog',
                'middleware' => [
                    \Mezzio\Authentication\AuthenticationMiddleware::class,
                    \Support\System\Infrastructure\Twig\DefaultTemplateParamsMiddleware::class,
                    RequestHandler\Media\TinyMceDialog::class,
                ],
                'allowed_methods' => [ 'GET' ],
            ],
            'admin-media-create' => [
                'name' => 'admin-media-create',
                'path' => '/admin/media/create',
                'middleware' => [
                    \Mezzio\Authentication\AuthenticationMiddleware::class,
                    \Support\System\Infrastructure\Twig\DefaultTemplateParamsMiddleware::class,
                    RequestHandler\Media\Create::class,
                ],
                'allowed_methods' => [ 'GET', 'POST' ],
            ],
            'admin-media-delete' => [
                'name' => 'admin-media-delete',
                'path' => '/admin/media/delete/{id}',
                'middleware' => [
                    \Mezzio\Authentication\AuthenticationMiddleware::class,
                    \Support\System\Infrastructure\Twig\DefaultTemplateParamsMiddleware::class,
                    RequestHandler\Media\Delete::class,
                ],
                'allowed_methods' => [ 'GET', 'POST' ],
            ],
            'admin-media-update' => [
                'name' => 'admin-media-update',
                'path' => '/admin/media/update/{id}',
                'middleware' => [
                    \Mezzio\Authentication\AuthenticationMiddleware::class,
                    \Support\System\Infrastructure\Twig\DefaultTemplateParamsMiddleware::class,
                    RequestHandler\Media\Update::class,
                ],
                'allowed_methods' => [ 'GET', 'POST' ],
            ],

            'admin-user-overview' => [
                'name' => 'admin-user-overview',
                'path' => '/admin/users',
                'middleware' => [
                    \Mezzio\Authentication\AuthenticationMiddleware::class,
                    \Support\System\Infrastructure\Twig\DefaultTemplateParamsMiddleware::class,
                    RequestHandler\User\Overview::class,
                ],
                'allowed_methods' => [ 'GET' ],
            ],
            'admin-user-create' => [
                'name' => 'admin-user-create',
                'path' => '/admin/users/create',
                'middleware' => [
                    \Mezzio\Authentication\AuthenticationMiddleware::class,
                    \Support\System\Infrastructure\Twig\DefaultTemplateParamsMiddleware::class,
                    RequestHandler\User\Create::class,
                ],
                'allowed_methods' => [ 'GET', 'POST' ],
            ],
            'admin-user-delete' => [
                'name' => 'admin-user-delete',
                'path' => '/admin/users/delete/{id}',
                'middleware' => [
                    \Mezzio\Authentication\AuthenticationMiddleware::class,
                    \Support\System\Infrastructure\Twig\DefaultTemplateParamsMiddleware::class,
                    RequestHandler\User\Delete::class,
                ],
                'allowed_methods' => [ 'GET', 'POST' ],
            ],
            'admin-user-update' => [
                'name' => 'admin-user-update',
                'path' => '/admin/users/update/{id}',
                'middleware' => [
                    \Mezzio\Authentication\AuthenticationMiddleware::class,
                    \Support\System\Infrastructure\Twig\DefaultTemplateParamsMiddleware::class,
                    RequestHandler\User\Update::class,
                ],
                'allowed_methods' => [ 'GET', 'POST' ],
            ],

            'admin-settings-overview' => [
                'name' => 'admin-settings-overview',
                'path' => '/admin/settings',
                'middleware' => [
                    \Mezzio\Authentication\AuthenticationMiddleware::class,
                    \Support\System\Infrastructure\Twig\DefaultTemplateParamsMiddleware::class,
                    RequestHandler\Settings\Overview::class,
                ],
                'allowed_methods' => [ 'GET', 'POST' ],
            ],
            'admin-settings-export-import' => [
                'name' => 'admin-settings-export-import',
                'path' => '/admin/settings/export-import',
                'middleware' => [
                    \Mezzio\Authentication\AuthenticationMiddleware::class,
                    \Support\System\Infrastructure\Twig\DefaultTemplateParamsMiddleware::class,
                    RequestHandler\Settings\ExportImport::class,
                ],
                'allowed_methods' => [ 'GET', 'POST' ],
            ],
        ];
    }
}
