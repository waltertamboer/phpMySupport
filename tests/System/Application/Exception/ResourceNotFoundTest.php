<?php

declare(strict_types=1);

namespace Support\System\Application\Exception;

use Laminas\Diactoros\ServerRequestFactory;
use PHPUnit\Framework\TestCase;

final class ResourceNotFoundTest extends TestCase
{
    /**
     * @covers \Support\System\Application\Exception\ResourceNotFound::fromRequest
     */
    public function testFromRequestMessage(): void
    {
        // Arrange
        $request = ServerRequestFactory::fromGlobals();

        // Act
        $exception = ResourceNotFound::fromRequest($request);

        // Assert
        static::assertEquals('Resource not found', $exception->getMessage());
    }

    /**
     * @covers \Support\System\Application\Exception\ResourceNotFound::fromRequest
     */
    public function testFromRequestCodeEquals404(): void
    {
        // Arrange
        $request = ServerRequestFactory::fromGlobals();

        // Act
        $exception = ResourceNotFound::fromRequest($request);

        // Assert
        static::assertEquals(404, $exception->getCode());
    }
}
