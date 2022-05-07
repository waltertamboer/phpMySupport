<?php

declare(strict_types=1);

namespace Support\KnowledgeBase\Application\RequestHandler;

use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Support\KnowledgeBase\Domain\Ticket\Ticket;
use Support\KnowledgeBase\Domain\Ticket\TicketCategory;

final class CreateTicket implements RequestHandlerInterface
{
    public function __construct(
        private readonly TemplateRendererInterface $renderer,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $error = null;
        $formData = [
            'subject' => '',
            'category' => '',
            'message' => '',
        ];

        $success = ($request->getQueryParams()['success'] ?? '') === '1';

        if ($request->getMethod() === 'POST') {
            $formData = $request->getParsedBody();

            $formSubject = $formData['subject'] ?? '';
            $formCategory = $formData['category'] ?? '';
            $formMessage = $formData['message'] ?? '';

            if ($formSubject === '') {
                $error = 'The subject is required.';
            } elseif ($formMessage === '') {
                $error = 'The message is required.';
            } else {
                return new RedirectResponse(
                    '/tickets/create?success=1'
                );
            }
        }

        $categories = [];//$this->loadTicketCategories();

        return new HtmlResponse($this->renderer->render(
            '@site/knowledge-base/create-ticket.html.twig',
            [
                'success' => $success,
                'error' => $error,
                'formData' => $formData,
                'categories' => $categories,
            ]
        ));
    }

    private function loadTicketCategories(): array
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('t');
        $qb->from(TicketCategory::class, 't');
        $qb->orderBy($qb->expr()->asc('t.position'));

        return $qb->getQuery()->getResult();
    }
}
