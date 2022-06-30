<?php

namespace BBLDN\JSONRPC\Application\Hydrator;

use BBLDN\JSONRPC\Domain\DTO\JSONRPCRequest;
use BBLDN\JSONRPC\Domain\Exception\JSONRPCException;
use BBLDN\JSONRPC\Domain\Exception\ParseJSONException;
use BBLDN\JSONRPC\Domain\Exception\BadRequestException;
use BBLDN\JSONRPC\Domain\Exception\MethodNotFoundException;

class Hydrator
{
    /**
     * @param array $item
     * @return JSONRPCRequest
     * @throws JSONRPCException
     */
    private function hydrateItem(array $item): JSONRPCRequest
    {
        $method = $item['method'] ?? null;
        if (false === is_string($method)) {
            throw new MethodNotFoundException('The request must contain the name of the method');
        }

        $id = $item['id'] ?? null;
        if (null !== $id) {
            if (false === is_string($id) && false === is_int($id)) {
                throw new BadRequestException('id must be of type string or integer');
            }
        }

        return new JSONRPCRequest($method, $item['params'] ?? null, $id);
    }

    /**
     * @param string $data
     * @return JSONRPCRequest[]|JSONRPCRequest
     * @throws JSONRPCException
     *
     * @psalm-return list<JSONRPCRequest>|JSONRPCRequest
     */
    public function hydrate(string $data): JSONRPCRequest|array
    {
        $array = json_decode($data, true);
        if (false === $array || null === $array) {
            throw new ParseJSONException('Parsing error');
        }

        if (false === array_is_list($array)) {
            return $this->hydrateItem($array);
        }

        $result = [];
        foreach ($array as $item) {
            $result[] = $this->hydrateItem($item);
        }

        return $result;
    }
}