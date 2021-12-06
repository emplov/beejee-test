<?php

use Rakit\Validation\Validator;
use Utils\Response;

if (!function_exists('response')) {
    /**
     * @param mixed|null $body
     * @return \Laminas\Diactoros\Response
     */
    function response(mixed $body = null): \Laminas\Diactoros\Response
    {
        $response = Response::getInstance()->response;

        if (!empty($body)) {
            $response->getBody()->write($body);
        }

        return $response;
    }
}

if (!function_exists('redirect')) {
    /**
     * @param string $url
     * @return \Laminas\Diactoros\Response
     */
    function redirect(string $url): \Laminas\Diactoros\Response
    {
        $response = Response::getInstance()->response;

        return $response->withHeader('Location', $url)->withStatus(302);
    }
}

if (!function_exists('env')) {
    /**
     * @param string $name
     * @param mixed|null $default
     * @return mixed
     */
    function env(string $name, mixed $default = null): mixed
    {
        if (isset($_ENV[$name])) {
            return $_ENV[$name];
        }

        return $default;
    }
}

if (!function_exists('validate')) {
    /**
     * @param array $inputs
     * @param array $rules
     * @return \Rakit\Validation\Validation
     */
    function validate(array $inputs, array $rules): \Rakit\Validation\Validation
    {
        $validator = new Validator;

        return $validator->validate($inputs, $rules);
    }
}
