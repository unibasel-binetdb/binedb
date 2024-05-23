<?php

namespace App\Models;

use App\Enums\UsageUnit;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Location extends Model
{
    use HasFactory;
    use CrudTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'loc_name',
        'example_rule',
        'usage_unit',
        'comment'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'usage_unit' => UsageUnit::class,
    ];

    public function library(): BelongsTo
    {
        return $this->belongsTo(Library::class);
    }
}
