<?php

declare(strict_types=1);

namespace Support\System\Application\Middleware;

use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Support\System\Application\Exception\ResourceNotFound;

final class PageNotFound implements MiddlewareInterface
{
    public function __construct(
        private readonly TemplateRendererInterface $renderer,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $response = $handler->handle($request);
        } catch (ResourceNotFound $exception) {
            $response = new HtmlResponse($this->renderer->render('@error/404.html.twig', []));

            $response = $response->withStatus(StatusCodeInterface::STATUS_NOT_FOUND);
        }

        return $response;
    }
}
