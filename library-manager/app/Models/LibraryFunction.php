<?php

namespace App\Models;

use App\Enums\Acquisition;
use App\Enums\Digitization;
use App\Enums\PrintDaemon;
use App\Enums\SlsKey;
use App\Enums\SlspCarrier;
use App\Enums\SubjectIndexing;
use App\Enums\YesNo;
use App\Enums\YesNoAlma;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LibraryFunction extends Model
{
    use HasFactory;
    use CrudTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cataloging',
        'cataloging_comment',
        'subject_idx_local',
        'subject_idx_gnd',
        'subject_idx_comment',
        'acquisition',
        'acquisition_comment',
        'digitization',
        'digitization_comment',
        'sls_key',
        'sls_key_comment',
        'emedia',
        'emedia_comment',
        'magazine_management',
        'magazine_management_comment',
        'lending',
        'lending_comment',
        'self_lending',
        'self_lending_comment',
        'basel_carrier',
        'basel_carrier_comment',
        'slsp_carrier',
        'slsp_carrier_comment',
        'rfid',
        'rfid_comment',
        'slsp_bursar',
        'slsp_bursar_comment',
        'print_daemon',
        'print_daemon_comment'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'cataloging' => YesNo::class,
        'subject_idx_local' => YesNo::class,
        'subject_idx_gnd' => SubjectIndexing::class,
        'acquisition' => Acquisition::class,
        'sls_key' => SlsKey::class,
        'emedia' => YesNo::class,
        'digitization' => Digitization::class,
        'magazine_management' => YesNo::class,
        'lending' => YesNoAlma::class,
        'self_lending' => YesNo::class,
        'basel_carrier' => YesNo::class,
        'slsp_carrier' => SlspCarrier::class,
        'rfid' => YesNo::class,
        'slsp_bursar' => SlspCarrier::class,
        'print_daemon' => PrintDaemon::class
    ];

    public function library(): BelongsTo
    {
        return $this->belongsTo(Library::class);
    }
}
