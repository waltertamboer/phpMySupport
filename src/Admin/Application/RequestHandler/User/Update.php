<?php

declare(strict_types=1);

namespace Support\Admin\Application\RequestHandler\User;

use Doctrine\ORM\EntityManagerInterface;
use Laminas\Crypt\Password\PasswordInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Authentication\UserInterface;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Support\Admin\Domain\Account\User;
use Support\System\Application\Exception\ResourceNotFound;

final class Update implements RequestHandlerInterface
{
    public function __construct(
        private readonly TemplateRendererInterface $renderer,
        private readonly EntityManagerInterface $entityManager,
        private readonly PasswordInterface $password,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $currentUser = $request->getAttribute(UserInterface::class);

        if ($currentUser === null || !$currentUser->isOwner()) {
            throw ResourceNotFound::fromRequest($request);
        }

        $entityId = $request->getAttribute('id');
        $entity = $this->entityManager->find(User::class, $entityId);
        assert($entity === null || $entity instanceof User);

        if ($entity === null) {
            throw ResourceNotFound::fromRequest($request);
        }

        $formData = [
            'username' => $entity->getUsername(),
            'role' => $entity->getRole(),
        ];

        $error = false;
        $errorMsg = null;

        if ($request->getMethod() === 'POST') {
            $formData = array_merge($formData, $request->getParsedBody());

            if (array_key_exists('changePasswordButton', $formData)) {
                $password = $formData['password'] ?? '';
                $otherPassword = $formData['newPassword'] ?? '';

                if ($password === '') {
                    $error = true;
                    $errorMsg = 'No valid password provided.';
                } elseif ($password !== $otherPassword) {
                    $error = true;
                    $errorMsg = 'Passwords do not match.';
                } else {
                    $entity->setPassword($this->password->create($password));
                }
            } else {
                $username = $formData['username'] ?? '';
                $role = $formData['role'] ?? '';

                if ($username === '') {
                    $error = true;
                    $errorMsg = 'No valid username provided.';
                } elseif ($username !== $entity->getUsername() && $this->hasExistingUser($username)) {
                    $error = true;
                    $errorMsg = 'Username already exists.';
                } elseif (!in_array($role, ['editor', 'admin'], true)) {
                    $error = true;
                    $errorMsg = 'Invalid role configured.';
                } else {
                    $entity->setUsername($username);

                    if ($entity !== $currentUser) {
                        $entity->setRole($formData['role']);
                    }

                    $this->entityManager->flush();

                    return new RedirectResponse('/admin/users');
                }
            }
        }

        return new HtmlResponse($this->renderer->render(
            '@admin/user/update.html.twig',
            [
                'entity' => $entity,
                'formData' => $formData,
                'error' => $error,
                'errorMsg' => $errorMsg,
            ],
        ));
    }

    private function hasExistingUser(string $username): bool
    {
        $repository = $this->entityManager->getRepository(User::class);

        return $repository->findOneBy([
                'username' => $username,
            ]) !== null;
    }
}
