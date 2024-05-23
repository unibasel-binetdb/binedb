<?php

namespace App\Models;

use App\Enums\AssociatedType;
use App\Enums\Faculty;
use App\Enums\Provider;
use App\Enums\StateType;
use App\Enums\Sticker;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\HasTranslatableFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Library extends Model
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
        'is_active',
        'name',
        'name_addition',
        'short_name',
        'alternative_name',
        'bibcode',
        'existing_since',
        'shipping_street',
        'shipping_pobox',
        'shipping_zip',
        'shipping_location',
        'different_billing_address',
        'billing_name',
        'billing_name_addition',
        'billing_street',
        'billing_pobox',
        'billing_zip',
        'billing_location',
        'billing_comment',
        'library_comment',
        'institution_url',
        'library_url',
        'associated_type',
        'faculty',
        'departement',
        'uni_regulations',
        'bibstats_identification',
        'associated_comment',
        'uni_costcenter',
        'ub_costcenter',
        'finance_comment',
        'it_provider',
        'ip_address',
        'it_comment',
        'iz_library',
        'state_type',
        'state_since',
        'state_until',
        'state_comment',
        'location_comment',
        'storage',
        'sticker',
        'colletion_comment'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'is_active' => 'boolean',
        'different_billing_address' => 'boolean',
        'associated_type' => AssociatedType::class,
        'faculty' => Faculty::class,
        'it_provider' => Provider::class,
        'iz_library' => 'boolean',
        'state_type' => StateType::class,
        'sticker' => Sticker::class
    ];

    public function functions(): HasMany
    {
        return $this->hasMany(PersonFunction::class, 'library_id', 'id');
    }

    public function getLink($opaque = false)
    {
        return '<a class="'.($opaque ? 'opacity-30' : '').'" href="' . url('library/' . $this->id . '/edit') . '">' . ($this->bibcode ?? '') . ' ' . ($this->name ?? '') . '</a>';
    }

    public function function(): HasOne
    {
        return $this->hasOne(LibraryFunction::class, 'library_id', 'id');
    }

    public function building(): HasOne
    {
        return $this->hasOne(LibraryBuilding::class, 'library_id', 'id');
    }

    public function slsp(): HasOne
    {
        return $this->hasOne(LibrarySlsp::class, 'library_id', 'id');
    }

    public function stock(): HasOne
    {
        return $this->hasOne(LibraryStock::class, 'library_id', 'id');
    }

    public function catalog(): HasOne
    {
        return $this->hasOne(LibraryCatalog::class, 'library_id', 'id');
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class, 'library_id', 'id')->orderBy('code', 'asc');
    }

    public function desks(): HasMany
    {
        return $this->hasMany(Desk::class, 'library_id', 'id')->orderBy('code', 'asc');
    }

    public function signatureSpans(): HasMany
    {
        return $this->hasMany(SignatureSpan::class, 'library_id', 'id')->orderBy('span', 'asc');
    }

    public function signatureAssignments(): HasMany
    {
        return $this->hasMany(SignatureAssignment::class, 'library_id', 'id')->orderBy('assignment', 'asc');
    }

    public function getFullNameAttribute() {
        return $this->bibcode . ' ' . $this->name;
    }

    public function identifiableAttribute() {
        return 'full_name';
    }
}
