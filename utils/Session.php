<?php

namespace Utils;

class Session
{
    /**
     * @return void
     */
    public static function start(): void
    {
        session_start();
    }

    /**
     * @param string $name
     * @return mixed
     */
    public static function get(string $name): mixed
    {
        return $_SESSION[$name] ?? null;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public static function put(string $name, mixed $value): void
    {
        $_SESSION[$name] = $value;
    }

    /**
     * @param string $name
     */
    public static function forget(string $name)
    {
        unset($_SESSION[$name]);
    }

    /**
     * @param string $name
     * @return bool
     */
    public static function has(string $name): bool
    {
        if (isset($_SESSION[$name])) {
            return true;
        }

        return false;
    }

    /**
     * @param string $name
     * @return bool
     */
    public static function exists(string $name): bool
    {
        return isset($_SESSION[$name]);
    }
}