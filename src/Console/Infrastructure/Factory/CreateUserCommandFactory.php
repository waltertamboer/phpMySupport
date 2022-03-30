<?php

declare(strict_types=1);

namespace Support\Console\Infrastructure\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Laminas\Crypt\Password\Bcrypt;
use Psr\Container\ContainerInterface;
use Support\Console\Application\Command\CreateUser;

final class CreateUserCommandFactory
{
    public function __invoke(ContainerInterface $container): CreateUser
    {
        return new CreateUser(
            $container->get(EntityManagerInterface::class),
            new Bcrypt(),
        );
    }
}
