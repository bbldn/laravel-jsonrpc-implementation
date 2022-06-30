<?php

namespace BBLDN\JSONRPC\Domain\Exception;

class ParseJSONException extends JSONRPCException
{
    /**
     * @param string $message
     */
    public function __construct(string $message = 'Parse error')
    {
        parent::__construct($message, -32700);
    }
}