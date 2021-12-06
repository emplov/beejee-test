<?php

namespace Utils;

use Laminas\Diactoros\Response as LeagueResponse;

class Response
{
    private static ?Response $_instance = null;

    public LeagueResponse $response;

    private function __construct()
    {
        $this->response = new LeagueResponse();
    }

    public static function getInstance(): ?Response
    {
        if (self::$_instance !== null)
        {
            return self::$_instance;
        }

        return self::$_instance = new self();
    }

    private function __clone(){}
}