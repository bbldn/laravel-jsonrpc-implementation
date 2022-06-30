<?php

namespace BBLDN\JSONRPC\Application;

use BBLDN\JSONRPC\Domain\DTO\Arguments;
use BBLDN\JSONRPC\Domain\DTO\JSONRPCRequest;
use BBLDN\JSONRPC\Domain\DTO\JSONRPCResponse;
use BBLDN\JSONRPC\Domain\Exception\JSONRPCException;
use Illuminate\Contracts\Container\Container as Container;
use BBLDN\JSONRPC\Domain\Exception\MethodNotFoundException;
use BBLDN\JSONRPC\Application\ResolverRegistry\ResolverRegistry;

class Kernel
{
    private Container $container;

    private ResolverRegistry $resolverRegistry;

    /**
     * @param Container $container
     * @param ResolverRegistry $resolverRegistry
     */
    public function __construct(
        Container $container,
        ResolverRegistry $resolverRegistry
    )
    {
        $this->container = $container;
        $this->resolverRegistry = $resolverRegistry;
    }

    /**
     * @param JSONRPCRequest $request
     * @return JSONRPCResponse|null
     * @throws JSONRPCException
     *
     * @noinspection PhpDocMissingThrowsInspection
     */
    private function handleRequest(JSONRPCRequest $request): ?JSONRPCResponse
    {
        $aliasMap = $this->resolverRegistry->getAll();
        $method = $request->getMethod();
        if (false === key_exists($method, $aliasMap)) {
            throw new MethodNotFoundException("Resolver for method: \"$method\" not found");
        }

        $requestId = $request->getId();
        [$resolverClass, $method] = $aliasMap[$method];
        $arguments = new Arguments($request->getParams(), $requestId);

        if (false === $this->container->has($resolverClass)) {
            throw new MethodNotFoundException("Resolver for method: \"$method\" not found");
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $resolverInstance = $this->container->get($resolverClass);

        try {
            $result = call_user_func([$resolverInstance, $method], $arguments);
        } catch (JSONRPCException $e) {
            if (null !== $requestId) {
                return JSONRPCResponse::createError($e->toArray(), $requestId);
            } else {
                return null;
            }
        }

        if (null !== $requestId) {
            if (false === is_a($result, JSONRPCResponse::class)) {
                return JSONRPCResponse::createSuccess($result, $requestId);
            } else {
                return $result;
            }
        }

        return null;
    }

    /**
     * @param JSONRPCRequest $request
     * @return JSONRPCResponse|null
     * @throws JSONRPCException
     */
    public function handle(JSONRPCRequest $request): ?JSONRPCResponse
    {
        return $this->handleRequest($request);
    }

    /**
     * @param JSONRPCRequest[] $requestList
     * @return JSONRPCResponse[]|null
     * @throws JSONRPCException
     *
     * @psalm-return list<JSONRPCResponse>|null
     * @psalm-param list<JSONRPCRequest> $requestList
     */
    public function handleList(array $requestList): ?array
    {
        $resultList = [];
        foreach ($requestList as $request) {
            $result = $this->handleRequest($request);
            if (null !== $result) {
                $resultList[] = $result;
            }
        }

        if (0 === count($resultList)) {
            return null;
        }

        return $resultList;
    }
}