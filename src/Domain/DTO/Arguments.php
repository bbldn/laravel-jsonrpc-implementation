<?php

namespace BBLDN\JSONRPC\Domain\DTO;

class Arguments
{
    private ?array $paramList;

    private int|string|null $id;

    /**
     * @param array|null $paramList
     * @param int|string|null $id
     */
    public function __construct(?array $paramList, int|string|null $id)
    {
        $this->paramList = $paramList;
        $this->id = $id;
    }

    /**
     * @return array|null
     */
    public function getParamList(): ?array
    {
        return $this->paramList;
    }

    /**
     * @return int|string|null
     */
    public function getId(): int|string|null
    {
        return $this->id;
    }
}