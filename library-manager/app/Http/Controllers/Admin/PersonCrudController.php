<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CatalogingLevel;
use App\Enums\Institution;
use App\Enums\Occupation;
use App\Enums\SlspContact;
use App\Enums\Training;
use App\Http\Requests\PersonRequest;
use App\Models\Library;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PersonCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PersonCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Person::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/person');
        CRUD::setEntityNameStrings(trans('person.singular'), trans('person.plural'));
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $request = $this->crud->getRequest();
        $hasFilter = $request->has('search') && strlen($request->input('search')['value']) > 0;

        if (!$this->crud->getRequest()->has('order')) {
            $this->crud->query->orderByDesc(
                function ($query) {
                    $query->selectRaw('MAX(case when person_functions.exited = 0 and libraries.is_active = 1 then 1 else 0 end)')
                        ->from('person_functions')
                        ->join('libraries', 'libraries.id', '=', 'person_functions.library_id')
                        ->whereColumn('person_functions.person_id', 'people.id');
                }
            )->orderBy('last_name');
        }

        if (!$hasFilter) {
            CRUD::filter("library")->label(trans('library.singular'))->type('select2_ajax')->values(backpack_url('library/fetch/search'))
                ->method('POST')->whenActive(function ($value) {
                    CRUD::addClause('whereHas', 'functions', function ($query) use ($value) {
                        $query->where('library_id', $value);
                    });
                });

            CRUD::filter("associated_type")->label(trans('personFunction.work'))->type('select2')->values(function () {
                return Occupation::values();
            })->whenActive(function ($value) {
                CRUD::addClause('whereHas', 'functions', function ($query) use ($value) {
                    $query->where('work', $value);
                });
            });

            CRUD::filter("exited")->label(trans('personFunction.state'))->type('select2')->values(function () {
                return [
                    'exited' => 'nur ausgetretene',
                    'all' => 'alle'
                ];
            })->value('active')->whenActive(function ($condition) {
                if ($condition == 'exited') {
                    CRUD::addClause('whereHas', 'functions', function ($query) {
                        $query->join('libraries', 'libraries.id', '=', 'person_functions.library_id');
                        $query->where('exited', 0)->where('libraries.is_active', 1);
                    }, '=', 0);                 
                }
            })->whenInactive(function () {
                CRUD::addClause('whereHas', 'functions', function ($query) {
                    $query->join('libraries', 'libraries.id', '=', 'person_functions.library_id');
                    $query->where('exited', 0)->where('libraries.is_active', 1);
                });
            });
        }

        CRUD::column('gender')->type('string')->label(trans('person.gender'));
        CRUD::column('first_name')->type('string')->label(trans('person.firstName'))->searchLogic(function ($query, $column, $searchTerm) {
            $query->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $searchTerm . '%']);
        });

        CRUD::column('last_name')->type('string')->label(trans('person.lastName'));

        $librarySort = [
            ['library.is_active', 'desc'],
            ['exited', 'asc'],
            ['library.fullName', 'asc'],
        ];

        $this->crud->addColumn([
            'name' => 'person_work',
            'property' => 'functions',
            'label' => trans('personFunction.work'),
            'type' => 'select_many',
            'sortBy' => $librarySort,
            'opaqueBy' => function($entry) {
                return $entry->exited || $entry->library->is_active == false;
            },
            'urlResolver' => function ($entry) {
                if ($entry == NULL)
                    return '#';

                return url('person-function/' . $entry->id . '/edit');
            },
            'entity' => 'functions',
            'attribute' => 'translated_work',
            'model' => 'App\Models\PersonFunction'
        ]);

        $this->crud->addColumn([
            'name' => 'person_libraries',
            'property' => 'functions',
            'label' => trans('library.singular'),
            'type' => 'select_many',
            'sortBy' => $librarySort,
            'opaqueBy' => function($entry) {
                return $entry->exited || $entry->library->is_active == false;
            },
            'urlResolver' => function ($entry) {
                if ($entry == NULL)
                    return '#';

                return url('library/' . $entry->library_id . '/edit');
            },
            'entity' => 'functions',
            'attribute' => 'library.fullName',
            'model' => 'App\Models\PersonFunction'
        ]);

        $this->crud->addColumn([
            'name' => 'person_emails',
            'property' => 'functions',
            'label' => trans('personFunction.email'),
            'type' => 'select_many',
            'sortBy' => $librarySort,
            'opaqueBy' => function($entry) {
                return $entry->exited || $entry->library->is_active == false;
            },
            'entity' => 'functions',
            'attribute' => 'email',
            'model' => 'App\Models\PersonFunction'
        ]);

        $this->crud->addColumn([
            'name' => 'person_phones',
            'property' => 'functions',
            'label' => trans('personFunction.phone'),
            'type' => 'select_many',
            'sortBy' => $librarySort,
            'opaqueBy' => function($entry) {
                return $entry->exited || $entry->library->is_active == false;
            },
            'entity' => 'functions',
            'attribute' => 'phone',
            'model' => 'App\Models\PersonFunction'
        ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        $personTab = trans('person.singular');
        $functionTab = trans('personFunction.singular');

        CRUD::setValidation(PersonRequest::class);
        CRUD::removeSaveActions(['save_and_back', 'save_and_new', 'save_and_preview']);
        CRUD::replaceSaveActions([
            'name' => 'save_and_edit',
            'redirect' => function ($crud, $request, $itemId) {
                return $crud->route . '/' . $itemId . '/edit#' . $request->request->get('_current_tab');
            },
            'button_text' => trans('backpack::base.save'),
        ]);

        if ($this->crud->getCurrentOperation() === 'create')
            CRUD::setHeading(trans('person.newPerson'));
        else {
            $personName = $this->crud->getCurrentEntry() ? $this->crud->getCurrentEntry()->first_name . ' ' . $this->crud->getCurrentEntry()->last_name : trans('person.singular');
            CRUD::setHeading($personName);
        }

        CRUD::field('gender')->label(trans('person.gender'))->size(4)->tab($personTab);

        CRUD::field('tlclose')->type('new_section')->tab($personTab);
        CRUD::field('first_name')->label(trans('person.firstName'))->size(4)->tab($personTab);
        CRUD::field('last_name')->label(trans('person.lastName'))->size(4)->tab($personTab);
        CRUD::field('seal')->label(trans('person.seal'))->size(4)->tab($personTab);
        CRUD::field('comment')->attributes(['rows' => 5])->label(trans('person.comment'))->size(6)->tab($personTab);
        CRUD::field('nmclose')->type('new_section')->title(trans('person.training'))->tab($personTab);
        CRUD::field('training')->label(trans('person.training'))->type('enum')
            ->enum_function('translate')->enum_class(Training::class)->size(3)->tab($personTab);

        CRUD::field('trclose')->type('new_section')->tab($personTab);
        CRUD::field('training_cataloging')->type('checkbox')->label(trans('person.trainingCataloging'))->size(3)->tab($personTab);
        CRUD::field('training_indexing')->type('checkbox')->label(trans('person.trainingIndexing'))->size(3)->tab($personTab);
        CRUD::field('training_acquisition')->type('checkbox')->label(trans('person.trainingAcquisition'))->size(3)->tab($personTab);
        CRUD::field('training_magazine')->type('checkbox')->label(trans('person.trainingMagazine'))->size(3)->tab($personTab);
        CRUD::field('training_lending')->type('checkbox')->label(trans('person.trainingLending'))->size(3)->tab($personTab);
        CRUD::field('training_emedia')->type('checkbox')->label(trans('person.trainingEmedia'))->size(3)->tab($personTab);

        CRUD::field('eduesction')->type('new_section')->title(trans('person.educationTitle'))->tab($personTab);
        CRUD::field('education')->label(trans('person.education'))->type('enum')
            ->enum_function('translate')->size(4)->tab($personTab);

        CRUD::field('certifiaction_title')->type('new_section')->title(trans('person.certifiactionTitle'))->tab($personTab);

        CRUD::field('acquisition_title')->type('new_section')->title(trans('person.acquisitionTitle'))->tab($personTab);
        CRUD::field('slsp_acq')->type('checkbox')->label(trans('person.slspAcq'))->size(3)->tab($personTab);
        CRUD::field('slsp_acq_plus')->type('checkbox')->label(trans('person.slspAcqPlus'))->size(3)->tab($personTab);
        CRUD::field('slsp_acq_certified')->type('checkbox')->label(trans('person.slspAcqCertified'))->size(3)->tab($personTab);
        CRUD::field('digirech_share')->type('checkbox')->label(trans('person.digirechShare'))->size(3)->tab($personTab);

        CRUD::field('res_mngt_title')->type('new_section')->title(trans('person.resMngtTitle'))->tab($personTab);
        CRUD::field('slsp_cat')->type('checkbox')->label(trans('person.slspCat'))->size(3)->tab($personTab);
        CRUD::field('slsp_cat_plus')->type('checkbox')->label(trans('person.slspCatPlus'))->size(3)->tab($personTab);
        CRUD::field('slsp_cat_certified')->type('checkbox')->label(trans('person.slspCatCertified'))->size(3)->tab($personTab);

        CRUD::field('eresource_title')->type('new_section')->title(trans('person.eresourceTitle'))->tab($personTab);
        CRUD::field('slsp_emedia')->type('checkbox')->label(trans('person.slspEmedia'))->size(3)->tab($personTab);
        CRUD::field('slsp_emedia_plus')->type('checkbox')->label(trans('person.slspEmediaPlus'))->size(3)->tab($personTab);
        CRUD::field('slsp_emedia_certified')->type('checkbox')->label(trans('person.slspEmediaCertified'))->size(3)->tab($personTab);

        CRUD::field('fulfillment_title')->type('new_section')->title(trans('person.fulfillmentTitle'))->tab($personTab);
        CRUD::field('slsp_circ')->type('checkbox')->label(trans('person.slspCirc'))->size(3)->tab($personTab);
        CRUD::field('slsp_circ_plus')->type('checkbox')->label(trans('person.slspCircPlus'))->size(3)->tab($personTab);
        CRUD::field('slsp_circ_certified')->type('checkbox')->label(trans('person.slspCircCertified'))->size(3)->tab($personTab);
        CRUD::field('slsp_newsection')->type('new_section')->tab($personTab);
        CRUD::field('slsp_circ_desk')->type('checkbox')->label(trans('person.slspCircDesk'))->size(3)->tab($personTab);
        CRUD::field('slsp_circ_limited')->type('checkbox')->label(trans('person.slspCircLimited'))->size(3)->tab($personTab);
        CRUD::field('slsp_student_certified')->type('checkbox')->label(trans('person.slspStudentCertified'))->size(3)->tab($personTab);

        CRUD::field('analytics_title')->type('new_section')->title(trans('person.analyticsTitle'))->tab($personTab);
        CRUD::field('slsp_analytics')->type('checkbox')->label(trans('person.slspAnalytics'))->size(3)->tab($personTab);
        CRUD::field('slsp_analytics_admin')->type('checkbox')->label(trans('person.slspAnalyticsAdmin'))->size(3)->tab($personTab);
        CRUD::field('slsp_analytics_certified')->type('checkbox')->label(trans('person.slspAnalyticsCertified'))->size(3)->tab($personTab);

        CRUD::field('sysadmin_title')->type('new_section')->title(trans('person.sysadminTitle'))->tab($personTab);
        CRUD::field('slsp_sysadmin')->type('checkbox')->label(trans('person.slspSysadmin'))->size(3)->tab($personTab);
        CRUD::field('slsp_staff_manager')->type('checkbox')->label(trans('person.slspStaffManager'))->size(3)->tab($personTab);

        CRUD::field('cmtclose')->type('new_section')->tab($personTab);
        CRUD::field('access_right_comment')->attributes(['rows' => 5])->label(trans('person.accessRightComment'))->size(6)->tab($personTab);
        CRUD::field('slsp_certification_comment')->attributes(['rows' => 5])->label(trans('person.slspCertificationComment'))->size(6)->tab($personTab);

        CRUD::field('cataloging_level_title')->type('new_section')->title(trans('person.catalogingLevel'))->tab($personTab);
        CRUD::field('cataloging_level')->label(trans('person.catalogingLevel'))->type('enum')
            ->enum_function('translate')->enum_class(CatalogingLevel::class)->size(3)->tab($personTab);

        CRUD::field('sls_phere_title')->type('new_section')->title(trans('person.slsPhereTitle'))->tab($personTab);
        CRUD::field('sls_phere_access')->type('checkbox')->label(trans('person.slsPhereAccess'))->size(3)->tab($personTab);
        CRUD::field('sls_phere_access_comment')->attributes(['rows' => 5])->label(trans('person.slsPhereAccessComment'))->size(8)->tab($personTab);

        CRUD::field('alma_title')->type('new_section')->title(trans('person.almaTitle'))->tab($personTab);
        CRUD::field('alma_completed')->type('checkbox')->label(trans('person.almaCompleted'))->size(3)->tab($personTab);

        CRUD::field('edoc_title')->type('new_section')->title(trans('person.edocTitle'))->tab($personTab);
        CRUD::field('edoc_login')->type('checkbox')->label(trans('person.edocLogin'))->size(3)->tab($personTab);
        CRUD::field('edoc_full_text')->type('checkbox')->label(trans('person.edocFullText'))->size(3)->tab($personTab);
        CRUD::field('edoc_bibliographic')->type('checkbox')->label(trans('person.edocBibliographic'))->size(3)->tab($personTab);
        CRUD::field('edocclose')->type('new_section')->tab($personTab);
        CRUD::field('edoc_comment')->attributes(['rows' => 5])->label(trans('person.edocComment'))->size(6)->tab($personTab);

        CRUD::field([
            'name' => 'functions',
            'label' => 'Funktionen',
            'type' => 'collapsible',
            'subfields' => [
                [
                    'name' => 'library_id',
                    'label' => trans('library.singular'),
                    'type' => 'select2',
                    'entity' => 'library',
                    'model' => "App\Models\Library",
                    'attributes' => [
                        'required' => true,
                    ],
                    'options' => (function ($query) {
                        $query->where('is_active', '1');

                        $currentEntry = $this->crud->getCurrentEntry();
                        if ($currentEntry != false) {
                            $libraryIds = $currentEntry->functions->map(function ($value) {
                                return $value->library_id;
                            })->toArray();

                            if (count($libraryIds) > 0)
                                $query->orWhereIn('id', $libraryIds);
                        }

                        return $query->orderByRaw('bibcode IS NOT NULL DESC')->orderBy('bibcode', 'asc')->get();
                    })
                ],
                [
                    'name' => 'library_link',
                    'type' => 'link',
                    'link' => function ($entry, $value) {
                        if($value == NULL || !array_key_exists('library_id', $value))
                            return NULL;

                        return 'library/' . $value['library_id'] . '/edit';
                    },
                    'label' => trans('personFunction.libraryLink'),
                    'wrapper' => ['class' => 'form-group col-md-4'],
                ],
                [
                    'name' => 'phone',
                    'type' => 'text',
                    'label' => trans('personFunction.phone'),
                    'wrapper' => ['class' => 'form-group col-md-4'],
                ],
                [
                    'name' => 'email',
                    'type' => 'text',
                    'label' => trans('personFunction.email'),
                    'wrapper' => ['class' => 'form-group col-md-4'],
                ],
                [
                    'name' => 'work',
                    'type' => 'enum',
                    'enum_class' => Occupation::class,
                    'enum_function' => 'translate',
                    'label' => trans('personFunction.work'),
                    'wrapper' => ['class' => 'form-group col-md-4'],
                ],
                [
                    'name' => 'work_start',
                    'type' => 'text',
                    'label' => trans('personFunction.workStart'),
                    'wrapper' => ['class' => 'form-group col-md-4'],
                ],
                [
                    'name' => 'work_end',
                    'type' => 'text',
                    'label' => trans('personFunction.workEnd'),
                    'wrapper' => ['class' => 'form-group col-md-4'],
                ],
                [
                    'name' => 'exited',
                    'type' => 'checkbox',
                    'label' => trans('personFunction.exited'),
                    'wrapper' => ['class' => 'form-group col-md-4 pt-5'],
                ],
                [
                    'name' => 'percentage_of_employment',
                    'type' => 'text',
                    'label' => trans('personFunction.percentageOfEmployment'),
                    'wrapper' => ['class' => 'form-group col-md-4'],
                ],
                [
                    'name' => 'percentage_comment',
                    'type' => 'textarea',
                    'attributes' => ['rows' => 5],
                    'label' => trans('personFunction.percentageComment'),
                    'wrapper' => ['class' => 'form-group col-md-8'],
                ],
                [
                    'name' => 'presence_times',
                    'type' => 'text',
                    'label' => trans('personFunction.presenceTimes'),
                    'wrapper' => ['class' => 'form-group col-md-8'],
                ],
                [
                    'name' => 'institution',
                    'type' => 'enum',
                    'enum_class' => Institution::class,
                    'enum_function' => 'translate',
                    'attributes' => ['required' => true],
                    'label' => trans('personFunction.institution'),
                    'wrapper' => ['class' => 'form-group col-md-4'],
                ],
                [
                    'name' => 'address_list',
                    'type' => 'checkbox',
                    'label' => trans('personFunction.addressList'),
                    'wrapper' => ['class' => 'form-group col-md-4'],
                ],
                [
                    'name' => 'email_list',
                    'type' => 'checkbox',
                    'label' => trans('personFunction.emailList'),
                    'wrapper' => ['class' => 'form-group col-md-8'],
                ],
                [
                    'name' => 'personal_login',
                    'type' => 'checkbox',
                    'label' => trans('personFunction.personalLogin'),
                    'wrapper' => ['class' => 'form-group col-md-4'],
                ],
                [
                    'name' => 'personal_login_comment',
                    'type' => 'textarea',
                    'attributes' => ['rows' => 5],
                    'label' => trans('personFunction.personalLoginComment'),
                    'wrapper' => ['class' => 'form-group col-md-8'],
                ],
                [
                    'name' => 'impersonal_login',
                    'type' => 'checkbox',
                    'label' => trans('personFunction.impersonalLogin'),
                    'wrapper' => ['class' => 'form-group col-md-4'],
                ],
                [
                    'name' => 'impersonal_login_comment',
                    'type' => 'textarea',
                    'attributes' => ['rows' => 5],
                    'label' => trans('personFunction.impersonalLoginComment'),
                    'wrapper' => ['class' => 'form-group col-md-8'],
                ],
                [
                    'name' => 'slsp_contact',
                    'type' => 'enum',
                    'enum_class' => SlspContact::class,
                    'enum_function' => 'translate',
                    'label' => trans('personFunction.slspContact'),
                    'wrapper' => ['class' => 'form-group col-md-4'],
                ],
                [
                    'name' => 'function_comment',
                    'type' => 'textarea',
                    'attributes' => ['rows' => 5],
                    'label' => trans('personFunction.functionComment'),
                    'wrapper' => ['class' => 'form-group col-md-12'],
                ],               
                [
                    'name' => 'contacts',
                    'type' => 'function_contacts',
                    'label' => trans('personFunction.contacts')
                ],
            ],
            'ajax' => true,
            'inline_create' => true,
            'reorder' => false,
            'collapsible' => true,
            'collapsible_head' => function ($e) {
                $lib = Library::find($e['library_id']);

                return [
                    'label' => $lib->bibcode . ' ' . $lib->name,
                    'opaque' => $lib->is_active == false || $e['exited'],
                    'sort' => ($lib->is_active == false || $e['exited'] ? '1' : '0') . $lib->bibcode . ' ' . $lib->name
                ];
            },
            'new_item_label' => trans('personFunction.addFunction'),
            'tab' => $functionTab
        ]);
    }

    public function fetchLibrary()
    {
        return $this->fetch(\App\Models\Library::class);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}