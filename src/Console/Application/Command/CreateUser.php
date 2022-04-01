<?php

declare(strict_types=1);

namespace Support\Console\Application\Command;

use Doctrine\ORM\EntityManagerInterface;
use Laminas\Crypt\Password\PasswordInterface;
use RuntimeException;
use Support\Admin\Domain\Account\User;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

final class CreateUser extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly PasswordInterface $password,
    )
    {
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
        $noInteraction = $input->getOption('no-interaction');

        $username = (string)$input->getOption('username');

        if ($username === '' && $noInteraction) {
            $formatter = $this->getHelper('formatter');
            $errorMessages = ['Error!', 'No username provided.'];
            $formattedBlock = $formatter->formatBlock($errorMessages, 'error');
            $output->writeln($formattedBlock);
            return 1;
        }

        $username = $this->askUsername($input, $output, $username);
        $password = $this->askPassword($input, $output);
        $role = $this->askRole($input, $output);

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

    private function askUsername(InputInterface $input, OutputInterface $output, string $username): string
    {
        $questionHelper = $this->getHelper('question');

        $hasValidUsername = false;
        while (!$hasValidUsername) {
            if ($username === '') {
                $question = new Question('Enter a username: ');
                $question->setValidator(static function (?string $answer): ?string {
                    if ($answer === null) {
                        throw new RuntimeException('The username is required.');
                    }

                    return $answer;
                });

                $username = $questionHelper->ask($input, $output, $question);
            }

            $hasValidUsername = $this->hasExistingUser($username) === false;

            if (!$hasValidUsername) {
                $formatter = $this->getHelper('formatter');
                $errorMessages = ['Error!', 'The username already exists.'];
                $formattedBlock = $formatter->formatBlock($errorMessages, 'error');
                $output->writeln($formattedBlock);
            }
        }

        return $username;
    }

    private function askPassword(InputInterface $input, OutputInterface $output): string
    {
        $password = (string)$input->getOption('password');

        if ($password !== '') {
            $formatter = $this->getHelper('formatter');
            $errorMessages = ['Warning!', 'Avoid using the --password parameter since it compromises '
                . 'your password by adding it to your shell history.'];
            $formattedBlock = $formatter->formatBlock($errorMessages, 'comment');
            $output->writeln($formattedBlock);

            return $password;
        }

        $questionHelper = $this->getHelper('question');

        $question = new Question('Enter a password: ');
        $question->setHidden(true);
        $question->setValidator(static function (?string $answer): ?string {
            if ($answer === null) {
                throw new RuntimeException('The password is required.');
            }

            return $answer;
        });

        return $questionHelper->ask($input, $output, $question);
    }

    private function askRole(InputInterface $input, OutputInterface $output): string
    {
        $validRoles = ['editor', 'admin'];
        $role = (string)$input->getOption('role');

        $questionHelper = $this->getHelper('question');
        $formatter = $this->getHelper('formatter');

        while (!in_array($role, $validRoles, true)) {
            $errorMessages = ['Error!', 'Invalid role, should be editor or admin.'];
            $formattedBlock = $formatter->formatBlock($errorMessages, 'error');
            $output->writeln($formattedBlock);

            $question = new Question('Enter a role (editor): ');
            $question->setValidator(static function (?string $answer): ?string {
                if ($answer === null) {
                    return 'editor';
                }

                return $answer;
            });

            $role = $questionHelper->ask($input, $output, $question);
        }

        return $role;
    }
}
