<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    const IN_PROCESS = 0;
    const DONE = 1;

    protected string $table = 'tasks';

    protected $fillable = [
        'name',
        'email',
        'text',
        'status',
    ];
}