<?php

namespace BBLDN\JSONRPC\Domain\Exception;

use Exception;

class JSONRPCException extends Exception
{
    /**
     * @param string $message
     * @param int $code
     */
    public function __construct(string $message, int $code = -32603)
    {
        parent::__construct($message, $code);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return ['code' => $this->code, 'message'=> $this->message];
    }
}