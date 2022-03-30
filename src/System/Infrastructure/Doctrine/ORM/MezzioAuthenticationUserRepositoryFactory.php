<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Doctrine\ORM;

use Doctrine\ORM\EntityManagerInterface;
use Laminas\Crypt\Password\Bcrypt;
use Psr\Container\ContainerInterface;
use Support\Admin\Domain\Account\User;

final class MezzioAuthenticationUserRepositoryFactory
{
    public function __invoke(ContainerInterface $container): MezzioAuthenticationUserRepository
    {
        $entityManager = $container->get(EntityManagerInterface::class);

        return new MezzioAuthenticationUserRepository(
            $entityManager->getRepository(User::class),
            new Bcrypt(),
        );
    }
}
