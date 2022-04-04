<?php

declare(strict_types=1);

namespace Support\Admin\Domain\Account;

use DateTimeImmutable;
use Mezzio\Authentication\UserInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class User implements UserInterface
{
    private UuidInterface $id;
    private DateTimeImmutable $createdAt;

    public function __construct(
        private string $username,
        private string $password,
        private string $role,
    ) {
        $this->id = Uuid::uuid4();
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getIdentity(): string
    {
        return $this->getUsername();
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    public function getRoles(): iterable
    {
        return [
            $this->getRole(),
        ];
    }

    public function isAdmin(): bool
    {
        if ($this->isOwner()) {
            return true;
        }

        return $this->getRole() == UserRole::Admin->value;
    }

    public function isEditor(): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        return $this->getRole() == UserRole::Editor->value;
    }

    public function isOwner(): bool
    {
        return $this->getRole() == UserRole::Owner->value;
    }

    public function getDetail(string $name, $default = null)
    {
        if ($name === 'id') {
            return $this->getId();
        }

        return $default;
    }

    public function getDetails(): array
    {
        return [
            'id' => $this->id->toString(),
        ];
    }
}
