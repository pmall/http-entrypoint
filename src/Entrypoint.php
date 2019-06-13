<?php declare(strict_types=1);

namespace Quanta\Http;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;

final class Entrypoint
{
    /**
     * The Psr-7 server request creator.
     *
     * @var callable
     */
    private $creator;

    /**
     * The Psr-15 request handler.
     *
     * @var \Psr\Http\Server\RequestHandlerInterface
     */
    private $handler;

    /**
     * The Psr-7 response emitter.
     *
     * @var callable
     */
    private $emitter;

    /**
     * Constructor.
     *
     * @param callable                                  $creator
     * @param \Psr\Http\Server\RequestHandlerInterface  $handler
     * @param callable                                  $emitter
     */
    public function __construct(
        callable $creator,
        RequestHandlerInterface $handler,
        callable $emitter
    ) {
        $this->creator = $creator;
        $this->handler = $handler;
        $this->emitter = $emitter;
    }

    /**
     * Run the application.
     *
     * @return void
     * @throws \UnexpectedValueException
     */
    public function run()
    {
        $request = ($this->creator)();

        if (! $request instanceof ServerRequestInterface) {
            throw new \UnexpectedValueException(
                vsprintf('%s expects an implementation of %s to be returned by the server request creator callable, %s returned', [
                    self::class,
                    ServerRequestInterface::class,
                    gettype($request),
                ])
            );
        }

        $response = $this->handler->handle($request);

        ($this->emitter)($response);
    }
}
