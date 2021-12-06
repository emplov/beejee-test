<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * @param string $password
     * @return string
     */
    public static function generatePassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }
}