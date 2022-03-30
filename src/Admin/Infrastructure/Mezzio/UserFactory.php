<?php

declare(strict_types=1);

namespace Support\Admin\Infrastructure\Mezzio;

use Doctrine\ORM\EntityManagerInterface;
use Mezzio\Authentication\UserInterface;
use Psr\Container\ContainerInterface;
use Support\Admin\Domain\Account\User;
use Support\System\Application\Exception\ResourceNotFound;
use Webmozart\Assert\Assert;

final class UserFactory
{
    public function __invoke(ContainerInterface $container): callable
    {
        return function (string $identity, array $roles = [], array $details = []) use ($container): UserInterface {
            Assert::allString($roles);
            Assert::isMap($details);

            Assert::keyExists($details, 'id');
            Assert::uuid($details['id']);

            $entityManager = $container->get(EntityManagerInterface::class);

            $result = $entityManager->find(User::class, $details['id']);

            if ($result === null) {
                throw new \RuntimeException('You are not authorized to view this page.');
            }

            return $result;
        };
    }
}
