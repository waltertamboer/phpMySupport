<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Infrastructure\Laminas;

use Support\KnowledgeBase\Application\RequestHandler;
use Support\KnowledgeBase\Domain\Article;
use Support\KnowledgeBase\Domain\Category;
use Support\KnowledgeBase\Infrastructure\Factory;

final class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'command_bus' => $this->getCommandBus(),
            'dependencies' => $this->getDependencies(),
            'doctrine' => $this->getDoctrine(),
            'query_bus' => $this->getQueryBus(),
            'routes' => $this->getRoutes(),
        ];
    }

    private function getCommandBus(): array
    {
        return [
            Article\Bus\Command\RegisterArticleView::class => Article\Bus\Command\RegisterArticleViewHandler::class,
            Category\Bus\Command\RegisterCategoryView::class => Category\Bus\Command\RegisterCategoryViewHandler::class,
        ];
    }

    private function getDependencies(): array
    {
        return [
            'factories' => [
                Article\Bus\Command\RegisterArticleViewHandler::class => Factory\RegisterArticleViewHandlerFactory::class,
                Article\Bus\Query\FindArticleBySlugHandler::class => Factory\FindArticleBySlugHandlerFactory::class,
                Article\ArticleRepository::class => Factory\ArticleRepositoryFactory::class,

                Category\Bus\Command\RegisterCategoryViewHandler::class => Factory\RegisterCategoryViewHandlerFactory::class,
                Category\CategoryRepository::class => Factory\CategoryRepositoryFactory::class,

                Category\Bus\Query\FindArticlesForCategoryHandler::class => Factory\FindArticlesForCategoryHandlerFactory::class,
                Category\Bus\Query\FindCategoryBySlugHandler::class => Factory\FindCategoryBySlugHandlerFactory::class,
                Category\Bus\Query\GetCategoryOverviewHandler::class => Factory\GetCategoryOverviewHandlerFactory::class,

                RequestHandler\Homepage::class => Factory\HomepageRequestHandlerFactory::class,
                RequestHandler\Article::class => Factory\ArticleRequestHandlerFactory::class,
                RequestHandler\Category::class => Factory\CategoryRequestHandlerFactory::class,
                RequestHandler\MediaFile::class => Factory\MediaFileRequestHandlerFactory::class,
                RequestHandler\Search::class => Factory\SearchRequestHandlerFactory::class,
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

    private function getQueryBus(): array
    {
        return [
            Article\Bus\Query\FindArticleBySlug::class => Article\Bus\Query\FindArticleBySlugHandler::class,
            Category\Bus\Query\FindArticlesForCategory::class => Category\Bus\Query\FindArticlesForCategoryHandler::class,
            Category\Bus\Query\FindCategoryBySlug::class => Category\Bus\Query\FindCategoryBySlugHandler::class,
            Category\Bus\Query\GetCategoryOverview::class => Category\Bus\Query\GetCategoryOverviewHandler::class,
        ];
    }

    private function getRoutes(): array
    {
        return [
            'home' => [
                'name' => 'home',
                'path' => '/',
                'middleware' => [
                    \Support\System\Application\Middleware\SettingsMiddleware::class,
                    RequestHandler\Homepage::class,
                ],
                'allowed_methods' => [ 'GET' ],
            ],
            'home-locale' => [
                'name' => 'home-locale',
                'path' => '/{locale}',
                'middleware' => [
                    \Support\System\Application\Middleware\SettingsMiddleware::class,
                    RequestHandler\Homepage::class,
                ],
                'allowed_methods' => [ 'GET' ],
            ],
            'article' => [
                'name' => 'article',
                'path' => '/{locale}/article/{slug}',
                'middleware' => [
                    \Support\System\Application\Middleware\SettingsMiddleware::class,
                    RequestHandler\Article::class,
                ],
                'allowed_methods' => [ 'GET' ],
            ],
            'category' => [
                'name' => 'category',
                'path' => '/{locale}/category/{slug}',
                'middleware' => [
                    \Support\System\Application\Middleware\SettingsMiddleware::class,
                    RequestHandler\Category::class,
                ],
                'allowed_methods' => [ 'GET' ],
            ],
            'media-file' => [
                'name' => 'media-file',
                'path' => '/files/{id}',
                'middleware' => [
                    \Support\System\Application\Middleware\SettingsMiddleware::class,
                    RequestHandler\MediaFile::class,
                ],
                'allowed_methods' => [ 'GET' ],
            ],
            'search' => [
                'name' => 'search',
                'path' => '/{locale}/search',
                'middleware' => [
                    \Support\System\Application\Middleware\SettingsMiddleware::class,
                    RequestHandler\Search::class,
                ],
                'allowed_methods' => [ 'GET' ],
            ],
        ];
    }
}
