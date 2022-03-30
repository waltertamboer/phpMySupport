<?php

declare(strict_types=1);

namespace Support\Console\Application\Command;

use Doctrine\ORM\EntityManagerInterface;
use Laminas\Crypt\Password\PasswordInterface;
use Support\Admin\Domain\Account\User;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class CreateUser extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly PasswordInterface $password,
    ) {
        parent::__construct(null);
    }

    protected function configure() // phpcs:ignore
    {
        $this->setName('user:create');
        $this->setDescription('Creates a new user.');

        $this->addOption('username', null, InputOption::VALUE_REQUIRED, 'The username to set for the new user.');
        $this->addOption('password', null, InputOption::VALUE_REQUIRED, 'The password to set for the new user. Avoid using this parameter.');
        $this->addOption('role', null, InputOption::VALUE_REQUIRED, 'The role to set for the new user, either editor or admin.', 'editor');
    }

    protected function execute(InputInterface $input, OutputInterface $output) // phpcs:ignore
    {
        $username = $input->getOption('username');
        if ($username === '' || $username === null) {
            $output->writeln('<error>No username provided.</error>');
            return 1;
        }

        if ($this->hasExistingUser($username)) {
            $output->writeln('<error>Username already exists.</error>');
            return 1;
        }

        $password = $input->getOption('password');
        $role = $input->getOption('role');

        if ($password !== null) {
            $error = '<info>Avoid using the --password parameter since it comprimises '
                . 'your password by adding it to your shell history.</info>';
            $output->writeln($error);
        }

        if ($password === '' || $password === null) {
            $output->writeln('<error>No password provided.</error>');
            return 1;
        }

        if ($role !== 'editor' && $role !== 'admin') {
            $output->writeln('<error>Invalid role, should be editor or admin.</error>');
            return 1;
        }

        $user = new User(
            $username,
            $this->password->create($password),
            $role,
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return 0;
    }

    private function hasExistingUser(string $username): bool
    {
        $repository = $this->entityManager->getRepository(User::class);

        return $repository->findOneBy([
            'username' => $username,
        ]) !== null;
    }
}
