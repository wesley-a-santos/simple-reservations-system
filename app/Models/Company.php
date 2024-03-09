<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static create(mixed $validated)
 */
class Company extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CascadeSoftDeletes;

    protected $fillable = ['name'];

    protected array $cascadeDeletes = ['users'];

    protected array $dates = ['deleted_at'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }
}
