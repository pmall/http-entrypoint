<?php declare(strict_types=1);

namespace Quanta;

use Psr\Http\Server\RequestHandlerInterface;

use Nyholm\Psr7Server\ServerRequestCreatorInterface;

final class HttpEntrypoint
{
    /**
     * The Psr-7 server request creator.
     *
     * @var \Nyholm\Psr7Server\ServerRequestCreatorInterface
     */
    private $creator;

    /**
     * The Psr-15 request handler (= the application).
     *
     * @var \Psr\Http\Server\RequestHandlerInterface
     */
    private $handler;

    /**
     * The callable used to emit the response.
     *
     * @var callable
     */
    private $emitter;

    /**
     * Constructor.
     *
     * @param \Nyholm\Psr7Server\ServerRequestCreatorInterface  $creator
     * @param \Psr\Http\Server\RequestHandlerInterface          $handler
     * @param callable                                          $emitter
     */
    public function __construct(
        ServerRequestCreatorInterface $creator,
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
     */
    public function run()
    {
        $request = $this->creator->fromGlobals();

        $response = $this->handler->handle($request);

        ($this->emitter)($response);
    }
}
