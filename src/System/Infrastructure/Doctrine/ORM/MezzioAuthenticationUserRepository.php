<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Doctrine\ORM;

use Doctrine\ORM\EntityRepository;
use Laminas\Crypt\Password\PasswordInterface;
use Mezzio\Authentication\UserInterface;
use Mezzio\Authentication\UserRepositoryInterface;
use Support\Admin\Domain\Account\User;

final class MezzioAuthenticationUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private readonly EntityRepository $repository,
        private readonly PasswordInterface $password,
    ) {
    }

    public function authenticate(string $credential, ?string $password = null): ?UserInterface
    {
        $result = $this->repository->findOneBy([
            'username' => $credential,
        ]);
        assert($result instanceof User || $result === null);

        if ($result === null) {
            return null;
        }

        if (!$this->password->verify($password, $result->getPassword())) {
            return null;
        }

        return $result;
    }
}
