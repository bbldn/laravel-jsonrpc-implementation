<?php

namespace BBLDN\JSONRPC\Domain\Symfony;

use Symfony\Component\HttpFoundation\Response;
use BBLDN\JSONRPC\Domain\DTO\JSONRPCResponse as JSONRPCResponseDTO;

class JSONRPCResponse extends Response
{
    protected $charset = 'UTF-8';

    /**
     * @param JSONRPCResponseDTO[]|JSONRPCResponseDTO|null $response
     * @param int $status
     * @param array $headers
     */
    public function __construct($response = null, int $status = self::HTTP_OK, array $headers = [])
    {
        parent::__construct('', $status, $headers);
        $this->setResponse($response);
    }

    /**
     * @param JSONRPCResponseDTO|JSONRPCResponseDTO[]|null $response
     * @return JSONRPCResponse
     *
     * @psalm-param list<JSONRPCResponseDTO>|JSONRPCResponseDTO|null $response
     *
     * @noinspection PhpReturnValueOfMethodIsNeverUsedInspection
     */
    private function setResponse(JSONRPCResponseDTO|array $response = null): self
    {
        if (null === $response) {
            return $this->setJson('');
        }

        if (true === is_array($response)) {
            $result = [];
            foreach ($response as $item) {
                $result[] = $item->toArray();
            }

            return $this->setJson(json_encode($result));
        }

        return $this->setJson(json_encode($response->toArray()));
    }

    /**
     * @param string $json
     * @return JSONRPCResponse
     */
    private function setJson(string $json): self
    {
        $key = 'Content-Type';
        if (false === $this->headers->has($key)) {
            $this->headers->set($key, 'application/json');
        }

        return $this->setContent($json);
    }
}