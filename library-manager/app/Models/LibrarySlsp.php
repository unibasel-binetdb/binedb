<?php

namespace App\Models;

use App\Enums\IzUsageCost;
use App\Enums\SlspAgreement;
use App\Enums\SlspCost;
use App\Enums\SlspState;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LibrarySlsp extends Model
{
    use HasFactory;
    use CrudTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status',
        'status_comment',
        'cost',
        'usage',
        'cost_comment',
        'agreement',
        'agreement_comment',
        'ftes',
        'fte_comment',
        'comment'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'status' => SlspState::class,
        'cost' => SlspCost::class,
        'usage' => IzUsageCost::class,
        'agreement' => SlspAgreement::class,
    ];

    public function library(): BelongsTo
    {
        return $this->belongsTo(Library::class);
    }
}
