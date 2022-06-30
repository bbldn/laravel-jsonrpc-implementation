<?php

namespace BBLDN\JSONRPC\Domain\DTO;

class JSONRPCRequest
{
    private string $method;

    private ?array $params;

    private int|string|null $id;

    /**
     * @param string $method
     * @param array|null $params
     * @param int|string|null $id
     */
    public function __construct(string $method, ?array $params, int|string|null $id)
    {
        $this->method = $method;
        $this->params = $params;
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return array|null
     */
    public function getParams(): ?array
    {
        return $this->params;
    }

    /**
     * @return int|string|null
     */
    public function getId(): int|string|null
    {
        return $this->id;
    }
}