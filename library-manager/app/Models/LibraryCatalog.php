<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LibraryCatalog extends Model
{
    use HasFactory;
    use CrudTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'is_072',
        'is_082',
        'is_084',
        'nz_fields',
        'iz_fields',
        'comment'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'is_072' => 'boolean',
        'is_082' => 'boolean',
        'is_084' => 'boolean',
        'nz_fields' => 'array',
        'iz_fields' => 'array'
    ];

    public function library(): BelongsTo
    {
        return $this->belongsTo(Library::class);
    }
}
