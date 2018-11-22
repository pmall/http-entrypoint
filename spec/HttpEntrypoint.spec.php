<?php

use function Eloquent\Phony\Kahlan\stub;
use function Eloquent\Phony\Kahlan\mock;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Nyholm\Psr7Server\ServerRequestCreatorInterface;

use Quanta\HttpEntrypoint;

describe('HttpEntrypoint', function () {

    beforeEach(function () {

        $this->creator = mock(ServerRequestCreatorInterface::class);
        $this->handler = mock(RequestHandlerInterface::class);
        $this->emitter = stub();

        $this->entrypoint = new HttpEntrypoint(
            $this->creator->get(),
            $this->handler->get(),
            $this->emitter
        );

    });

    describe('->run()', function () {

        it('should run the application', function () {

            $request = mock(ServerRequestInterface::class);
            $response = mock(ResponseInterface::class);

            $this->creator->fromGlobals->returns($request);
            $this->handler->handle->with($request)->returns($response);

            $this->entrypoint->run();

            $this->emitter->once()->calledWith($response);

        });

    });

});
