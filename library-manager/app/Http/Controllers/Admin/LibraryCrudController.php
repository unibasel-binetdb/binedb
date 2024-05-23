<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AssociatedType;
use App\Enums\Faculty;
use App\Enums\Institution;
use App\Enums\IzUsageCost;
use App\Enums\PrintDaemon;
use App\Enums\Provider;
use App\Enums\SignatureAssignmentType;
use App\Enums\SlspAgreement;
use App\Enums\SlspCarrier;
use App\Enums\SlspCost;
use App\Enums\SlspState;
use App\Enums\StateType;
use App\Enums\Sticker;
use App\Enums\SubjectIndexing;
use App\Enums\UsageUnit;
use App\Enums\YesNo;
use App\Enums\YesNoAlma;
use App\Http\Requests\LibraryRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class LibraryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class LibraryCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;

    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        store as traitStore;
    }

    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation {
        update as traitUpdate;
    }

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Library::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/library');
        CRUD::setEntityNameStrings(trans('library.singular'), trans('library.plural'));
    }

    protected function fetchSearch()
    {

        return $this->fetch([
            'model' => \App\Models\Library::class,
            'searchable_attributes' => ['name', 'bibcode'],
            'paginate' => 50,
            'searchOperator' => 'LIKE'
        ]);
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        if (!$this->crud->getRequest()->has('order'))
            $this->crud->query->orderByRaw('bibcode IS NOT NULL DESC')->orderBy('bibcode', 'asc');

        $request = $this->crud->getRequest();
        $hasFilter = $request->has('search') && strlen($request->input('search')['value']) > 0;

        if (!$hasFilter) {
            CRUD::filter("associated_type")->label(trans('library.associatedType'))->type('select2')->values(function () {
                return AssociatedType::values();
            });

            CRUD::filter("faculty")->label(trans('library.faculty'))->type('select2')->values(function () {
                return Faculty::values();
            });

            CRUD::filter("is_active")->label(trans('library.state'))->type('select2')->values(function () {
                return [
                    'inactive' => 'nur inaktive',
                    'all' => 'alle'
                ];
            })->value('active')->whenActive(function ($condition) {
                if ($condition == 'inactive')
                    CRUD::addClause('where', 'is_active', '0');
            })->whenInactive(function () {
                CRUD::addClause('where', 'is_active', '1');
            });
        }

        CRUD::column('bibcode')->type('string')->label(trans('library.bibcode'))->searchLogic(function ($query, $column, $searchTerm) {
            $query->orWhere('bibcode', 'like', '%' . $searchTerm . '%');
        });

        CRUD::column('name')->type('value_title')->label(trans('library.singular'))->searchLogic(function ($query, $column, $searchTerm) {
            $query->orWhere('name', 'like', '%' . $searchTerm . '%');
        });

        CRUD::column('associated_type')->label(trans('library.associatedType'))
            ->type('enum')
            ->enum_function('translate')
            ->enum_class(AssociatedType::class);

        CRUD::column('faculty')->label(trans('library.faculty'))
            ->type('enum')
            ->enum_function('translate')
            ->enum_class(Faculty::class);

        CRUD::column("is_active")->label(trans('library.activeInactive'))->type('boolean')->options([
            0 => trans('library.isInactive'),
            1 => trans('library.isActive')
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
        CRUD::setValidation(LibraryRequest::class);
        CRUD::removeSaveActions(['save_and_back', 'save_and_new', 'save_and_preview']);
        CRUD::replaceSaveActions([
            'name' => 'save_and_edit',
            'redirect' => function ($crud, $request, $itemId) {
                return $crud->route . '/' . $itemId . '/edit#' . $request->request->get('_current_tab');
            },
            'button_text' => trans('backpack::base.save'),
        ]);
        CRUD::setOperationSetting('showSaveActionChange', false);


        if ($this->crud->getCurrentOperation() === 'create')
            CRUD::setHeading(trans('library.new'));
        else {
            $libraryName = $this->crud->getCurrentEntry() ? $this->crud->getCurrentEntry()->bibcode . ' ' . $this->crud->getCurrentEntry()->name : trans('library.singular');
            CRUD::setHeading($libraryName);
        }

        $libraryTab = trans('library.singular');
        $employeeTab = 'Mitarbeitende (Institut / UB)';
        $almaCollectionTab = 'Alma-Collection';
        $almaFunctionsTab = 'Alma-Funktionsbereiche';
        $catalogingTab = 'Katalogisier-Info';
        $stockTab = 'Bestand';
        $buildingTab = 'Gebäude';
        $slspTab = 'SLSP';

        CRUD::field('name')->type("text")->label(trans('library.name'))->size(3)->tab($libraryTab);
        CRUD::field('name_addition')->type("text")->label(trans('library.nameAddition'))->size(3)->tab($libraryTab);
        CRUD::field('short_name')->type("text")->label(trans('library.shortName'))->size(3)->tab($libraryTab);
        CRUD::field('alternative_name')->type("textarea")->label(trans('library.alternativeName'))->size(3)->tab($libraryTab);
        CRUD::field('bibcode')->type("text")->label(trans('library.bibcode'))->size(3)->tab($libraryTab);
        CRUD::field('existing_since')->type("text")->label(trans('library.existingSince'))->size(3)->tab($libraryTab);
        CRUD::field('is_active')->type("checkbox")->label(trans('library.isActive'))->wrapper(['class' => 'form-group col-md-3 pt-5'])->tab($libraryTab);
        CRUD::field('institution_url')->type('url')->label(trans('library.institutionUrl'))->prefix('<i class="las la-globe"></i>')->size(6)->tab($libraryTab);
        CRUD::field('library_url')->type('url')->label(trans('library.libraryUrl'))->prefix('<i class="las la-globe"></i>')->size(6)->tab($libraryTab);
        CRUD::field('shipaddrsection')->type('new_section')->title('Postadresse')->tab($libraryTab);
        CRUD::field('shipping_street')->type("text")->label(trans('library.shippingStreet'))->prefix('<i class="las la-home"></i>')->size(3)->tab($libraryTab);
        CRUD::field('shipping_pobox')->type("text")->label(trans('library.shippingPoBox'))->prefix('<i class="las la-inbox"></i>')->size(3)->tab($libraryTab);
        CRUD::field('shipping_zip')->type("text")->label(trans('library.shippingZip'))->prefix('<i class="las la-map-marker"></i>')->size(3)->tab($libraryTab);
        CRUD::field('shipping_location')->type("text")->label(trans('library.shippingLocation'))->prefix('<i class="las la-map-signs"></i>')->size(3)->tab($libraryTab);
        CRUD::field([
            'name' => 'copy_shipping',
            'type' => 'copy_address',
            'fields' => ['name', 'name_addition', 'shipping_street', 'shipping_pobox', 'shipping_zip', 'shipping_location'],
            'tab' => $libraryTab
        ]);

        CRUD::field('billaddrsection')->type('new_section')->title('Rechnungsadresse')->tab($libraryTab);
        CRUD::field('billing_name')->type("text")->label(trans('library.billingName'))->size(3)->tab($libraryTab);
        CRUD::field('billing_name_addition')->type("text")->label(trans('library.billingNameAddition'))->size(3)->tab($libraryTab);
        CRUD::field('different_billing_address')->type('checkbox')->label(trans('library.differentBillingAddress'))->wrapper(['class' => 'form-group col-md-3 pt-5'])->tab($libraryTab);
        CRUD::field('billaddrsectionsep')->type('new_section')->tab($libraryTab);
        CRUD::field('billing_street')->type("text")->label(trans('library.billingStreet'))->prefix('<i class="las la-home"></i>')->size(3)->tab($libraryTab);
        CRUD::field('billing_pobox')->type("text")->label(trans('library.billingPoBox'))->prefix('<i class="las la-inbox"></i>')->size(3)->tab($libraryTab);
        CRUD::field('billing_zip')->type("text")->label(trans('library.billingZip'))->prefix('<i class="las la-map-marker"></i>')->size(3)->tab($libraryTab);
        CRUD::field('billing_location')->type("text")->label(trans('library.billingLocation'))->prefix('<i class="las la-map-signs"></i>')->size(3)->tab($libraryTab);
        CRUD::field([
            'name' => 'copy_billing',
            'type' => 'copy_address',
            'fields' => ['billing_name', 'billing_name_addition', 'billing_street', 'billing_pobox', 'billing_zip', 'billing_location'],
            'tab' => $libraryTab
        ]);

        CRUD::field('billing_comment')->type('textarea')->attributes(['rows' => 5])->label(trans('library.billingComment'))->size(12)->tab($libraryTab);
        CRUD::field('library_comment')->type('textarea')->attributes(['rows' => 5])->label(trans('library.libraryComment'))->size(12)->tab($libraryTab);
        CRUD::field('blcmtsep')->type('new_section')->title('URL\'s')->tab($libraryTab);
        CRUD::field('blcmtsep')->type('new_section')->title('Zugehörigkeit')->tab($libraryTab);
        CRUD::field('associated_type')->label(trans('library.associatedType'))->type('enum')
            ->enum_function('translate')->enum_class(AssociatedType::class)->size(6)->tab($libraryTab);
        CRUD::field('associated_comment')->type('textarea')->attributes(['rows' => 5])->label(trans('library.associatedComment'))->size(6)->tab($libraryTab);
        CRUD::field('faculty')->label(trans('library.faculty'))->type('enum')
            ->enum_function('translate')->enum_class(Faculty::class)->size(4)->tab($libraryTab);
        CRUD::field('departement')->type('text')->label(trans('library.departement'))->size(4)->tab($libraryTab);
        CRUD::field('uni_regulations')->type('text')->label(trans('library.uniRegulations'))->size(4)->tab($libraryTab);
        CRUD::field('bibstats_identification')->type('text')->label(trans('library.bibstatsIdentification'))->size(4)->tab($libraryTab);

        CRUD::field('financesec')->type('new_section')->title('Finanzen')->tab($libraryTab);
        CRUD::field('uni_costcenter')->type('text')->label(trans('library.uniCostcenter'))->size(3)->tab($libraryTab);
        CRUD::field('ub_costcenter')->type('text')->label(trans('library.ubCostcenter'))->size(3)->tab($libraryTab);
        CRUD::field('finance_comment')->type('textarea')->attributes(['rows' => 5])->label(trans('library.financeComment'))->size(6)->tab($libraryTab);
        CRUD::field('itsec')->type('new_section')->title('IT Infrastruktur')->tab($libraryTab);
        CRUD::field('it_provider')->label(trans('library.itProvider'))->type('enum')
            ->enum_function('translate')->enum_class(Provider::class)->size(3)->tab($libraryTab);
        CRUD::field('ip_address')->type('text')->label(trans('library.ipAddress'))->size(3)->tab($libraryTab);
        CRUD::field('it_comment')->type('textarea')->attributes(['rows' => 5])->label(trans('library.itComment'))->size(6)->tab($libraryTab);
        CRUD::field('statussec')->type('new_section')->title('Status')->tab($libraryTab);
        CRUD::field('iz_library')->type('checkbox')->label(trans('library.izLibrary'))->wrapper(['class' => 'form-group col-md-3 pt-5'])->tab($libraryTab);
        CRUD::field('state_type')->label(trans('library.stateType'))->type('enum')
            ->enum_function('translate')->enum_class(StateType::class)->size(3)->tab($libraryTab);
        CRUD::field('state_since')->type('text')->label(trans('library.stateSince'))->size(3)->tab($libraryTab);
        CRUD::field('state_until')->type('text')->label(trans('library.stateUntil'))->size(3)->tab($libraryTab);
        CRUD::field('state_comment')->type('textarea')->attributes(['rows' => 5])->label(trans('library.stateComment'))->size(6)->tab($libraryTab);

        //employees
        CRUD::field('employeeTitle')->type('new_section')->title(trans('library.employees'))->tab($employeeTab);

        $this->crud->addField([
            'name' => 'create_function_link',
            'label' => trans('library.addFunction'),
            'style' => 'btn',
            'type' => 'link',
            'link' => function ($entry) {
                return 'person-function/create?library_id=' . $entry->id;
            },
            'wrapper' => ['class' => 'form-group col-md-2'],
            'tab' => $employeeTab
        ]);

        $this->crud->addField([
            'name' => 'create_person_link',
            'label' => trans('library.addPerson'),
            'style' => 'btn',
            'type' => 'link',
            'link' => function ($entry) {
                return 'person/create';
            },
            'wrapper' => ['class' => 'form-group col-md-2'],
            'tab' => $employeeTab
        ]);

        CRUD::field([
            'name' => 'institutions_employees',
            'label' => trans('library.institutionEmployees'),
            'type' => 'view',
            'view' => 'library/employee_list',
            'institution' => Institution::Institution,
            'tab' => $employeeTab
        ]);

        CRUD::field([
            'name' => 'ub_employees',
            'label' => trans('library.ubEmployees'),
            'type' => 'view',
            'view' => 'library/employee_list',
            'institution' => Institution::Ub,
            'tab' => $employeeTab
        ]);

        //Alma-Funktionsbereiche
        CRUD::field('location_title')->type('new_section')->title(trans('library.locationTitle'))->tab($almaCollectionTab);
        CRUD::field([
            'name' => 'locations',
            'label' => '',
            'type' => 'collapsible',
            'tab' => $almaCollectionTab,
            'subfields' => [
                [
                    'name' => 'code',
                    'type' => 'text',
                    'label' => trans('location.code'),
                    'wrapper' => ['class' => 'form-group col-md-1'],
                ],
                [
                    'name' => 'loc_name',
                    'type' => 'text',
                    'label' => trans('location.name'),
                    'wrapper' => ['class' => 'form-group col-md-2'],
                ],
                [
                    'name' => 'example_rule',
                    'type' => 'text',
                    'label' => trans('location.exampleRule'),
                    'wrapper' => ['class' => 'form-group col-md-2'],
                ],
                [
                    'name' => 'usage_unit',
                    'type' => 'enum',
                    'enum_class' => UsageUnit::class,
                    'enum_function' => 'translate',
                    'label' => trans('location.usageUnit'),
                    'wrapper' => ['class' => 'form-group col-md-2'],
                ],
                [
                    'name' => 'comment',
                    'type' => 'textarea',
                    'label' => trans('location.comment'),
                    'attributes' => ['rows' => 3],
                    'wrapper' => ['class' => 'form-group col-md-5'],
                ]
            ],
            'ajax' => true,
            'inline_create' => true,
            'reorder' => false,
            'new_item_label' => trans('location.addLocation')
        ]);

        CRUD::field('location_comment')->type('textarea')->attributes(['rows' => 5])->label(trans('library.locationComment'))->size(12)->tab($almaCollectionTab);

        CRUD::field('desk_title')->type('new_section')->title(trans('library.deskTitle'))->tab($almaCollectionTab);
        CRUD::field([
            'name' => 'desks',
            'label' => '',
            'type' => 'collapsible',
            'tab' => $almaCollectionTab,
            'subfields' => [
                [
                    'name' => 'code',
                    'type' => 'text',
                    'label' => trans('desk.code'),
                    'wrapper' => ['class' => 'form-group col-md-2'],
                ],
                [
                    'name' => 'name',
                    'type' => 'text',
                    'label' => trans('desk.name'),
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name' => 'comment',
                    'type' => 'textarea',
                    'attributes' => ['rows' => 3],
                    'label' => trans('desk.comment'),
                    'wrapper' => ['class' => 'form-group col-md-7'],
                ]
            ],
            'ajax' => true,
            'inline_create' => true,
            'reorder' => false,
            'new_item_label' => trans('desk.addDesk')
        ]);

        CRUD::field('signature_title')->type('new_section')->title(trans('library.signatureTitle'))->tab($almaCollectionTab);
        CRUD::field([
            'name' => 'signatureSpans',
            'label' => trans('signature.signatureSpan'),
            'type' => 'collapsible',
            'tab' => $almaCollectionTab,
            'subfields' => [
                [
                    'name' => 'span',
                    'type' => 'text',
                    'label' => trans('signature.span'),
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name' => 'comment',
                    'type' => 'textarea',
                    'attributes' => ['rows' => 3],
                    'label' => trans('signature.comment'),
                    'wrapper' => ['class' => 'form-group col-md-9'],
                ]
            ],
            'ajax' => true,
            'inline_create' => true,
            'reorder' => false,
            'new_item_label' => trans('signature.addSignatureSpan')
        ]);

        CRUD::field([
            'name' => 'signatureAssignments',
            'label' => trans('signature.signatureAssignment'),
            'type' => 'collapsible',
            'tab' => $almaCollectionTab,
            'subfields' => [
                [
                    'name' => 'assignment',
                    'type' => 'enum',
                    'enum_class' => SignatureAssignmentType::class,
                    'enum_function' => 'translate',
                    'label' => trans('signature.assignment'),
                    'wrapper' => ['class' => 'form-group col-md-3'],
                ],
                [
                    'name' => 'comment',
                    'type' => 'textarea',
                    'attributes' => ['rows' => 3],
                    'label' => trans('signature.comment'),
                    'wrapper' => ['class' => 'form-group col-md-9'],
                ]
            ],
            'ajax' => true,
            'inline_create' => true,
            'reorder' => false,
            'new_item_label' => trans('signature.addSignature')
        ]);

        CRUD::field('storage')->type("text")->label(trans('library.storage'))->size(4)->tab($almaCollectionTab);
        CRUD::field('stickers_title')->type('new_section')->title(trans('library.stickersTitle'))->tab($almaCollectionTab);
        CRUD::field('sticker')->label(trans('library.sticker'))->type('enum')
            ->enum_function('translate')->enum_class(Sticker::class)->size(4)->tab($almaCollectionTab);
        CRUD::field('stickers_clear')->type('new_section')->tab($almaCollectionTab);
        CRUD::field('comment_title')->type('new_section')->title(trans('library.commentTitle'))->tab($almaCollectionTab);
        CRUD::field('colletion_comment')->type("textarea")->attributes(['rows' => 5])->label(trans('library.colletionComment'))->size(9)->tab($almaCollectionTab);

        //Function
        CRUD::field('function.cataloging_and_acquisition_title')->type('new_section')->title(trans('libraryFunction.catalogingTitle'))->tab($almaFunctionsTab);
        CRUD::field('function.cataloging')->label(trans('libraryFunction.cataloging'))->type('enum')
            ->enum_function('translate')->enum_class(YesNo::class)->size(3)->tab($almaFunctionsTab);
        CRUD::field('function.cataloging_comment')->type("textarea")->attributes(['rows' => 5])->label(trans('libraryFunction.catalogingComment'))->size(9)->tab($almaFunctionsTab);
        CRUD::field('function.subject_idx_local')->label(trans('libraryFunction.subjectIdxLocal'))->type('enum')
            ->enum_function('translate')->enum_class(YesNo::class)->size(3)->tab($almaFunctionsTab);
        CRUD::field('function.subject_idx_gnd')->label(trans('libraryFunction.subjectIdxGnd'))->type('enum')
            ->enum_function('translate')->enum_class(SubjectIndexing::class)->size(3)->tab($almaFunctionsTab);
        CRUD::field('function.subject_idx_comment')->type("textarea")->attributes(['rows' => 5])->label(trans('libraryFunction.subjectIdxComment'))->size(6)->tab($almaFunctionsTab);
        CRUD::field('function.acquisition')->label(trans('libraryFunction.acquisition'))->type('enum')
            ->enum_function('translate')->enum_class(YesNo::class)->size(3)->tab($almaFunctionsTab);
        CRUD::field('function.acquisition_comment')->type("textarea")->attributes(['rows' => 5])->label(trans('libraryFunction.acquisitionComment'))->size(9)->tab($almaFunctionsTab);
        CRUD::field('function.magazine_management')->label(trans('libraryFunction.magazineManagement'))->type('enum')
            ->enum_function('translate')->enum_class(YesNo::class)->size(3)->tab($almaFunctionsTab);
        CRUD::field('function.magazine_management_comment')->type("textarea")->attributes(['rows' => 5])->label(trans('libraryFunction.magazineManagementComment'))->size(9)->tab($almaFunctionsTab);
        CRUD::field('function.lending_title')->type('new_section')->title(trans('libraryFunction.lendingTitle'))->tab($almaFunctionsTab);
        CRUD::field('function.lending')->label(trans('libraryFunction.lending'))->type('enum')
            ->enum_function('translate')->enum_class(YesNoAlma::class)->size(3)->tab($almaFunctionsTab);
        CRUD::field('function.lending_comment')->type("textarea")->attributes(['rows' => 5])->label(trans('libraryFunction.lendingComment'))->size(9)->tab($almaFunctionsTab);
        CRUD::field('function.self_lending')->label(trans('libraryFunction.selfLending'))->type('enum')
            ->enum_function('translate')->enum_class(YesNo::class)->size(3)->tab($almaFunctionsTab);
        CRUD::field('function.self_lending_comment')->type("textarea")->attributes(['rows' => 5])->label(trans('libraryFunction.selfLendingComment'))->size(9)->tab($almaFunctionsTab);
        CRUD::field('function.basel_carrier')->label(trans('libraryFunction.baselCarrier'))->type('enum')
            ->enum_function('translate')->enum_class(YesNo::class)->size(3)->tab($almaFunctionsTab);
        CRUD::field('function.basel_carrier_comment')->type("textarea")->attributes(['rows' => 5])->label(trans('libraryFunction.baselCarrierComment'))->size(9)->tab($almaFunctionsTab);
        CRUD::field('function.slsp_carrier')->label(trans('libraryFunction.slspCarrier'))->type('enum')
            ->enum_function('translate')->enum_class(SlspCarrier::class)->size(3)->tab($almaFunctionsTab);
        CRUD::field('function.slsp_carrier_comment')->type("textarea")->attributes(['rows' => 5])->label(trans('libraryFunction.slspCarrierComment'))->size(9)->tab($almaFunctionsTab);
        CRUD::field('function.rfid')->label(trans('libraryFunction.rfid'))->type('enum')
            ->enum_function('translate')->enum_class(YesNo::class)->size(3)->tab($almaFunctionsTab);
        CRUD::field('function.rfid_comment')->type("textarea")->attributes(['rows' => 5])->label(trans('libraryFunction.rfidComment'))->size(9)->tab($almaFunctionsTab);
        CRUD::field('function.slsp_bursar')->label(trans('libraryFunction.slspBursar'))->type('enum')
            ->enum_function('translate')->enum_class(SlspCarrier::class)->size(3)->tab($almaFunctionsTab);
        CRUD::field('function.slsp_bursar_comment')->type("textarea")->attributes(['rows' => 5])->label(trans('libraryFunction.slspBursarComment'))->size(9)->tab($almaFunctionsTab);
        CRUD::field('function.print_daemon')->label(trans('libraryFunction.printDaemon'))->type('enum')
            ->enum_function('translate')->enum_class(PrintDaemon::class)->size(3)->tab($almaFunctionsTab);
        CRUD::field('function.print_daemon_comment')->type("textarea")->attributes(['rows' => 5])->label(trans('libraryFunction.printDaemonComment'))->size(9)->tab($almaFunctionsTab);

        //Catalog
        CRUD::field('catalog.catalog_title')->type('new_section')->title(trans('libraryCatalog.catalogTitle'))->tab($catalogingTab);
        CRUD::field('catalog.is_072')->type('checkbox')->label(trans('libraryCatalog.is072'))->size(3)->tab($catalogingTab);
        CRUD::field('catalog.is_082')->type('checkbox')->label(trans('libraryCatalog.is082'))->size(3)->tab($catalogingTab);
        CRUD::field('catalog.is_084')->type('checkbox')->label(trans('libraryCatalog.is084'))->size(3)->tab($catalogingTab);

        CRUD::field([
            'name' => 'catalog.nz_fields',
            'label' => trans('libraryCatalog.nzFields'),
            'type' => 'table',
            'entity_singular' => trans('libraryCatalog.fieldSingular'),
            'tab' => $catalogingTab,
            'columns' => [
                'field' => [
                    'label' => trans('libraryCatalog.fields.field'),
                    'size' => 13
                ],
                'subfield' => [
                    'label' => trans('libraryCatalog.fields.subfield'),
                    'size' => 12
                ],
                'subfieldOrigin' => [
                    'label' => trans('libraryCatalog.fields.subfieldOrigin'),
                    'size' => 12
                ],
                'code' => [
                    'label' => trans('libraryCatalog.fields.code'),
                    'size' => 12,
                ],
                'name' => [
                    'label' => trans('libraryCatalog.fields.name'),
                    'size' => 12
                ],
                'comment' => [
                    'label' => trans('libraryCatalog.fields.comment'),
                    'size' => 39,
                    'type' => 'textarea'
                ],
            ]
        ]);

        CRUD::field([
            'name' => 'catalog.iz_fields',
            'label' => trans('libraryCatalog.izFields'),
            'type' => 'table',
            'entity_singular' => trans('libraryCatalog.fieldSingular'),
            'tab' => $catalogingTab,
            'columns' => [
                'field' => [
                    'label' => trans('libraryCatalog.fields.field'),
                    'size' => 15,
                ],
                'subfield' => [
                    'label' => trans('libraryCatalog.fields.subfield'),
                    'size' => 15,
                ],
                'code' => [
                    'label' => trans('libraryCatalog.fields.code'),
                    'size' => 15,
                ],
                'name' => [
                    'label' => trans('libraryCatalog.fields.name'),
                    'size' => 15,
                ],
                'comment' => [
                    'label' => trans('libraryCatalog.fields.comment'),
                    'size' => 36,
                    'type' => 'textarea'
                ]
            ]
        ]);

        CRUD::field('catalog.comment')->type("textarea")->attributes(['rows' => 5])->label(trans('libraryCatalog.comment'))->size(12)->tab($catalogingTab);

        //Stock
        CRUD::field('stock.stock_title')->type('new_section')->title(trans('libraryStock.stockTitle'))->tab($stockTab);
        CRUD::field('stock.is_special_stock')->type('checkbox')->label(trans('libraryStock.isSpecialStock'))->wrapper(['class' => 'form-group col-md-3 pt-5'])->tab($stockTab);
        CRUD::field('stock.special_stock_comment')->type("textarea")->attributes(['rows' => 5])->label(trans('libraryStock.specialStockComment'))->size(6)->tab($stockTab);
        CRUD::field('stock.depositum_title')->type('new_section')->title(trans('libraryStock.depositumTitle'))->tab($stockTab);
        CRUD::field('stock.is_depositum')->type('checkbox')->label(trans('libraryStock.isDepositum'))->wrapper(['class' => 'form-group col-md-3 pt-5'])->tab($stockTab);
        CRUD::field('stock.is_inst_depositum')->type('checkbox')->label(trans('libraryStock.isInstDepositum'))->wrapper(['class' => 'form-group col-md-3 pt-5'])->tab($stockTab);
        CRUD::field('stock.inst_depositum_comment')->type("textarea")->attributes(['rows' => 5])->label(trans('libraryStock.instDepositumComment'))->size(6)->tab($stockTab);
        CRUD::field('stock.pushback_title')->type('new_section')->title(trans('libraryStock.pushback'))->tab($stockTab);
        CRUD::field('stock.pushback')->type("text")->label(trans('libraryStock.pushback'))->size(12)->tab($stockTab);
        CRUD::field('stock.pushback_2010')->type("text")->label(trans('libraryStock.pushback2010'))->size(12)->tab($stockTab);
        CRUD::field('stock.pushback_2020')->type("text")->label(trans('libraryStock.pushback2020'))->size(12)->tab($stockTab);
        CRUD::field('stock.pushback_2030')->type("text")->label(trans('libraryStock.pushback2030'))->size(12)->tab($stockTab);
        CRUD::field('stock.memory_library_title')->type('new_section')->title(trans('libraryStock.memoryLibrary'))->tab($stockTab);
        CRUD::field('stock.memory_library')->type("text")->label(trans('libraryStock.memoryLibrary'))->size(3)->tab($stockTab);
        CRUD::field('stock.running_title')->type('new_section')->title(trans('libraryStock.runningTitle'))->tab($stockTab);
        CRUD::field('stock.running_1899')->type("text")->label(trans('libraryStock.running1899'))->size(4)->tab($stockTab);
        CRUD::field('stock.running_1999')->type("text")->label(trans('libraryStock.running1999'))->size(4)->tab($stockTab);
        CRUD::field('stock.running_2000')->type("text")->label(trans('libraryStock.running2000'))->size(4)->tab($stockTab);
        CRUD::field('stock.running_zss_1899')->type("text")->label(trans('libraryStock.runningZss1899'))->size(4)->tab($stockTab);
        CRUD::field('stock.running_zss_1999')->type("text")->label(trans('libraryStock.runningZss1999'))->size(4)->tab($stockTab);
        CRUD::field('stock.running_zss_2000')->type("text")->label(trans('libraryStock.runningZss2000'))->size(4)->tab($stockTab);
        CRUD::field('stock.comment')->type("textarea")->attributes(['rows' => 5])->label(trans('libraryStock.stockComment'))->size(12)->tab($stockTab);

        //Building
        CRUD::field('building_title')->type('new_section')->title(trans('libraryBuilding.buildingTitle'))->tab($buildingTab);
        CRUD::field('building.comment')->type("textarea")->attributes(['rows' => 4])->label(trans('libraryBuilding.comment'))->size(12)->tab($buildingTab);

        CRUD::field('devices_title')->type('new_section')->title(trans('libraryBuilding.devicesTitle'))->tab($buildingTab);
        CRUD::field('building.copier')->type("text")->label(trans('libraryBuilding.copier'))->size(3)->tab($buildingTab);
        CRUD::field('building.additional_devices')->type("text")->label(trans('libraryBuilding.additionalDevices'))->size(9)->tab($buildingTab);

        CRUD::field('keys_title')->type('new_section')->title(trans('libraryBuilding.keysTitle'))->tab($buildingTab);
        CRUD::field('building.key')->label(trans('libraryBuilding.key'))->type('checkbox')->wrapper(['class' => 'form-group col-md-3 pt-5'])->tab($buildingTab);
        CRUD::field('building.key_depot')->type("text")->label(trans('libraryBuilding.keyDepot'))->size(3)->tab($buildingTab);
        CRUD::field('building.key_comment')->type("textarea")->attributes(['rows' => 5])->label(trans('libraryBuilding.keyComment'))->size(6)->tab($buildingTab);
        CRUD::field('operating_space_title')->type('new_section')->title(trans('libraryBuilding.operatingSpaceTitle'))->tab($buildingTab);
        CRUD::field('building.operating_area')->type("text")->label(trans('libraryBuilding.operatingArea'))->size(3)->tab($buildingTab);
        CRUD::field('building.audience_area')->type("text")->label(trans('libraryBuilding.audienceArea'))->size(3)->tab($buildingTab);
        CRUD::field('workspace_title')->type('new_section')->title(trans('libraryBuilding.workspaceTitle'))->tab($buildingTab);
        CRUD::field('building.staff_workspaces')->type("text")->label(trans('libraryBuilding.staffWorkspaces'))->size(3)->tab($buildingTab);
        CRUD::field('building.audience_workspaces')->type("text")->label(trans('libraryBuilding.audienceWorkspaces'))->size(3)->tab($buildingTab);
        CRUD::field('building.workspace_comment')->type("textarea")->attributes(['rows' => 5])->label(trans('libraryBuilding.workspaceComment'))->size(6)->tab($buildingTab);
        CRUD::field('space_title')->type('new_section')->title(trans('libraryBuilding.spaceTitle'))->tab($buildingTab);
        CRUD::field('building.space_comment')->type("textarea")->attributes(['rows' => 5])->label(trans('libraryBuilding.spaceComment'))->size(6)->tab($buildingTab);

        //SLSP
        CRUD::field('slsp_title')->type('new_section')->title(trans('librarySlsp.slspTitle'))->tab($slspTab);
        CRUD::field('slsp.status')->label(trans('librarySlsp.status'))->type('enum')
            ->enum_function('translate')->enum_class(SlspState::class)->size(3)->tab($slspTab);
        CRUD::field('slsp.status_comment')->type("textarea")->attributes(['rows' => 5])->label(trans('librarySlsp.statusComment'))->size(6)->tab($slspTab);
        CRUD::field('cost_title')->type('new_section')->title(trans('librarySlsp.costTitle'))->tab($slspTab);
        CRUD::field('slsp.cost')->label(trans('librarySlsp.cost'))->type('enum')
            ->enum_function('translate')->enum_class(SlspCost::class)->size(3)->tab($slspTab);
        CRUD::field('slsp.usage')->label(trans('librarySlsp.usage'))->type('enum')
            ->enum_function('translate')->enum_class(IzUsageCost::class)->size(3)->tab($slspTab);
        CRUD::field('slsp.cost_comment')->type("textarea")->attributes(['rows' => 5])->label(trans('librarySlsp.costComment'))->size(6)->tab($slspTab);
        CRUD::field('agreement_title')->type('new_section')->title(trans('librarySlsp.agreementTitle'))->tab($slspTab);
        CRUD::field('slsp.agreement')->label(trans('librarySlsp.agreement'))->type('enum')
            ->enum_function('translate')->enum_class(SlspAgreement::class)->size(3)->tab($slspTab);
        CRUD::field('slsp.agreement_comment')->type("textarea")->attributes(['rows' => 5])->label(trans('librarySlsp.agreementComment'))->size(6)->tab($slspTab);
        CRUD::field('fte_title')->type('new_section')->title(trans('librarySlsp.fteTitle'))->tab($slspTab);
        CRUD::field('slsp.ftes')->type("text")->label(trans('librarySlsp.ftes'))->size(3)->tab($slspTab);
        CRUD::field('slsp.fte_comment')->type("textarea")->attributes(['rows' => 5])->label(trans('librarySlsp.fteComment'))->size(6)->tab($slspTab);
        CRUD::field('agreement_clear')->type('new_section')->tab($slspTab);
        CRUD::field('slsp.comment')->type("textarea")->attributes(['rows' => 5])->label(trans('librarySlsp.comment'))->size(9)->tab($slspTab);
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

    public function update($id)
    {
        $this->decodeTableFieldsFromRequest();
        return $this->traitUpdate($id);
    }

    public function store()
    {
        $this->decodeTableFieldsFromRequest();
        return $this->traitStore();
    }

    private function decodeTableFieldsFromRequest()
    {
        $request = $this->crud->getRequest();
        $catalogField = $request->get('catalog');

        $izFields = $catalogField['iz_fields'];
        if (is_string($izFields))
            $catalogField['iz_fields'] = json_decode($izFields ?? '', true);

        $nzFields = $catalogField['nz_fields'];
        if (is_string($nzFields))
            $catalogField['nz_fields'] = json_decode($nzFields ?? '', true);

        $request->request->set('catalog', $catalogField);
        $this->crud->setRequest($request);
    }
}
