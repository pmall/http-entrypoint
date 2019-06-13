<?php

use function Eloquent\Phony\Kahlan\stub;
use function Eloquent\Phony\Kahlan\mock;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Quanta\Http\Entrypoint;

describe('Entrypoint', function () {

    beforeEach(function () {

        $this->creator = stub();
        $this->handler = mock(RequestHandlerInterface::class);
        $this->emitter = stub();

        $this->entrypoint = new Entrypoint(
            $this->creator,
            $this->handler->get(),
            $this->emitter
        );

    });

    describe('->run()', function () {

        context('when the server request creator returns an implementation of ServerRequestInterface', function () {

            it('should run the application', function () {

                $request = mock(ServerRequestInterface::class);
                $response = mock(ResponseInterface::class);

                $this->creator->returns($request);
                $this->handler->handle->with($request)->returns($response);

                $this->entrypoint->run();

                $this->emitter->once()->calledWith($response);

            });

        });

        context('when the server request creator does not return an implementation of ServerRequestInterface', function () {

            it('should throw an UnexpectedValueException', function () {

                $this->creator->returns(1);

                expect([$this->entrypoint, 'run'])->toThrow(new UnexpectedValueException);

            });

        });

    });

});
