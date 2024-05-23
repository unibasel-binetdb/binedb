<?php

namespace App\Models;

use App\Enums\Institution;
use App\Enums\Occupation;
use App\Enums\SlspContact;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\HasTranslatableFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PersonFunction extends Model
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
        'phone',
        'email',
        'work',
        'work_start',
        'work_end',
        'exited',
        'percentage_of_employment',
        'percentage_comment',
        'presence_times',
        'institution',
        'address_list',
        'email_list',
        'personal_login',
        'personal_login_comment',
        'impersonal_login',
        'impersonal_login_comment',
        'function_comment',
        'slsp_contact',
        'library_id',
        'person_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'person_id' => 'integer',
        'library_id' => 'integer',
        'exited' => 'boolean',
        'address_list' => 'boolean',
        'email_list' => 'boolean',
        'personal_login' => 'boolean',
        'impersonal_login' => 'boolean',
        'institution' => Institution::class,
        'slsp_contact' => SlspContact::class,
        'work' => Occupation::class
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function library(): BelongsTo
    {
        return $this->belongsTo(Library::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class)->orderBy('topic', 'asc');
    }

    public function getPersonLink() {
        return $this->person->getLink($this->exited || !$this->library->is_active);
    }

    public function getLibraryLink() {
        return $this->library->getLink($this->exited || !$this->library->is_active);
    }

    public function getTranslatedWorkAttribute()
    {
        return $this->work ? $this->work->translate() : null;
    }
}
