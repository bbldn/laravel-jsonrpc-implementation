<?php

namespace BBLDN\JSONRPC\Domain\DTO;

class JSONRPCResponse
{
    private mixed $error;

    private mixed $result;

    private int|string|null $id;

    /**
     * @param mixed|null $result
     * @param mixed|null $error
     * @param int|string|null $id
     */
    private function __construct(int|string|null $id, mixed $result = null, mixed $error = null)
    {
        $this->id = $id;
        $this->result = $result;
        $this->error = $error;
    }

    /**
     * @param mixed $result
     * @param int|string|null $id
     * @return JSONRPCResponse
     */
    public static function createSuccess(mixed $result, int|string|null $id): JSONRPCResponse
    {
        return new self($id, $result, null);
    }

    /**
     * @param mixed $error
     * @param int|string|null $id
     * @return JSONRPCResponse
     */
    public static function createError(mixed $error, int|string|null $id): JSONRPCResponse
    {
        return new self($id, null, $error);
    }

    /**
     * @return mixed
     */
    public function getResult(): mixed
    {
        return $this->result;
    }

    /**
     * @return mixed
     */
    public function getError(): mixed
    {
        return $this->error;
    }

    /**
     * @return int|string|null
     */
    public function getId(): int|string|null
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        if (null !== $this->error) {
            return [
                'id' => $this->id,
                'jsonrpc' => '2.0',
                'error' => $this->error,
            ];
        }

        return [
            'id' => $this->id,
            'jsonrpc' => '2.0',
            'result' => $this->result,
        ];
    }
}