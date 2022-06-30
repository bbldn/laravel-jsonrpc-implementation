<?php

namespace BBLDN\JSONRPC\Infrastructure\Symfony\Controller;

use Symfony\Component\HttpFoundation\Request;
use BBLDN\JSONRPC\Domain\DTO\JSONRPCResponse;
use BBLDN\JSONRPC\Application\Hydrator\Hydrator;
use BBLDN\JSONRPC\Domain\Exception\JSONRPCException;
use BBLDN\JSONRPC\Application\Kernel as JSONRPCKernel;
use BBLDN\JSONRPC\Domain\Symfony\JSONRPCResponse as JSONRPCResponseSymfony;

class JSONRPCController
{
    private Hydrator $hydrator;

    private JSONRPCKernel $kernel;

    /**
     * @param Hydrator $hydrator
     * @param JSONRPCKernel $kernel
     */
    public function __construct(Hydrator $hydrator, JSONRPCKernel $kernel)
    {
        $this->kernel = $kernel;
        $this->hydrator = $hydrator;
    }

    /**
     * @param Request $request
     * @return JSONRPCResponseSymfony
     * @throws JSONRPCException
     */
    public function entryPoint(Request $request): JSONRPCResponseSymfony
    {
        try {
            $requestList = $this->hydrator->hydrate((string)$request->getContent());
        } catch (JSONRPCException $e) {
            $response = JSONRPCResponse::createError($e->toArray(), null);

            return new JSONRPCResponseSymfony($response);
        }

        if (true === is_array($requestList)) {
            $response = $this->kernel->handleList($requestList);
        } else {
            $response = $this->kernel->handle($requestList);
        }

        return new JSONRPCResponseSymfony($response);
    }
}