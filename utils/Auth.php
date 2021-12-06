<?php

namespace Utils;

class Auth
{
    /**
     * @return bool
     */
    public static function isAuthenticated(): bool
    {
        return Session::has('user');
    }
    /**
     * @return bool
     */
    public static function isNotAuthenticated(): bool
    {
        return !Session::has('user');
    }
}