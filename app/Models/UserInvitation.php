<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, mixed $token)
 * @method static create(array $array)
 */
class UserInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'token',
        'registered_at',
        'company_id',
        'role_id',
    ];
}
