<?php

namespace App\Exports;

use App\Exports\Traits\ExcelColumnAutoSize;
use App\Models\PersonFunction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Style;
use Illuminate\Http\Request;

class PersonFunctionExport implements FromQuery, WithEvents, WithMapping, WithHeadings, WithDefaultStyles, WithTitle
{
    use ExcelColumnAutoSize;

    private $onlyActive = NULL;
    private $associatedType = NULL;
    private $stateType = NULL;
    private $training = NULL;
    private $education = NULL;
    private $work = NULL;
    private $onlyExited = NULL;
    private $onlyAddressList = NULL;
    private $onlyEmailList = NULL;
    private $onlyPesonalLogin = NULL;
    private $onlyImpersonalLogin = NULL;
    private $slspContact = NULL;

    public function filter(Request $request)
    {
        $onlyActive = $request->input('active');
        if ($onlyActive !== NULL)
            $this->onlyActive = $onlyActive == "1";

        $this->associatedType = $request->input('associated_type');
        $this->stateType = $request->input('state_type');
        $this->training = $request->input('training');
        $this->education = $request->input('education');
        $this->work = $request->input('work');

        $onlyExited = $request->input('exited');
        if ($onlyExited !== NULL)
            $this->onlyExited = $onlyExited == "1";

        $onlyAddressList = $request->input('address_list');
        if ($onlyAddressList !== NULL)
            $this->onlyAddressList = $onlyAddressList == "1";

        $onlyEmailList = $request->input('email_list');
        if ($onlyEmailList !== NULL)
            $this->onlyEmailList = $onlyEmailList == "1";

        $onlyPesonalLogin = $request->input('personal_login');
        if ($onlyPesonalLogin !== NULL)
            $this->onlyPesonalLogin = $onlyPesonalLogin == "1";

        $onlyImpersonalLogin = $request->input('impersonal_login');
        if ($onlyImpersonalLogin !== NULL)
            $this->onlyImpersonalLogin = $onlyImpersonalLogin == "1";

        $this->slspContact = $request->input('slsp_contact');

        return $this;
    }

    public function query()
    {
        $qry = PersonFunction::query();

        $qry->select([
            'person_functions.*',
            'people.id as people_id',
            'libraries.id as library_id',
            'people.last_name',
            'libraries.bibcode'
        ]);

        $qry->join('people', 'people.id', '=', 'person_functions.person_id')
            ->join('libraries', 'libraries.id', '=', 'person_functions.library_id');

        if ($this->onlyActive !== NULL)
            $qry->where('libraries.is_active', $this->onlyActive);

        if ($this->training !== NULL) {
            $qry->whereHas('person', function ($query) {
                $query->where('training', $this->training);
            });
        }

        if ($this->education !== NULL) {
            $qry->whereHas('person', function ($query) {
                $query->where('education', $this->education);
            });
        }

        if ($this->associatedType !== NULL) {
            $qry->whereHas('library', function ($query) {
                $query->where('associated_type', $this->associatedType);
            });
        }

        if ($this->stateType !== NULL) {
            $qry->whereHas('library', function ($query) {
                $query->where('state_type', $this->stateType);
            });
        }

        if ($this->work !== NULL)
            $qry->where('work', $this->work);

        if ($this->onlyExited !== NULL)
            $qry->where('exited', $this->onlyExited);

        if ($this->onlyAddressList !== NULL)
            $qry->where('address_list', $this->onlyAddressList);

        if ($this->onlyEmailList !== NULL)
            $qry->where('email_list', $this->onlyEmailList);

        if ($this->onlyPesonalLogin !== NULL)
            $qry->where('personal_login', $this->onlyPesonalLogin);

        if ($this->onlyImpersonalLogin !== NULL)
            $qry->where('impersonal_login', $this->onlyImpersonalLogin);

        if ($this->slspContact !== NULL)
            $qry->where('slsp_contact', $this->slspContact);

        return $qry->orderBy('people.last_name')->orderBy('libraries.bibcode');
    }

    public function headings(): array
    {
        return [
            trans('person.lastName'),
            trans('person.firstName'),
            trans('person.gender'),
            trans('person.seal'),
            trans('person.comment'),
            trans('library.bibcode'),
            trans('library.singular'),
            trans('library.name'),
            trans('library.associatedType'),
            trans('library.stateType'),
            trans('personFunction.phone'),
            trans('personFunction.email'),
            trans('personFunction.work'),
            trans('personFunction.percentageOfEmployment'),
            trans('personFunction.presenceTimes'),
            trans('personFunction.workStart'),
            trans('personFunction.workEnd'),
            trans('personFunction.exited'),
            trans('personFunction.addressList'),
            trans('personFunction.emailList'),
            trans('personFunction.institution'),
            trans('personFunction.personalLogin'),
            trans('personFunction.personalLoginComment'),
            trans('personFunction.impersonalLogin'),
            trans('personFunction.impersonalLoginComment'),
            trans('personFunction.slspContact'),
            trans('personFunction.functionComment'),
            trans('person.training'),
            trans('person.trainingCataloging'),
            trans('person.trainingIndexing'),
            trans('person.trainingAcquisition'),
            trans('person.trainingMagazine'),
            trans('person.trainingLending'),
            trans('person.trainingEmedia'),
            trans('person.education'),
            trans('person.slspAcq'),
            trans('person.slspAcqPlus'),
            trans('person.slspAcqCertified'),
            trans('person.digirechShare'),
            trans('person.slspCat'),
            trans('person.slspCatPlus'),
            trans('person.slspCatCertified'),
            trans('person.slspEmedia'),
            trans('person.slspEmediaPlus'),
            trans('person.slspEmediaCertified'),
            trans('person.slspCirc'),
            trans('person.slspCircPlus'),
            trans('person.slspCircCertified'),
            trans('person.slspCircDesk'),
            trans('person.slspCircLimited'),
            trans('person.slspStudentCertified'),
            trans('person.slspAnalytics'),
            trans('person.slspAnalyticsAdmin'),
            trans('person.slspAnalyticsCertified'),
            trans('person.slspSysadmin'),
            trans('person.slspStaffManager'),
            trans('person.accessRightComment'),
            trans('person.slspCertificationComment'),
            trans('person.catalogingLevel'),
            trans('person.slsPhereAccess'),
            trans('person.slsPhereAccessComment'),
            trans('person.almaCompleted'),
            trans('person.edocLogin'),
            trans('person.edocFullText'),
            trans('person.edocBibliographic'),
            trans('person.edocComment')
        ];
    }

    public function map($function): array
    {
        return [
            $function->person->last_name,
            $function->person->first_name,
            $function->person->gender,
            $function->person->seal,
            $function->person->comment,
            $function->library->bibcode,
            $function->library->is_active ? trans('library.isActive') : trans('library.isInactive'),
            $function->library->name,
            $function->library->associated_type?->translate(),
            $function->library->state_type?->translate(),
            $function->phone,
            $function->email,
            $function->work?->translate(),
            $function->percentage_of_employment,
            $function->presence_times,
            $function->work_start,
            $function->work_end,
            $function->exited ? trans('general.yes') : trans('general.no'),
            $function->address_list ? trans('general.yes') : trans('general.no'),
            $function->email_list ? trans('general.yes') : trans('general.no'),
            $function->institution?->translate(),
            $function->personal_login ? trans('general.yes') : trans('general.no'),
            $function->personal_login_comment,
            $function->impersonal_login ? trans('general.yes') : trans('general.no'),
            $function->impersonal_login_comment,
            $function->slsp_contact?->translate(),
            $function->function_comment,
            $function->person->training?->translate(),
            $function->person->training_cataloging ? trans('general.yes') : trans('general.no'),
            $function->person->training_indexing ? trans('general.yes') : trans('general.no'),
            $function->person->training_acquisition ? trans('general.yes') : trans('general.no'),
            $function->person->training_magazine ? trans('general.yes') : trans('general.no'),
            $function->person->training_lending ? trans('general.yes') : trans('general.no'),
            $function->person->training_emedia ? trans('general.yes') : trans('general.no'),
            $function->person->education?->translate(),
            $function->person->slsp_acq ? trans('general.yes') : trans('general.no'),
            $function->person->slsp_acq_plus ? trans('general.yes') : trans('general.no'),
            $function->person->slsp_acq_certified ? trans('general.yes') : trans('general.no'),
            $function->person->digirech_share ? trans('general.yes') : trans('general.no'),
            $function->person->slsp_cat ? trans('general.yes') : trans('general.no'),
            $function->person->slsp_cat_plus ? trans('general.yes') : trans('general.no'),
            $function->person->slsp_cat_certified ? trans('general.yes') : trans('general.no'),
            $function->person->slsp_emedia ? trans('general.yes') : trans('general.no'),
            $function->person->slsp_emedia_plus ? trans('general.yes') : trans('general.no'),
            $function->person->slsp_emedia_certified ? trans('general.yes') : trans('general.no'),
            $function->person->slsp_circ ? trans('general.yes') : trans('general.no'),
            $function->person->slsp_circ_plus ? trans('general.yes') : trans('general.no'),
            $function->person->slsp_circ_certified ? trans('general.yes') : trans('general.no'),
            $function->person->slsp_circ_desk ? trans('general.yes') : trans('general.no'),
            $function->person->slsp_circ_limited ? trans('general.yes') : trans('general.no'),
            $function->person->slsp_student_certified ? trans('general.yes') : trans('general.no'),
            $function->person->slsp_analytics ? trans('general.yes') : trans('general.no'),
            $function->person->slsp_analytics_admin ? trans('general.yes') : trans('general.no'),
            $function->person->slsp_analytics_certified ? trans('general.yes') : trans('general.no'),
            $function->person->slsp_sysadmin ? trans('general.yes') : trans('general.no'),
            $function->person->slsp_staff_manager ? trans('general.yes') : trans('general.no'),
            $function->person->access_right_comment,
            $function->person->slsp_certification_comment,
            $function->person->cataloging_level?->translate(),
            $function->person->sls_phere_access ? trans('general.yes') : trans('general.no'),
            $function->person->sls_phere_access_comment,
            $function->person->alma_completed ? trans('general.yes') : trans('general.no'),
            $function->person->edoc_login ? trans('general.yes') : trans('general.no'),
            $function->person->edoc_full_text ? trans('general.yes') : trans('general.no'),
            $function->person->edoc_bibliographic ? trans('general.yes') : trans('general.no'),
            $function->person->edoc_comment
        ];
    }

    public function title(): string
    {
        return trans('export.personFunctionSheet');
    }

    public function defaultStyles(Style $defaultStyle)
    {
        return $defaultStyle->getAlignment()->setVertical(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP
        );
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $this->autoSizeColumns($event);
            },
        ];
    }
}