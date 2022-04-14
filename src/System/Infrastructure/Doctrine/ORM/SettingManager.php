<?php

declare(strict_types=1);

namespace Support\System\Infrastructure\Doctrine\ORM;

use Doctrine\ORM\EntityManagerInterface;
use Support\System\Domain\Setting;
use function PHPUnit\Framework\assertEquals;

final class SettingManager implements \Support\System\Domain\SettingManager
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function get(string $name, string $defaultValue = ''): string
    {
        $repository = $this->entityManager->getRepository(Setting::class);

        $setting = $repository->findOneBy([
            'name' => $name,
        ]);

        if ($setting === null) {
            return $defaultValue;
        }

        return $setting->getValue();
    }

    public function set(string $name, string $value): void
    {
        $repository = $this->entityManager->getRepository(Setting::class);

        $setting = $repository->findOneBy([
            'name' => $name,
        ]);
        assert($setting === null || $setting instanceof Setting);

        if ($setting === null) {
            $setting = new Setting($name, $value);

            $this->entityManager->persist($setting);
        } else {
            $setting->setValue($value);
        }

        $this->entityManager->flush();
    }
}
