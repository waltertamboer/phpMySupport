<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Doctrine\ORM;

use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Driver\XmlDriver;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Psr\Container\ContainerInterface;
use Ramsey\Uuid\Doctrine\UuidType;
use RuntimeException;
use Support\KnowledgeBase\Infrastructure\Doctrine\ORM\Type\CategoryIdType;
use Support\KnowledgeBase\Infrastructure\Doctrine\ORM\Type\CategoryNameType;
use Support\KnowledgeBase\Infrastructure\Doctrine\ORM\Type\CategorySlugType;
use Support\System\Infrastructure\Doctrine\ORM\Type\InetType;
use Support\System\Infrastructure\Doctrine\ORM\Type\UserAgentType;
use Webmozart\Assert\Assert;

final class EntityManagerFactory
{
    public function __invoke(ContainerInterface $container): EntityManagerInterface
    {
        $config = $container->get('config');

        return $this->createFromDoctrineConfig($container, $config['doctrine'] ?? []);
    }

    /**
     * @param array<string, mixed> $config
     */
    private function createFromDoctrineConfig(ContainerInterface $container, array $config): EntityManagerInterface
    {
        \Doctrine\DBAL\Types\Type::addType(UuidType::NAME, UuidType::class);
        \Doctrine\DBAL\Types\Type::addType(CategoryIdType::NAME, CategoryIdType::class);
        \Doctrine\DBAL\Types\Type::addType(CategoryNameType::NAME, CategoryNameType::class);
        \Doctrine\DBAL\Types\Type::addType(CategorySlugType::NAME, CategorySlugType::class);
        \Doctrine\DBAL\Types\Type::addType(InetType::NAME, InetType::class);
        \Doctrine\DBAL\Types\Type::addType(UserAgentType::NAME, UserAgentType::class);

        $configuration = $this->createDoctrineConfiguration($container, $config);

        return EntityManager::create($config['connection'] ?? [], $configuration);
    }

    /**
     * @param array<string, mixed> $config
     */
    private function createDoctrineConfiguration(ContainerInterface $container, array $config): Configuration
    {
        $configuration = new Configuration();

        if (array_key_exists('autoCommit', $config) && $config['autoCommit'] !== null) {
            $configuration->setAutoCommit($config['autoCommit'] === true);
        }

        if (array_key_exists('autoGenerateProxyClasses', $config) && $config['autoGenerateProxyClasses'] !== null) {
            $configuration->setAutoGenerateProxyClasses($config['autoGenerateProxyClasses'] === true);
        }

        if (array_key_exists('proxyDirectory', $config) && $config['proxyDirectory'] !== null) {
            Assert::stringNotEmpty($config['proxyDirectory']);
            $configuration->setProxyDir($config['proxyDirectory']);
        }

        if (array_key_exists('proxyNamespace', $config) && $config['proxyNamespace'] !== null) {
            Assert::stringNotEmpty($config['proxyNamespace']);
            $configuration->setProxyNamespace($config['proxyNamespace']);
        }

        if (array_key_exists('sqlLogger', $config) && $config['sqlLogger'] !== null) {
            $configuration->setSQLLogger($container->get($config['sqlLogger']));
        }

        $this->initializeMetadataDriver($configuration, $config['metadata'] ?? []);

        $configuration->setNamingStrategy(new UnderscoreNamingStrategy());

        return $configuration;
    }

    /**
     * @param array<string, mixed> $config
     */
    private function initializeMetadataDriver(Configuration $configuration, array $config): void
    {
        $type = $config['type'] ?? 'xml';

        switch ($type) {
            case 'annotation':
                $result = $configuration->newDefaultAnnotationDriver(
                    $config['paths'] ?? [],
                    $config['useSimpleAnnotationReader'] === true,
                );
                break;

            case 'xml':
                $result = new XmlDriver($config['paths'] ?? []);
                break;

            default:
                throw new RuntimeException('Invalid metadata driver: ' . $type);
        }

        $configuration->setMetadataDriverImpl($result);
    }
}
