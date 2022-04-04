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
use Support\KnowledgeBase\Domain\Article\Article;
use Support\KnowledgeBase\Domain\Category\Category;
use Support\KnowledgeBase\Domain\Category\CategoryName;
use Support\KnowledgeBase\Domain\Category\CategorySlug;
use Support\System\Application\Exception\ResourceNotFound;

final class Create implements RequestHandlerInterface
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

        $formData = [
            'username' => '',
            'role' => 'editor',
        ];

        $error = false;
        $errorMsg = null;

        if ($request->getMethod() === 'POST') {
            $formData = $request->getParsedBody();

            $username = $formData['username'] ?? '';
            $password = $formData['password'] ?? '';
            $otherPassword = $formData['newPassword'] ?? '';
            $role = $formData['role'] ?? 'editor';

            if ($username === '') {
                $error = true;
                $errorMsg = 'No valid username provided.';
            } elseif ($this->hasExistingUser($username)) {
                $error = true;
                $errorMsg = 'Username already exists.';
            } elseif ($password === '') {
                $error = true;
                $errorMsg = 'No valid password provided.';
            } elseif ($password !== $otherPassword) {
                $error = true;
                $errorMsg = 'Passwords do not match.';
            } elseif (!in_array($role, ['editor', 'admin'], true)) {
                $error = true;
                $errorMsg = 'Invalid role configured.';
            } else {
                $currentUser = new User(
                    $formData['username'],
                    $this->password->create($formData['password']),
                    $formData['role'],
                );

                $this->entityManager->persist($currentUser);
                $this->entityManager->flush();

                return new RedirectResponse('/admin/users');
            }
        }

        return new HtmlResponse($this->renderer->render(
            '@admin/user/create.html.twig',
            [
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
