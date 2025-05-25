<?php

namespace App\Models;

use App\Enums\CatalogingLevel;
use App\Enums\Education;
use App\Enums\Training;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\HasTranslatableFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Person extends Model
{
    use HasTranslatableFields;
    use CrudTrait;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'gender',
        'first_name',
        'last_name',
        'seal',
        'comment',
        'training',
        'training_cataloging',
        'training_indexing',
        'training_acquisition',
        'training_magazine',
        'training_lending',
        'education',
        'slsp_acq',
        'slsp_acq_plus',
        'slsp_acq_certified',
        'digirech_share',
        'slsp_cat',
        'slsp_cat_plus',
        'slsp_cat_certified',
        'slsp_emedia',
        'slsp_emedia_plus',
        'slsp_emedia_certified',
        'slsp_circ',
        'slsp_circ_plus',
        'slsp_circ_certified',
        'slsp_circ_desk',
        'slsp_circ_limited',
        'slsp_student_certified',
        'slsp_analytics',
        'slsp_analytics_admin',
        'slsp_analytics_certified',
        'slsp_sysadmin',
        'slsp_staff_manager',
        'access_right_comment',
        'slsp_certification_comment',
        'cataloging_level',
        'sls_phere_access',
        'sls_phere_access_comment',
        'alma_completed',
        'edoc_login',
        'edoc_full_text',
        'edoc_bibliographic',
        'edoc_comment',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'training' => Training::class,
        'training_cataloging' => 'boolean',
        'training_indexing' => 'boolean',
        'training_acquisition' => 'boolean',
        'training_magazine' => 'boolean',
        'training_lending' => 'boolean',
        'training_emedia' => 'boolean',
        'education' => Education::class,
        'slsp_acq' => 'boolean',
        'slsp_acq_plus' => 'boolean',
        'slsp_acq_certified' => 'boolean',
        'digirech_share' => 'boolean',
        'slsp_cat' => 'boolean',
        'slsp_cat_plus' => 'boolean',
        'slsp_cat_certified' => 'boolean',
        'slsp_emedia' => 'boolean',
        'slsp_emedia_plus' => 'boolean',
        'slsp_emedia_certified' => 'boolean',
        'slsp_circ' => 'boolean',
        'slsp_circ_plus' => 'boolean',
        'slsp_circ_certified' => 'boolean',
        'slsp_circ_desk' => 'boolean',
        'slsp_circ_limited' => 'boolean',
        'slsp_student_certified' => 'boolean',
        'slsp_analytics' => 'boolean',
        'slsp_analytics_admin' => 'boolean',
        'slsp_analytics_certified' => 'boolean',
        'slsp_sysadmin' => 'boolean',
        'slsp_staff_manager' => 'boolean',
        'cataloging_level' => CatalogingLevel::class,
        'sls_phere_access' => 'boolean',
        'alma_completed' => 'boolean',
        'edoc_login' => 'boolean',
        'edoc_full_text' => 'boolean',
        'edoc_bibliographic' => 'boolean',
    ];

    public function functions(): HasMany
    {
        return $this->hasMany(PersonFunction::class, 'person_id', 'id')
            ->orderBy('exited', 'asc');
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn () => ($this->first_name ?? '') . ' ' . ($this->last_name ?? ''),
        );
    }

    public function getLink($opaque = false)
    {
        return '<a class="'.($opaque ? 'opacity-30' : '').'" href="' . url('person/' . $this->id . '/edit') . '">' . $this->name . '</a>';
    }

    public function getFullNameAttribute() {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function identifiableAttribute() {
        return 'full_name';
    }
}