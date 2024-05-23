<?php

namespace App\Models;

use App\Enums\YesNo;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LibraryBuilding extends Model
{
    use HasFactory;
    use CrudTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'copier',
        'additional_devices',
        'comment',
        'key',
        'key_depot',
        'key_comment',
        'operating_area',
        'audience_area',
        'staff_workspaces',
        'audience_workspaces',
        'workspace_comment',
        'space_comment'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'key' => 'boolean'
    ];

    public function library(): BelongsTo
    {
        return $this->belongsTo(Library::class);
    }
}
