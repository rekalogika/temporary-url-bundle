<?php

declare(strict_types=1);

/*
 * This file is part of rekalogika/temporary-url-bundle package.
 *
 * (c) Priyadi Iman Nurcahyo <https://rekalogika.dev>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Rekalogika\TemporaryUrl\Tests;

use Symfony\Component\HttpFoundation\Response;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Rekalogika\TemporaryUrl\Data;
use Rekalogika\TemporaryUrl\Exception\ServerNotFoundException;
use Rekalogika\TemporaryUrl\Exception\TicketNotFoundException;
use Rekalogika\TemporaryUrl\Exception\WrongSessionException;
use Rekalogika\TemporaryUrl\Internal\TemporaryUrlController;
use Rekalogika\TemporaryUrl\TemporaryUrlGeneratorInterface;
use Rekalogika\TemporaryUrl\Tests\Model\Data1;
use Rekalogika\TemporaryUrl\Tests\Model\Data2;
use Rekalogika\TemporaryUrl\Tests\Model\Data3;
use Rekalogika\TemporaryUrl\Tests\Model\DataServer;
use Rekalogika\TemporaryUrl\Tests\Model\UnserializableData;

class TemporaryUrlTest extends TestCase
{
    private ?ContainerInterface $container = null;

    #[\Override]
    protected function setUp(): void
    {
        $kernel = new Kernel();
        $kernel->boot();

        $this->container = $kernel->getContainer();
    }

    private function getTemporaryUrlGenerator(): TemporaryUrlGeneratorInterface
    {
        $temporaryUrlGenerator = $this->container
            ?->get('test.' . TemporaryUrlGeneratorInterface::class);

        self::assertInstanceOf(
            TemporaryUrlGeneratorInterface::class,
            $temporaryUrlGenerator,
        );

        return $temporaryUrlGenerator;
    }

    private function getController(): TemporaryUrlController
    {
        $controller = $this->container
            ?->get('test.' . TemporaryUrlController::class);

        self::assertInstanceOf(
            TemporaryUrlController::class,
            $controller,
        );

        return $controller;
    }

    public function testWiring(): void
    {
        $this->getTemporaryUrlGenerator();
    }

    public function testTemporaryUrl(): void
    {
        $temporaryUrlGenerator = $this->getTemporaryUrlGenerator();

        $data = new Data('text/plain', 'foo', 'test.txt');
        $temporaryUrl = $temporaryUrlGenerator->generateUrl($data);

        self::assertStringStartsWith('/__temporary-url__/', $temporaryUrl);
    }

    public function testResponse(): void
    {
        $temporaryUrlGenerator = $this->getTemporaryUrlGenerator();

        $data = new Data('text/plain', 'foo', 'test.txt');
        $temporaryUrl = $temporaryUrlGenerator->generateUrl($data);

        self::assertStringStartsWith('/__temporary-url__/', $temporaryUrl);

        $ticket = preg_replace('/^.*\//', '', $temporaryUrl);
        self::assertNotNull($ticket);
        self::assertStringMatchesFormat('%x', $ticket);

        $controller = $this->getController();
        $response = $controller($ticket);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertEquals('text/plain', $response->headers->get('Content-Type'));
        self::assertEquals('foo', $response->getContent());
    }

    public function testInvalidTicket(): void
    {
        $controller = $this->getController();

        $this->expectException(TicketNotFoundException::class);

        $controller('invalid-ticket');
    }

    public function testTtl(): void
    {
        $temporaryUrlGenerator = $this->getTemporaryUrlGenerator();

        $data = new Data('text/plain', 'foo', 'test.txt');
        $temporaryUrl = $temporaryUrlGenerator->generateUrl($data, 2);

        self::assertStringStartsWith('/__temporary-url__/', $temporaryUrl);

        $ticket = preg_replace('/^.*\//', '', $temporaryUrl);
        self::assertNotNull($ticket);
        self::assertStringMatchesFormat('%x', $ticket);

        $controller = $this->getController();
        $response = $controller($ticket);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertEquals('text/plain', $response->headers->get('Content-Type'));
        self::assertEquals('foo', $response->getContent());

        sleep(2);

        $this->expectException(TicketNotFoundException::class);

        $controller($ticket);
    }

    public function testSessionPinning(): void
    {
        $temporaryUrlGenerator = $this->getTemporaryUrlGenerator();

        $data = new Data('text/plain', 'foo', 'test.txt');
        $temporaryUrl = $temporaryUrlGenerator->generateUrl($data, null, true);

        self::assertStringStartsWith('/__temporary-url__/', $temporaryUrl);

        $ticket = preg_replace('/^.*\//', '', $temporaryUrl);
        self::assertNotNull($ticket);
        self::assertStringMatchesFormat('%x', $ticket);

        // accessed from the same session

        $controller = $this->getController();
        $response = $controller($ticket);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertEquals('text/plain', $response->headers->get('Content-Type'));
        self::assertEquals('foo', $response->getContent());

        // accessed from different session

        $secondaryController = $this->container?->get(TemporaryUrlController::class . '.secondary');
        self::assertInstanceOf(TemporaryUrlController::class, $secondaryController);

        $this->expectException(WrongSessionException::class);
        $secondaryController($ticket);
    }

    public function testWithoutSessionPinning(): void
    {
        $temporaryUrlGenerator = $this->getTemporaryUrlGenerator();

        $data = new Data('text/plain', 'foo', 'test.txt');
        $temporaryUrl = $temporaryUrlGenerator->generateUrl($data, null, false);

        self::assertStringStartsWith('/__temporary-url__/', $temporaryUrl);

        $ticket = preg_replace('/^.*\//', '', $temporaryUrl);
        self::assertNotNull($ticket);
        self::assertStringMatchesFormat('%x', $ticket);

        // accessed from the same session

        $controller = $this->getController();
        $response = $controller($ticket);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertEquals('text/plain', $response->headers->get('Content-Type'));
        self::assertEquals('foo', $response->getContent());

        // accessed from different session

        $secondaryController = $this->container?->get(TemporaryUrlController::class . '.secondary');
        self::assertInstanceOf(TemporaryUrlController::class, $secondaryController);

        $response = $secondaryController($ticket);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertEquals('text/plain', $response->headers->get('Content-Type'));
        self::assertEquals('foo', $response->getContent());
    }

    public function testDataWithoutServer(): void
    {
        $temporaryUrlGenerator = $this->getTemporaryUrlGenerator();

        $data = new DataWithoutServer();

        $this->expectException(ServerNotFoundException::class);
        $temporaryUrlGenerator->generateUrl($data, null, false);
    }

    public function testUnionType(): void
    {
        $temporaryUrlGenerator = $this->getTemporaryUrlGenerator();

        $data1 = new Data1('foo');
        $data2 = new Data2('bar');
        $data3 = new Data3('baz');

        $url1 = $temporaryUrlGenerator->generateUrl($data1);
        $url2 = $temporaryUrlGenerator->generateUrl($data2);

        $this->expectException(ServerNotFoundException::class);
        $temporaryUrlGenerator->generateUrl($data3);

        self::assertStringStartsWith('/__temporary-url__/', $url1);
        self::assertStringStartsWith('/__temporary-url__/', $url2);
    }

    public function testUnserializableData(): void
    {
        $temporaryUrlGenerator = $this->getTemporaryUrlGenerator();
        $data = new UnserializableData('foo');
        $url = $temporaryUrlGenerator->generateUrl($data, null, false);
        self::assertStringStartsWith('/__temporary-url__/', $url);
    }
}
