<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ContactTopic;
use App\Enums\Institution;
use App\Enums\Occupation;
use App\Enums\SlspContact;
use App\Http\Requests\PersonFunctionRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PersonFunctionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PersonFunctionCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\PersonFunction::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/person-function');
        CRUD::setEntityNameStrings(trans('personFunction.singular'), trans('personFunction.plural'));
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
            $this->crud->query->select([
                'person_functions.*',  // Select all columns from the main table
                'people.id as people_id', // Alias the ID from the 'people' table
                'libraries.id as library_id', // Alias the ID from the 'libraries' table
                'people.last_name',
                'libraries.bibcode'
            ]);

            // Perform the joins
            $this->crud->query->join('people', 'people.id', '=', 'person_functions.person_id')
                ->join('libraries', 'libraries.id', '=', 'person_functions.library_id');

            // Order by the necessary fields
            $this->crud->query
                ->orderBy('libraries.is_active', 'desc')
                ->orderBy('exited', 'asc')
                ->orderBy('people.last_name')
                ->orderBy('libraries.bibcode');
        }

        if (!$hasFilter) {
            CRUD::filter("work")->label(trans('personFunction.work'))->type('select2')->values(function () {
                return Occupation::values();
            });

            CRUD::filter("exited")->label(trans('personFunction.state'))->type('select2')->values(function () {
                return [
                    'exited' => 'nur ausgetretene',
                    'all' => 'alle'
                ];
            })->value('active')->whenActive(function ($condition) {
                if ($condition == 'exited') {
                    CRUD::addClause(function($qry) {
                        $qry->where('exited', 1)->orWhere('libraries.is_active', 0);
                    });
                }
            })->whenInactive(function () {
                CRUD::addClause(function($qry) {
                    $qry->where('exited', 0)->where('libraries.is_active', 1);
                });
            });
        }

        $this->crud->addColumn([
            'name' => 'person',
            'label' => trans('person.singular'),
            'type' => 'custom_html',
            'value' => function ($entry) {
                return $entry->getPersonLink();
            },
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('person', function ($q) use ($column, $searchTerm) {
                    $q->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $searchTerm . '%']);
                });
            }
        ]);

        $this->crud->addColumn([
            'name' => 'work',
            'type' => 'enum',
            'enum_class' => Occupation::class,
            'enum_function' => 'translate',
            'label' => trans('personFunction.work')
        ]);


        $this->crud->addColumn([
            'name' => 'library',
            'label' => trans('library.singular'),
            'type' => 'custom_html',
            'value' => function ($entry) {
                return $entry->getLibraryLink();
            },
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('library', function ($q) use ($column, $searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('bibcode', 'like', '%' . $searchTerm . '%');
                });
            }
        ]);

        $this->crud->addColumn([
            'name' => 'email',
            'type' => 'string',
            'label' => trans('personFunction.email')
        ]);

        $this->crud->addColumn([
            'name' => 'phone',
            'type' => 'string',
            'label' => trans('personFunction.phone')
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
        $functionTab = trans('personFunction.singular');
        $contactTab = trans('contact.plural');

        CRUD::setValidation(PersonFunctionRequest::class);
        CRUD::removeSaveActions(['save_and_back', 'save_and_new', 'save_and_preview']);
        CRUD::replaceSaveActions([
            'name' => 'save_and_edit',
            'redirect' => function ($crud, $request, $itemId) {
                return $crud->route . '/' . $itemId . '/edit#' . $request->request->get('_current_tab');
            },
            'button_text' => trans('backpack::base.save'),
        ]);
        CRUD::setOperationSetting('showSaveActionChange', false);

        $defaultLibrary = request()->query('library_id', NULL);

        $this->crud->addField([
            'name' => 'library_id',
            'label' => trans('library.singular'),
            'type' => 'select2',
            'default' => $defaultLibrary,
            'entity' => 'library',
            'model' => "App\Models\Library",
            'attributes' => [
                'required' => true,
            ],
            'options' => function ($query) {
                $query->where('is_active', '1');

                $currentEntry = $this->crud->getCurrentEntry();
                if ($currentEntry != false)
                    $query->orWhere('id', $currentEntry->library_id);

                return $query->orderByRaw('bibcode IS NOT NULL DESC')->orderBy('bibcode', 'asc')->get();
            },
            'wrapper' => ['class' => 'form-group col-md-6'],
            'tab' => $functionTab
        ]);

        $this->crud->addField([
            'name' => 'person_id',
            'label' => trans('person.singular'),
            'type' => 'select2',
            'entity' => 'person',
            'model' => "App\Models\Person",
            'attributes' => [
                'required' => true,
            ],
            'options' => function ($query) {
                return $query->orderBy('first_name', 'ASC')->get();
            },
            'wrapper' => ['class' => 'form-group col-md-6'],
            'tab' => $functionTab
        ]);

        $this->crud->addField([
            'name' => 'library_link',
            'label' => trans('personFunction.libraryLink'),
            'type' => 'link',
            'link' => function ($entry) {
                return 'library/' . $entry->library_id . '/edit';
            },
            'wrapper' => ['class' => 'form-group col-md-6'],
            'tab' => $functionTab
        ]);

        $this->crud->addField([
            'name' => 'person_link',
            'label' => trans('personFunction.personLink'),
            'type' => 'link',
            'link' => function ($entry) {
                return 'person/' . $entry->person_id . '/edit';
            },
            'wrapper' => ['class' => 'form-group col-md-6'],
            'tab' => $functionTab
        ]);

        CRUD::field('phone')->type('text')->label(trans('personFunction.phone'))->size(4)->tab($functionTab);
        CRUD::field('email')->type('text')->label(trans('personFunction.email'))->size(4)->tab($functionTab);
        CRUD::field('work')->label(trans('personFunction.work'))->type('enum')
            ->enum_function('translate')->enum_class(Occupation::class)->size(4)->tab($functionTab);

        CRUD::field('work_start')->type('text')->label(trans('personFunction.workStart'))->size(4)->tab($functionTab);
        CRUD::field('work_end')->type('text')->label(trans('personFunction.workEnd'))->size(4)->tab($functionTab);
        CRUD::field('exited')->type('checkbox')->label(trans('personFunction.exited'))->wrapper(['class' => 'form-group col-md-4 pt-5'])->tab($functionTab);
        CRUD::field('percentage_of_employment')->type('text')->label(trans('personFunction.percentageOfEmployment'))->size(4)->tab($functionTab);
        CRUD::field('percentage_comment')->type('textarea')->attributes(['rows' => 5])->label(trans('personFunction.percentageComment'))->size(8)->tab($functionTab);
        CRUD::field('presence_times')->type('text')->label(trans('personFunction.presenceTimes'))->size(8)->tab($functionTab);

        CRUD::field('institution')->label(trans('personFunction.institution'))->showAsterisk(false)->attributes(['required' => true])->type('enum')
            ->enum_function('translate')->enum_class(Institution::class)->size(4)->tab($functionTab);
        CRUD::field('address_list')->type('checkbox')->label(trans('personFunction.addressList'))->size(4)->tab($functionTab);
        CRUD::field('email_list')->type('checkbox')->label(trans('personFunction.emailList'))->size(4)->tab($functionTab);
        CRUD::field('emclose')->type('new_section')->tab($functionTab);
        CRUD::field('personal_login')->type('checkbox')->label(trans('personFunction.personalLogin'))->size(4)->tab($functionTab);
        CRUD::field('personal_login_comment')->type('textarea')->attributes(['rows' => 5])->label(trans('personFunction.personalLoginComment'))->size(8)->tab($functionTab);
        CRUD::field('impersonal_login')->type('checkbox')->label(trans('personFunction.impersonalLogin'))->size(4)->tab($functionTab);
        CRUD::field('impersonal_login_comment')->type('textarea')->attributes(['rows' => 5])->label(trans('personFunction.impersonalLoginComment'))->size(8)->tab($functionTab);
        CRUD::field('slsp_contact')->label(trans('personFunction.slspContact'))->type('enum')
            ->enum_function('translate')->enum_class(SlspContact::class)->size(4)->tab($functionTab);

        CRUD::field('function_comment')->type('textarea')->attributes(['rows' => 5])->label(trans('personFunction.functionComment'))->size(12)->tab($functionTab);

        CRUD::field([
            'name' => 'contacts',
            'label' => trans('contact.singular'),
            'type' => 'collapsible',
            'tab' => $contactTab,
            'subfields' => [
                [
                    'name' => 'topic',
                    'type' => 'enum',
                    'enum_class' => ContactTopic::class,
                    'enum_function' => 'translate',
                    'label' => trans('contact.topic'),
                    'wrapper' => ['class' => 'form-group col-md-4'],
                    'attributes' => [
                        'required' => true,
                    ]
                ],
                [
                    'name' => 'comment',
                    'type' => 'text',
                    'label' => trans('contact.comment'),
                    'wrapper' => ['class' => 'form-group col-md-8'],
                ]
            ],
            'ajax' => true,
            'inline_create' => true,
            'reorder' => false,
            'new_item_label' => trans('personFunction.addContact')
        ]);
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}