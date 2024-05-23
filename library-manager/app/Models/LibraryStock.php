<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LibraryStock extends Model
{
    use HasFactory;
    use CrudTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'is_special_stock',
        'special_stock_comment',
        'is_depositum',
        'is_inst_depositum',
        'inst_depositum_comment',
        'pushback',
        'pushback_2010',
        'pushback_2020',
        'pushback_2030',
        'memory_library',
        'running_1899',
        'running_1999',
        'running_2000',
        'running_zss_1999',
        'running_zss_2000',
        'running_zss_1899',
        'comment'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'is_special_stock' => 'boolean',
        'is_depositum' => 'boolean',
        'is_inst_depositum' => 'boolean'
    ];

    public function library(): BelongsTo
    {
        return $this->belongsTo(Library::class);
    }
}
